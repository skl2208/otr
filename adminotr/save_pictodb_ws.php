<?php
//========== Web Service สำหรับบันทึกข้อมูลลงใน DB ============ //
//========= เกิดขึ้นทุกครั้งทีมีการ upload ภาพขึ้นไป =============== //
//========== จะต้องทำการบันทึกเพื่อเก็บลงในคลังภาพ ============== //

session_start();

include "inc/config.php";
include "../include/checkadmin.php";

//========= Input ==========
// inData = {
//     "typenews" : <ชื่อกลุ่มข้อมูล>,
//     "image_url" : <URL ของภาพ>,
//     "image_desc : <คำอธิบายภาพ>
// };
//========= Output ==========
// {
// "message" : "<ข้อความตอบกลับ>"
// }


//return data 
// "message" : "" ( OK , NO Data , FAIL )
$ret_msg = array("message" => "","info"=>"");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // New pic insert
    if(isset($_POST["typenews"]) && isset($_POST["image_url"])) {
        $typenews = $_POST["typenews"];
        $image_url = $_POST["image_url"];
    
        if (isset($_POST["image_desc"])) {
            $image_desc = $_POST["image_desc"];
        } else {
            $image_desc = "";
        }
    
        $sql = "INSERT INTO pic_activity (catagory,image_url,image_desc) VALUES (?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss',$typenews,$image_url,$image_desc);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $ret_msg["message"] = "OK";
            $ret_msg["info"] = $image_url;
        } else {
            $ret_msg["message"] = "FAIL";
        }
        echo json_encode($ret_msg);
    } else {
        $ret_msg["message"] = "INSERT ERROR ! No typenews or image_url";
        echo json_encode($ret_msg);
    }

} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    parse_str(file_get_contents("php://input"),$post_vars);

    if(isset($post_vars["typenews"]) && isset($post_vars["image_desc"]) && isset($post_vars["id"]) && isset($post_vars["status"])) {
        // UPDATE
        $typenews = $post_vars["typenews"];
        $image_desc = $post_vars["image_desc"];
        $id= $post_vars["id"];
        $status = $post_vars["status"];

        if(strtolower($status)=="on") {
            $status="Y";
        }
        if(strtolower($status)=="off") {
            $status="N";
        }
    
        $sql = "UPDATE pic_activity SET catagory=?,image_desc=?,status=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssi',$typenews,$image_desc,$status,$id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $ret_msg["message"] = "OK";
        } else {
            $ret_msg["message"] = "NOTHING UPDATE";
        }
        echo json_encode($ret_msg);
    } else {
        $ret_msg["message"] = "UPDATE ERROR ! No typenews or image_url or ID";
        echo json_encode($ret_msg);
    }

} else {
    echo "OK, Connected to Web Service Successful !";
}
$conn->close();
?>