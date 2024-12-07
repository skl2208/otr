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
    $start_year = $_POST["start_year"];
    $picture_URL = $_POST["picture_URL"];
    $status1 = $_POST["status1"];

    $sql = "INSERT INTO residency (titlename,name,surname,position,start_year,picture_URL,status) VALUES ('$titlename','$name','$surname','$position','$start_year','$picture_URL','$status1')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssss',$titlename,$name,$surname,$position,$start_year,$picture_URL,$status1);
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
    $start_year = $post_vars["start_year"];
    $picture_URL = $post_vars["picture_URL"];
    $status1 = $post_vars["status1"];

    $sql = "UPDATE residency SET titlename=?,name=?,surname=?,position=?,start_year=?,picture_URL=?,status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssssi',$titlename,$name,$surname,$position,$start_year,$picture_URL,$status1,$id);
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
