<?php
include "../include/config.php";

$ret_msg = array("message" => "", "info" => "", "query" => "");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = isset($_POST["username"]) ? $_POST["username"] : "";
    $oldpassword = isset($_POST["oldpassword"]) ? $_POST["oldpassword"] : "";
    $newpassword = $_POST["newpassword1"];

    $sql = "UPDATE user SET password='$newpassword' WHERE username='$username' AND password='$oldpassword'";
    $conn->query($sql);
    if ($conn->affected_rows > 0) {
        $info = "Update Successfully";
        $ret_msg["message"] = "OK";
        $ret_msg["info"] = $info;
        $ret_msg["query"] = $sql;
        echo json_encode($ret_msg);
    } else {
        $info = "Update Fail!";
        $ret_msg["message"] = "FAIL";
        $ret_msg["info"] = $info;
        $ret_msg["query"] = $sql.",".$conn->affected_rows;
        echo json_encode($ret_msg);
    }
} else {
    echo "OK, Connected to Web Service Successful !";
}
$conn->close();
