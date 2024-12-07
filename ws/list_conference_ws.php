<?php
//========== Web Service สำหรับเรียกดูอัลบัมภาพจากทุกกลุ่ม ============ //
//===== ตัวอย่างในการแสดงผลอยู่ที่ addeditnews.php#uploadalbum ====== //
//===== ตัวเรียก Web Service อยู่ใน index.js function showAlbum ==== //

include "../include/config.php";

//========= Input ==========
// {
//     pageno : <เลขหน้า page>,
//     rows_per_page : <จำนวน rec ต่อ 1 หน้า>,
//     searchTxt : <หมวด/กลุ่ม>
// }
//========= Output ==========
// {
// "message" : "<ข้อความตอบกลับ>",
// "total" : "<จำนวน record>",
// "current_page" : <หน้าที่>,
// "no_page" : <จำนวนหน้า>,
// "info" : [
//     {
//         "id":<id ของ record>,
//     }
// ]}
//======= default =========
$pageno = 1;
$rows_per_page = 12;
$searchTxt = "";
$select_group = "";
$condition = "";

//========= Get variable ==========
if (isset($_POST["pageno"])) {
    $pageno = $_POST["pageno"];
}
if (isset($_POST["searchTxt"])) {
    $searchTxt = str_replace("'","",trim($_POST["searchTxt"]));
    $condition = " AND (topic_confer LIKE '%" . $searchTxt . "%' OR detail LIKE '%".$searchTxt."%' OR place LIKE '%".$searchTxt."%')";
}

if (isset($_POST["select_group"])) {
    $select_group = $_POST["select_group"];
    if($select_group!="") {
        $condition .= " AND type_confer = '" . $select_group."' ";
    }
    
}

//return data 
// "message" : "" ( OK , NO Data , FAIL )
$ret_msg = array("message" => "", "total" => "", "current_page" => "", "info" => array());
$sql = "SELECT count(id) AS totalrec FROM conference WHERE 1 ". $condition;
$result0 = $conn->query($sql);
if ($result0) {
    $row0 = $result0->fetch_assoc();
    $total_all_record = $row0["totalrec"];
}

$sql = "SELECT id,topic_confer,picture_URL,type_confer,place,start_confer_date,end_confer_date FROM conference WHERE 1 " . $condition . " ORDER BY start_confer_date DESC,end_confer_date DESC LIMIT " . (($pageno - 1) * $rows_per_page) . "," . $rows_per_page;
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $ret_msg["message"] = "OK";
    $ret_msg["total"] = $total_all_record;
    $ret_msg["current_page"] = $pageno;
    $ret_msg["rows_per_page"] = $rows_per_page;

    $info = array(
        "id" => "", 
        "topic_confer" => "", 
        "picture_URL" => "", 
        "type_confer" => "", 
        "place" => "",
        "start_confer_date" => "", 
        "end_confer_date" => ""
    );
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        $info["id"] = $row["id"];
        $info["topic_confer"] = $row["topic_confer"];
        $info["picture_URL"] = $row["picture_URL"];
        $info["type_confer"] = $row["type_confer"];
        $info["place"] = $row["place"];
        $info["start_confer_date"] = $row["start_confer_date"];
        $info["end_confer_date"] = $row["end_confer_date"];

        array_push($ret_msg["info"], $info);
    }
} else {
    $ret_msg["message"] = "NO Data:" . $sql;
    $ret_msg["current_page"] = $pageno;
}
echo json_encode($ret_msg);
