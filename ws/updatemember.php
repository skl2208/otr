<?php
include "../include/config.php";

$ret_msg = array("message" => "", "info" => "", "query" => "");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = isset($_POST["username"]) ? $_POST["username"] : "";
    $titlename = isset($_POST["titlename"]) ? $_POST["titlename"] : "";
    $name = isset($_POST["name"]) ? $_POST["name"] : "";
    $surname = isset($_POST["surname"]) ? $_POST["surname"] : "";
    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    $telephone = isset($_POST["telephone"]) ? $_POST["telephone"] : "";

    $sql = "update user set titlename='$titlename',name='$name',surname='$surname',email='$email',telephone='$telephone',update_date=CURRENT_TIMESTAMP() WHERE username='$username'";
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
        $ret_msg["query"] = $sql;
        echo json_encode($ret_msg);
    }
} else {
    echo "OK, Connected to Web Service Successful !";
}
$conn->close();
