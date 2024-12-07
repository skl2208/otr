<?php
//========== Web Service สำหรับบันทึกข้อมูลลงใน DB ============ //
//========= เกิดขึ้นทุกครั้งทีมีการ upload ภาพขึ้นไป =============== //
//========== จะต้องทำการบันทึกเพื่อเก็บลงในคลังภาพ ============== //

include "../inc/config.php";

$ret_msg = array("message" => "","info"=>"");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $webpage_name = $_POST["webpage_name"];
    $content = $_POST["content"];
    $status = $_POST["status"];
    $username = $_POST["username"];
    
    $sql = "INSERT INTO webpage (webpage_name,content,user_create,create_date,status) VALUES (?,?,?,CURRENT_TIMESTAMP,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss',$webpage_name,$content,$username,$status);
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
    $webpage_name = $post_vars["webpage_name"];
    $content = $post_vars["content"];
    $status = $post_vars["status"];
    $username = $post_vars["username"];

    $sql = "UPDATE webpage SET webpage_name=?,content=?,status=?,user_update=?,update_date=CURRENT_TIMESTAMP WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssi',$webpage_name,$content,$status,$username,$id);
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
