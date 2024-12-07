<?php
include "../inc/config.php";

$ret_msg = array("message" => "", "info" => "");
$info = array("id" => "");

if (isset($_POST["content"])) {
    $detail = $_POST["content"];
}

$sql = "INSERT INTO tmp_news (detail) VALUES ('$detail')";
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
