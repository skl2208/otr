<?php
//========== Web Service สำหรับเรียกดูอัลบัมภาพจากทุกกลุ่ม ============ //
//===== ตัวอย่างในการแสดงผลอยู่ที่ addeditnews.php#uploadalbum ====== //
//===== ตัวเรียก Web Service อยู่ใน index.js function showAlbum ==== //

session_start();

include "inc/config.php";
include "../include/checkadmin.php";

//========= Input ==========
// {
//     id : <id ภาพ>
// }
//========= Output ==========
// {
// "message" : "<ข้อความตอบกลับ>",
// "info" : {
//      "id":<id ของ record>,
//      "image_url" : <url ของ image>,
//      "image_desc" : <คำอธิบายภาพ>,
//      "catagory" : <ชื่อกลุ่มภาพ>
//  }
// }

$id = 0;  // default
if (isset($_POST["id"])) {
    $id = $_POST["id"];
}

//return data 
// "message" : "" ( OK , NO Data , FAIL )
$ret_msg = array("message" => "", "info" => "");
$info = array("id" => "", "catagory" => "", "image_url" => "", "image_desc" => "", "status" => "");

$sql = "SELECT id,catagory,image_url,image_desc,status FROM pic_activity WHERE id= $id";
$result = $conn->query($sql);
if ($result) {
    if ($result->num_rows > 0) {

        //==== preparing for return message
        $ret_msg["message"] = "OK";


        $row = $result->fetch_assoc();
        $info["id"] = $row["id"];
        $info["catagory"] = $row["catagory"];
        $info["image_url"] = $row["image_url"];
        $info["image_desc"] = ($row["image_desc"] == null ? "" : $row["image_desc"]);
        $info["status"] = $row["status"];

        $pos = strpos($info["image_url"], "http");
        if ($pos === false) {
            $info["image_url"] = $baseHTTP . $baseURL . $info["image_url"];
        }

        $ret_msg["info"] = $info;
    } else {
        $ret_msg["message"] = "NO Data";
    }
} else {
    $ret_msg["message"] = "FAIL : Cannot connect database";
}

echo json_encode($ret_msg);
