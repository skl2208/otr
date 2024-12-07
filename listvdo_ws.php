<?php
//========== Web Service สำหรับเรียกดูอัลบัมภาพจากทุกกลุ่ม ============ //
//===== ตัวอย่างในการแสดงผลอยู่ที่ addeditnews.php#uploadalbum ====== //
//===== ตัวเรียก Web Service อยู่ใน index.js function showAlbum ==== //

include "include/config.php";

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
//         "vdo_url" : <url ของ image>,
//         "vdo_desc" : <คำอธิบายภาพ>,
//         "id_catagory" : <ชื่อกลุ่มภาพ>,
//         "create_date" : <วันที่เริ่มสร้าง vdo>,
//         "update_date" : <วันที่ update ล่าสุด>,
//         "is_youtube" : <มาจาก link youtube หรือไม่>,
//         "attach_file_url" : <ที่อยู่ของไฟล์แนบ>
//     }
// ]}
//======= default =========
$pageno = 1;
$rows_per_page = 8;
$searchTxt = "";
$select_group = "";
$condition = "";

//========= Get variable ==========
if (isset($_POST["pageno"])) {
    $pageno = $_POST["pageno"];
}
if (isset($_POST["rows_per_page"])) {
    $rows_per_page = $_POST["rows_per_page"];
}

if (isset($_POST["searchTxt"])) {
    $searchTxt = str_replace("'","",trim($_POST["searchTxt"]));
    $condition = " AND vdo_desc LIKE '%" . $searchTxt . "%'";
}

if (isset($_POST["select_group"])) {
    $select_group = $_POST["select_group"];
    if($select_group!="") {
        $condition .= " AND id_catagory = " . $select_group;
    }
    
}

//return data 
// "message" : "" ( OK , NO Data , FAIL )
$ret_msg = array("message" => "", "total" => "", "current_page" => "", "info" => array());
$sql = "SELECT count(id) AS totalrec FROM vdo WHERE 1 AND status='Y' " . $condition;
$result0 = $conn->query($sql);
if ($result0) {
    $row0 = $result0->fetch_assoc();
    $total_all_record = $row0["totalrec"];
}

$sql = "SELECT vdo.id,id_catagory,vdo_url,vdo_desc,is_youtube,create_date,update_date,attach_file_url,src_clip,pin,vdo_group.catagory FROM vdo LEFT JOIN vdo_group ON vdo.id_catagory = vdo_group.id WHERE 1 " . $condition . " AND vdo.status='Y' ORDER BY pin DESC,update_date DESC LIMIT " . (($pageno - 1) * $rows_per_page) . "," . $rows_per_page;
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $ret_msg["message"] = "OK";
    $ret_msg["total"] = $total_all_record;
    $ret_msg["current_page"] = $pageno;
    $ret_msg["rows_per_page"] = $rows_per_page;

    $info = array("id" => "", "catagory" => "", "vdo_url" => "", "vdo_desc" => "");
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        $info["id"] = $row["id"];
        $info["id_catagory"] = $row["id_catagory"];
        $info["catagory"] = $row["catagory"];
        $info["pin"] = $row["pin"];
        $info["vdo_url"] = $row["vdo_url"];
        $pos = strpos($info["vdo_url"], "http");
        if ($pos === false) {
            $info["vdo_url"] = $baseHTTP . $baseURL . $info["vdo_url"];
        }
        $info["vdo_desc"] = ($row["vdo_desc"] == null ? "" : $row["vdo_desc"]);
        $info["is_youtube"] = $row["is_youtube"];
        $info["src_clip"] = $row["src_clip"];
        $info["create_date"] = $row["create_date"];
        $info["update_date"] = $row["update_date"];
        $info["attach_file_url"] = $row["attach_file_url"];
        if ($info["attach_file_url"] != null && $info["attach_file_url"] != "") {
            $pos = strpos($info["attach_file_url"], "http");
            if ($pos === false) {
                $info["attach_file_url"] = $baseHTTP . $baseURL . $info["attach_file_url"];
            }
        }

        array_push($ret_msg["info"], $info);
        //echo $info[$i++]."<br>";
    }
} else {
    $ret_msg["message"] = "NO Data:" . $sql;
    $ret_msg["current_page"] = $pageno;
}
echo json_encode($ret_msg);
