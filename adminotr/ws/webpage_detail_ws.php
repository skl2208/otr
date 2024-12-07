<?php
//========== Web Service สำหรับบันทึกข้อมูลลงใน DB ============ //
//========= เกิดขึ้นทุกครั้งทีมีการ upload ภาพขึ้นไป =============== //
//========== จะต้องทำการบันทึกเพื่อเก็บลงในคลังภาพ ============== //

include "../inc/config.php";

$ret_msg = array("message" => "","info"=>"");
$info = array();
$id = "";

if(isset($_GET["id"])) {
    $id = $_GET["id"];
}

if($id != "") {
    $sql = "SELECT id,webpage_name,content,status FROM webpage WHERE id=$id";
    $result = $conn->query($sql);
    if($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ret_msg["message"] = "OK";
        $info["id"] = $row["id"];
        $info["webpage_name"] = $row["webpage_name"];
        $info["content"] = $row["content"];
        $info["status"] = $row["status"];
        $ret_msg["info"] = $info;
    }
    echo json_encode($ret_msg);

} else {

    $ret_msg["message"] = "FAIL";
    echo json_encode($ret_msg);
}

$conn->close();
