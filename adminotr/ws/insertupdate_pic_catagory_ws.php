<?php
//========== Web Service สำหรับบันทึกข้อมูลลงใน DB ============ //
//========= เกิดขึ้นทุกครั้งทีมีการ upload ภาพขึ้นไป =============== //
//========== จะต้องทำการบันทึกเพื่อเก็บลงในคลังภาพ ============== //

include "../inc/config.php";

$ret_msg = array("message" => "");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // New pic catagory insert
    $catagory = $_POST["catagory"];
    $catagory_desc = $_POST["catagory_desc"];
    $status = $_POST["status"];

    $sql = "INSERT INTO pic_catagory (catagory,catagory_desc,status) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss',$catagory,$catagory_desc,$status);
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
    $catagory = $post_vars["catagory"];
    $catagory_desc = $post_vars["catagory_desc"];
    $status = $post_vars["status"];

    $sql = "UPDATE pic_catagory SET catagory=?,catagory_desc=?,status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi',$catagory,$catagory_desc,$status,$id);
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
