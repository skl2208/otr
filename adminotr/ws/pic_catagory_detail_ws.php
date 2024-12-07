<?php
include "../inc/config.php";

$ret_msg = array("message" => "", "info" => "");

if (isset($_POST["id"])) {
    $id = $_POST["id"];
    //========= ไป Query รายละเอียด =========//
    $sql = "SELECT id,catagory,catagory_desc,status FROM pic_catagory WHERE id=$id";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        // Blinding
        $ret_msg["message"] = "OK";
        $info = array("id" => "", "catagory" => "", "catagory_desc" => "", "status" => "");

        $row = $result->fetch_assoc();
        $info["id"] = $row["id"];
        $info["catagory"] = $row["catagory"];
        $info["catagory_desc"] = $row["catagory_desc"];
        $info["status"] = $row["status"];
        
        $ret_msg["info"] = $info;
    } else {
        $ret_msg["message"] = "OK";
        $ret_msg["info"] = "NO DATA";
    }

    echo json_encode($ret_msg);
    $conn->close();
} else {
    $ret_msg["message"] = "FAIL:No id receive";
    echo json_encode($ret_msg);
}
