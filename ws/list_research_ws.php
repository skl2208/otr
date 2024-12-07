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
if (isset($_POST["rows_per_page"])) {
    $rows_per_page = $_POST["rows_per_page"];
}

if (isset($_POST["searchTxt"])) {
    $searchTxt = str_replace("'", "", trim($_POST["searchTxt"]));
    $condition = " AND (topic LIKE '%" . $searchTxt . "%' OR topicen LIKE '%" . $searchTxt . "%' OR name LIKE '%" . $searchTxt . "%' OR advisor LIKE '%" . $searchTxt . "%')";
}

if (isset($_POST["select_group"])) {
    $select_group = trim($_POST["select_group"]);
    if ($select_group != "") {
        $condition .= " AND group_name = '" . $select_group . "' ";
    }
}
if (isset($_POST["research_year"])) {
    $research_year = trim($_POST["research_year"]);
    if ($research_year != null && $research_year != "") {
        $condition .= " AND research_year = " . $research_year;
    }
}
//return data 
// "message" : "" ( OK , NO Data , FAIL )
$ret_msg = array("message" => "", "total" => "", "current_page" => "","etc"=>"","info" => array());
$sql = "SELECT count(id) AS totalrec FROM research WHERE status='Y' " . $condition;
$result0 = $conn->query($sql);
if ($result0) {
    $row0 = $result0->fetch_assoc();
    $total_all_record = $row0["totalrec"];
}

$sql = "SELECT id,topic,topicen,name,advisor,download_url,group_name FROM research WHERE status='Y' " . $condition . " ORDER BY update_date DESC,create_date DESC LIMIT " . (($pageno - 1) * $rows_per_page) . "," . $rows_per_page;
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $ret_msg["message"] = "OK";
    $ret_msg["total"] = $total_all_record;
    $ret_msg["current_page"] = $pageno;
    $ret_msg["rows_per_page"] = $rows_per_page;
    $ret_msg["etc"] = $sql;

    $info = array(
        "id" => "",
        "topic" => "",
        "topicen" => "",
        "name" => "",
        "advisor" => "",
        "download_url" => "",
        "group_name" => ""
    );
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        $info["id"] = $row["id"];
        $info["topic"] = $row["topic"];
        $info["topicen"] = $row["topicen"];
        $info["name"] = $row["name"];
        $info["advisor"] = $row["advisor"];
        $info["download_url"] = $row["download_url"];
        $info["group_name"] = $row["group_name"];

        array_push($ret_msg["info"], $info);
    }
} else {
    $ret_msg["message"] = "Fail";
    $ret_msg["current_page"] = $pageno;
    $ret_msg["etc"] = $sql;
}
echo json_encode($ret_msg);
