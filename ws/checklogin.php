<?php
include "../include/config.php";

if (!isset($_SESSION)) {
    session_start();
}

$username = $_POST["username"];
$password = $_POST["password"];
$message = array("message" => "FAIL", "reason" => "", "info" => "");
$info = array();

$sql = "SELECT * FROM user WHERE username=? AND password=? AND status='Y'";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $username,$password); // 's' specifies the variable type => 'string'
$stmt->execute();

$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $message["message"] = "OK";
    $message["reason"] = "Login Successfully";

    $info["titlename"] = $row["titlename"];
    $info["name"] = $row["name"];
    $info["surname"] = $row["surname"];
    $info["email"] = $row["email"];
    $info["telephone"] = $row["telephone"];

    $_SESSION["username"] = $row["username"];
    $_SESSION["usergroup"] = $row["usergroup"];
    $_SESSION["titlename"] = $row["titlename"];
    $_SESSION["name"] = $row["name"];
    $_SESSION["surname"] = $row["surname"];
    $_SESSION["telephone"] = $row["telephone"];
    $_SESSION["email"] = $row["email"];

    $info["session_name"] = isset($_SESSION);

    $message["info"] = $info;
} else {
    $message["message"] = "FAIL";
    $message["reason"] = "ข้อมูล Username หรือ รหัสผ่านไม่ถูกต้อง";
}

echo json_encode($message);
