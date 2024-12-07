<?php
//========== Web Service สำหรับบันทึกข้อมูลลงใน DB ============ //
//========= เกิดขึ้นทุกครั้งทีมีการ upload ภาพขึ้นไป =============== //
//========== จะต้องทำการบันทึกเพื่อเก็บลงในคลังภาพ ============== //

include "../inc/config.php";

$ret_msg = array("message" => "");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // New vdo insert
    $is_youtube = $_POST["is_youtube"];
    $status = $_POST["status"];
    $vdo_url = $_POST["vdo_url"];
    $vdo_desc = $_POST["vdo_desc"];
    $catagory = $_POST["catagory"];
    $attach_file_url = $_POST["attach_file_url"];
    $src_clip = $_POST["src_clip"];
    $pin = $_POST["pin"];

    $sql = "INSERT INTO vdo (id_catagory,is_youtube,status,vdo_url,vdo_desc,attach_file_url,src_clip,pin) VALUES (?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssss',$catagory,$is_youtube,$status,$vdo_url,$vdo_desc,$attach_file_url,$src_clip,$pin);
    $stmt->execute();

    if ($stmt->affected_rows) {
        $ret_msg["message"] = "OK";
    } else {
        $ret_msg["message"] = "FAIL Insert";
    }
    echo json_encode($ret_msg);
} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    parse_str(file_get_contents("php://input"), $post_vars);

    // UPDATE
    $is_youtube = $post_vars["is_youtube"];
    $vdo_url = $post_vars["vdo_url"];
    $vdo_desc = $post_vars["vdo_desc"];
    $catagory = $post_vars["catagory"];
    $status = $post_vars["status"];
    $src_clip = $post_vars["src_clip"];
    $attach_file_url = $post_vars["attach_file_url"];
    $pin = $post_vars["pin"];
    $id = $post_vars["id"];

    if (strtolower($status) == "on") {
        $status = "Y";
    }
    if (strtolower($status) == "off") {
        $status = "N";
    }

    $sql = "UPDATE vdo SET id_catagory=?,vdo_desc=?,vdo_url=?,status=?,is_youtube=?,src_clip=?,update_date=CURRENT_TIMESTAMP(),attach_file_url=?,pin=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssssi',$catagory,$vdo_desc,$vdo_url,$status,$is_youtube,$src_clip,$attach_file_url,$pin,$id);
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
