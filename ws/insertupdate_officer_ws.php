<?php
//========== Web Service สำหรับบันทึกข้อมูลลงใน DB ============ //
//========= เกิดขึ้นทุกครั้งทีมีการ upload ภาพขึ้นไป =============== //
//========== จะต้องทำการบันทึกเพื่อเก็บลงในคลังภาพ ============== //

include "../inc/config.php";

$ret_msg = array("message" => "");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titlename = $_POST["titlename"];
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $position = $_POST["position"];
    $controlunit = $_POST["controlunit"];
    $graduation = $_POST["graduation"];
    $download_URL = $_POST["download_URL"];
    $picture_URL = $_POST["picture_URL"];
    $status1 = $_POST["status"];
    $seq = $_POST["seq"];

    $sql = "INSERT INTO officer (titlename,name,surname,position,controlunit,graduation,picture_URL,download_URL,seq,status) VALUES (?,?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssssis',$titlename,$name,$surname,$position,$controlunit,$graduation,$picture_URL,$download_URL,$seq,$status1);
    $stmt->execute();

    if ($stmt->affected_rows>0) {
        $ret_msg["message"] = "OK";
    } else {
        $ret_msg["message"] = "FAIL Insert";
    }
    echo json_encode($ret_msg);
} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    parse_str(file_get_contents("php://input"), $post_vars);

    // UPDATE
    $id = $post_vars["id"];
    $titlename = $post_vars["titlename"];
    $name = $post_vars["name"];
    $surname = $post_vars["surname"];
    $position = $post_vars["position"];
    $controlunit = $post_vars["controlunit"];
    $graduation = $post_vars["graduation"];
    $download_URL = $post_vars["download_URL"];
    $picture_URL = $post_vars["picture_URL"];
    $seq=$post_vars["seq"];
    $status1 = $post_vars["status"];

    $sql = "UPDATE officer SET titlename=?,name=?,surname=?,position=?,controlunit=?,graduation=?,download_URL=?,picture_URL=?,seq=?,status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssssisi',$titlename,$name,$surname,$position,$controlunit,$graduation,$download_URL,$picture_URL,$seq,$status1,$id);
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
