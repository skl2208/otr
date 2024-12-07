<?php
//========== Web Service สำหรับเรียกดูอัลบัมภาพจากทุกกลุ่ม ============ //
//===== ตัวอย่างในการแสดงผลอยู่ที่ addeditnews.php#uploadalbum ====== //
//===== ตัวเรียก Web Service อยู่ใน index.js function showAlbum ==== //

session_start();

include "inc/config.php";
include "../include/checkadmin.php";
//========= Input ==========
// {
//     pageno : <เลขหน้า page>,
//     rows_per_page : <จำนวน rec ต่อ 1 หน้า>,
//     catagory : <หมวด/กลุ่ม>
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
//         "image_url" : <url ของ image>,
//         "image_desc" : <คำอธิบายภาพ>,
//         "catagory" : <ชื่อกลุ่มภาพ>
//     }
// ]}

$pageno = 1;  // default
if (isset($_POST["pageno"])) {
    $pageno = $_POST["pageno"];
}

$rows_per_page = 40; // default
if (isset($_POST["rows_per_page"])) {
    $rows_per_page = $_POST["rows_per_page"];
}

$condition = ""; // default
if(isset($_POST["catagory"]) && $_POST["catagory"] != "") {
    $condition = " AND catagory LIKE '%".$_POST["catagory"]."%'";
}
//return data 
// "message" : "" ( OK , NO Data , FAIL )
$ret_msg = array("message" => "", "total" => "", "current_page" => "", "info" => array());
$sql = "SELECT count(id) AS totalrec FROM pic_activity WHERE 1 ".$condition;
$result0 = $conn->query($sql);
if($result0) {
    $row0 = $result0->fetch_assoc();
    $total_all_record = $row0["totalrec"] ;
}

$sql = "SELECT id,catagory,image_url,image_desc FROM pic_activity WHERE 1 ".$condition." ORDER BY create_date DESC LIMIT " . (($pageno - 1) * $rows_per_page) . "," . $rows_per_page;
$result = $conn->query($sql);
if ($result) {
    if ($result->num_rows > 0) {
        $ret_msg["message"] = "OK";
        $ret_msg["total"] = $total_all_record;
        $ret_msg["current_page"] = $pageno;
        $ret_msg["rows_per_page"] = $rows_per_page;

        $info = array("id" => "", "catagory" => "", "image_url" => "", "image_desc" => "");
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $info["id"] = $row["id"];
            $info["catagory"] = $row["catagory"];
            $info["image_url"] = $row["image_url"];
            $pos = strpos($info["image_url"],"http");
            if($pos===false) {
                $info["image_url"] = $baseHTTP.$baseURL.$info["image_url"];
            }
            $info["image_desc"] = ($row["image_desc"]==null ? "" : $row["image_desc"]);

            array_push($ret_msg["info"], $info);
            //echo $info[$i++]."<br>";
        }
    } else {
        $ret_msg["message"] = "NO Data";
        $ret_msg["current_page"] = $pageno;
    }
} else {
    $ret_msg["message"] = "FAIL : Cannot connect database";
}

echo json_encode($ret_msg);
