<?php
include "../inc/config.php";

$ret_msg = array("message" => "", "info" => "");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nameth = $_POST["nameth"];
    $nameen = $_POST["nameen"];
    $status = $_POST["status"];
    $description = $_POST["description"];

    $sql = "INSERT INTO department (nameth,nameen,description,status) VALUES (?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss',$nameth,$nameen,$description,$status);
    $stmt->execute();

    if ($stmt->affected_rows>0) {
        $ret_msg["message"] = "OK";
    } else {
        $ret_msg["message"] = "FAIL Insert";
        $ret_msg["info"] = $sql;
    }
    echo json_encode($ret_msg);
} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    parse_str(file_get_contents("php://input"), $post_vars);

    // UPDATE
    $id = $post_vars["id"];
    $nameen = $post_vars["nameen"];
    $description = $post_vars["description"];
    $status = $post_vars["status"];

    $sql = "UPDATE department SET status=?,nameen=?,description=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssd',$status,$nameen,$description,$id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $ret_msg["message"] = "OK";
        $ret_msg["info"] = "sql=" . $sql;
    } else {
        $ret_msg["message"] = "FAIL Update";
    }
    echo json_encode($ret_msg);
} else {
    echo "OK, Connected to Web Service Successful !";
}
$conn->close();
