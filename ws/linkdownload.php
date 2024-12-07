<?php
include "../include/config.php";

$ret_msg = array("message" => "", "info" => "", "query" => "");

if (isset($_POST["command"]) && $_POST["command"] == "VIEW") {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $id = isset($_POST["id"]) ? $_POST["id"] : "";
    
        $sql = "SELECT hit FROM download WHERE id='$id'";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $info = array("HIT",$row["hit"]);
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

} elseif (isset($_POST["command"]) && $_POST["command"] == "UPDATE") {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $id = isset($_POST["id"]) ? $_POST["id"] : "";
    
        $sql = "UPDATE download SET hit=hit+1 WHERE id='$id'";
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
} else {
    echo "OK, Connected to Web Service Successful !";
}


$conn->close();
