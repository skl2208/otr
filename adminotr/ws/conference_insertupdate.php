<?php
//========== Web Service สำหรับบันทึกข้อมูลลงใน DB ============ //
//========= เกิดขึ้นทุกครั้งทีมีการ upload ภาพขึ้นไป =============== //
//========== จะต้องทำการบันทึกเพื่อเก็บลงในคลังภาพ ============== //

include "../inc/config.php";

//========= Input ==========
// const inData = {
//     "id": id,
//     "titlename": titlename,
//     "name": name,
//     "surname": surname,
//     "position": position,
//     "controlunit": controlunit,
//     "graduation": graduation,
//     "status": status1
// }
//========= Output ==========
// {
// "message" : "<ข้อความตอบกลับ>"
// }


//return data 
// "message" : "" ( OK , NO Data , FAIL )
$ret_msg = array("message" => "","info"=>"");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $topic_confer = $_POST["topic_confer"];
    $type_confer = $_POST["type_confer"];
    $picture_URL = $_POST["picture_URL"];
    $detail = $_POST["detail"];
    $start_confer_date = $_POST["start_confer_date"];
    $end_confer_date = $_POST["end_confer_date"];
    $username = $_POST["username"];
    $place = $_POST["place"];
    
    $sql = "INSERT INTO conference (topic_confer,type_confer,picture_URL,detail,start_confer_date,end_confer_date,user_create,place) VALUES (?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssss',$topic_confer,$type_confer,$picture_URL,$detail,$start_confer_date,$end_confer_date,$username,$place); // 's' specifies the variable type => 'string'
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $ret_msg["message"] = "OK";
    } else {
        $ret_msg["message"] = "FAIL Update";
        $ret_msg["info"] = $sql;
    }

    echo json_encode($ret_msg);

} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    parse_str(file_get_contents("php://input"), $post_vars);

    // UPDATE
    $id = $post_vars["id"];
    $picture_URL = $post_vars["picture_URL"];
    $topic_confer = $post_vars["topic_confer"];
    $type_confer = $post_vars["type_confer"];
    $detail = $post_vars["detail"];
    $start_confer_date = $post_vars["start_confer_date"];
    $end_confer_date = $post_vars["end_confer_date"];
    $username = $post_vars["username"];
    $place = $post_vars["place"];

    $sql = "UPDATE conference SET picture_URL=?,topic_confer=?,type_confer=?,detail=?,start_confer_date=?,end_confer_date=?,user_update=?,update_date=CURRENT_TIMESTAMP,place=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssssi',$picture_URL,$topic_confer,$type_confer,$detail,$start_confer_date,$end_confer_date,$username,$place,$id);
    $stmt->execute();

     // Need to store the result into memory first
    //$conn->query($sql);
    if ($stmt->affected_rows > 0) {
        $ret_msg["message"] = "OK";
        $ret_msg["info"] = "sql=".$sql;
    } else {
        $ret_msg["message"] = "FAIL Update";
        $ret_msg["info"] = "stmt->num_rows=".$stmt->affected_rows." result1= ".$result1->num_rows;
    }
    echo json_encode($ret_msg);
} else {
    echo "OK, Connected to Web Service Successful !";
}
$conn->close();
