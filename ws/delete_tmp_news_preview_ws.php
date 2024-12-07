<?php
include "../inc/config.php";

$id = "";

$ret_msg = array("message"=>"","info"=>"");
$info = array("id"=>"");

if(isset($_POST["id"])) {
    $id =$_POST["id"];
}

if($id=="") {
    $ret_msg["message"] = "FAIL";
    $ret_msg["info"] = "empty field!";
} else {
    $sql = "DELETE FROM tmp_news WHERE id=$id";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i',$id);
    $stmt->execute();

    if($stmt->affected_rows > 0) {
        $ret_msg["message"] = "OK";
        $ret_msg["info"] = "Delete Successfully";
    } else {
        $ret_msg["message"] = "FAIL";
        $ret_msg["info"] = "Delete ไม่สำเร็จ";
    }
}
$conn->close();
echo json_encode($ret_msg);
