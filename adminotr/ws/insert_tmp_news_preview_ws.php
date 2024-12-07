<?php
include "../inc/config.php";

$topic = "";
$picture_URL = "";
$detail = "";
$ret_msg = array("message"=>"","info"=>"");
$info = array("id"=>"");

if(isset($_POST["topic"])) {
    $topic =$_POST["topic"];
}
if(isset($_POST["picture_URL"])) {
    $picture_URL =$_POST["picture_URL"];
}
if(isset($_POST["detail"])) {
    $detail =$_POST["detail"];
}

if($topic=="" || $picture_URL=="" || $detail=="") {
    $ret_msg["message"] = "FAIL";
    $ret_msg["info"] = "empty field!";
} else {
    $sql = "INSERT INTO tmp_news (topic,picture_URL,detail) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss',$topic,$picture_URL,$detail);
    $stmt->execute();

    if($stmt->affected_rows>0) {
        $info["id"] = $stmt->insert_id;
        $ret_msg["message"] = "OK";
        $ret_msg["info"] = $info;
    } else {
        $ret_msg["message"] = "FAIL";
        $ret_msg["info"] = "Insert ไม่สำเร็จ:$sql";
    }
}
$conn->close();
echo json_encode($ret_msg);
