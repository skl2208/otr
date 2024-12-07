<?php
//========== Web Service สำหรับบันทึกข้อมูลลงใน DB ============ //
//========= เกิดขึ้นทุกครั้งทีมีการ upload ภาพขึ้นไป =============== //
//========== จะต้องทำการบันทึกเพื่อเก็บลงในคลังภาพ ============== //

include "../inc/config.php";

$ret_msg = array("message" => "");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $topic = $_POST["topic"];
    $name = $_POST["name"];
    $advisor = $_POST["advisor"];
    $content = $_POST["content"];
    $status1 = $_POST["status1"];
    $group_name = $_POST["group_name"];
    $download_url = $_POST["download_url"];

    $sql = "INSERT INTO research (topic,name,advisor,group_name,content,download_url,status) VALUES (?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssss',$topic,$name,$advisor,$group_name,$content,$download_url,$status1);
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
    $topic = $post_vars["topic"];
    $name = $post_vars["name"];
    $advisor = $post_vars["advisor"];
    $content = $post_vars["content"];
    $status1 = $post_vars["status1"];
    $group_name = $post_vars["group_name"];
    $download_url = $post_vars["download_url"];

    $sql = "UPDATE research SET group_name=?,download_url=?,status=?,topic=?,name=?,advisor=?,content=?,update_date=CURRENT_TIMESTAMP WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssssi',$group_name,$download_url,$status1,$topic,$name,$advisor,$content,$id);
    $stmt->execute();
    $conn->query($sql);
    if ($conn->affected_rows > 0) {
        $ret_msg["message"] = "OK";
    } else {
        $ret_msg["message"] = "FAIL Update";
    }
    echo json_encode($ret_msg);
} else {
    echo "OK, Connected to Web Service Successful !";
}
$conn->close();
