<?php
//========== Web Service สำหรับบันทึกข้อมูลลงใน DB ============ //
//========= เกิดขึ้นทุกครั้งทีมีการ upload ภาพขึ้นไป =============== //
//========== จะต้องทำการบันทึกเพื่อเก็บลงในคลังภาพ ============== //

include "../inc/config.php";

$ret_msg = array("message" => "","info"=>"");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $link_name = $_POST["link_name"];
    $link_url = $_POST["link_url"];
    $description = $_POST["description"];
    $seq = $_POST["seq"];
    $status = $_POST["status"];

    if($seq==0) {$seq=999;} 

    $sql = "INSERT INTO download (link_name,link_url,description,seq,status) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssis',$link_name,$link_url,$description,$seq,$status);
    $stmt->execute();

    if ($stmt->affected_rows) {
        $ret_msg["message"] = "OK";
        $ret_msg["info"] = "Insert Successfully";
    } else {
        $ret_msg["message"] = "FAIL";
        $ret_msg["info"] = $conn->error;
    }
    echo json_encode($ret_msg);

} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    parse_str(file_get_contents("php://input"), $post_vars);

    // UPDATE
    $id = $post_vars["id"];
    $seq = $post_vars["seq"];
    $link_name = $post_vars["link_name"];
    $link_url = $post_vars["link_url"];
    $description = $post_vars["description"];
    $status = $post_vars["status"];

    $sql = "UPDATE download SET link_name=?,link_url=?,description=?,status=?,seq=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssii',$link_name,$link_url,$description,$status,$seq,$id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $ret_msg["message"] = "OK";
        $ret_msg["info"] = "Update Successfully";
    } else {
        $ret_msg["message"] = "FAIL";
        $ret_msg["info"] = $stmt->error;
    }
    echo json_encode($ret_msg);
} else {
    echo "OK, Connected to Web Service Successful !";
}
$conn->close();
