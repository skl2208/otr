<?php
//========== Web Service สำหรับบันทึกข้อมูลลงใน DB ============ //
//========= เกิดขึ้นทุกครั้งทีมีการ upload ภาพขึ้นไป =============== //
//========== จะต้องทำการบันทึกเพื่อเก็บลงในคลังภาพ ============== //

include "../inc/config.php";

$ret_msg = array("message" => "");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $group_name = $_POST["group_name"];
    $download_url = $_POST["download_url"];
    $status1 = $_POST["status1"];

    $sql = "INSERT INTO resident_group (group_name,download_url,status) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss',$group_name,$download_url,$status1);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $ret_msg["message"] = "OK";
    } else {
        $ret_msg["message"] = "FAIL Insert";
    }
    echo json_encode($ret_msg);
} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    parse_str(file_get_contents("php://input"), $post_vars);

    // UPDATE
    $id = $post_vars["id"];
    $group_name = $post_vars["group_name"];
    $download_url = $post_vars["download_url"];
    $status1 = $post_vars["status1"];

    $sql = "UPDATE resident_group SET group_name=?,download_url=?,status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi',$group_name,$download_url,$status1,$id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $ret_msg["message"] = "OK";
    } else {
        $ret_msg["message"] = "FAIL Update";
    }
    echo json_encode($ret_msg);
} else {
    echo "OK, Connected to Web Service Successful !";
}
$conn->close();
