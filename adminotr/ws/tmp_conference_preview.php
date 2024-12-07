<?php
include "../inc/config.php";

$ret_msg = array("message" => "", "info" => "");
$info = array("id" => "");

if (isset($_POST["content"])) {
    $detail = $_POST["content"];
}
if (isset($_POST["topic"])) {
    $topic = $_POST["topic"];
}
if (isset($_POST["type_confer"])) {
    $type_confer = $_POST["type_confer"];
}
if (isset($_POST["picture_URL"])) {
    $picture_URL = $_POST["picture_URL"];
}
if (isset($_POST["info1"])) {
    $info1 = $_POST["info1"];
}
if (isset($_POST["info2"])) {
    $info2 = $_POST["info2"];
}
$sql = "INSERT INTO tmp_news (topic,picture_URL,detail,info1,info2,info3) VALUES ('$topic','$picture_URL','$detail','$info1','$info2','$type_confer')";
$result = $conn->query($sql);
if ($result) {
    $info["id"] = $conn->insert_id;
    $info["query"] = $sql;
    $ret_msg["message"] = "OK";
    $ret_msg["info"] = $info;
} else {
    $ret_msg["message"] = "FAIL";
    $ret_msg["info"] = "Insert ไม่สำเร็จ:$sql";
}

$conn->close();
echo json_encode($ret_msg);
