<?php
//========== Web Service สำหรับบันทึกข้อมูลลงใน DB ============ //
//========= เกิดขึ้นทุกครั้งทีมีการ upload ภาพขึ้นไป =============== //
//========== จะต้องทำการบันทึกเพื่อเก็บลงในคลังภาพ ============== //

include "../inc/config.php";

//========= Input ==========
// var inData = {
//     "menu_id" : menu_id,
//     "webpage_id" : webpage_id,
//     "external_URL" : external_URL,
//     "is_external" : is_external
// }
//========= Output ==========
// {
// "message" : "<ข้อความตอบกลับ>"
// }


//return data 
// "message" : "" ( OK , NO Data , FAIL )
$ret_msg = array("message" => "", "info" => "");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //===== No action for post =====//
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET["id"])) {
        $menu_id = $_GET["id"];
        $sql = "SELECT * FROM menu WHERE id=$menu_id";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $ret_msg["message"] = "OK";
            $data = array(
                "id" => $row["id"],
                "menu_name" => $row["menu_name"],
                "link_url" => ($row["link_url"] == null) ? "" : $row["link_url"],
                "is_external" => ($row["is_external"] == null ? "" : $row["is_external"])
            );
            $ret_msg["info"] = $data;
        } else {
            $ret_msg["message"] = "FAIL";
            $ret_msg["info"] = "Cannot load data with query " . $sql;
        }
    } else {
        $ret_msg = "OK, Connected to Web Service Successful !";
    }

    echo json_encode($ret_msg);
} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    parse_str(file_get_contents("php://input"), $post_vars);

    // UPDATE
    $menu_id = $post_vars["menu_id"];
    $webpage_id = $post_vars["webpage_id"];
    $external_URL = $post_vars["external_URL"];
    $is_external = $post_vars["is_external"];

    if ($is_external == 'N') {
        //link ไปยัง webpage ภายใน
        $sql = "UPDATE menu SET link_url=?,is_external='N' WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si',$webpage_id,$menu_id);
    } else {
        //link ไปยัง webpage ภายนอก
        $sql = "UPDATE menu SET link_url=?,is_external='Y' WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si',$external_URL,$menu_id);
    }
    $stmt->execute();

    $info = "sql=" . $sql;
    if ($stmt->affected_rows > 0) {
        $ret_msg["message"] = "OK";
    } else {
        $ret_msg["message"] = "FAIL Menu Link Updating";
    }
    $ret_msg["info"] = $info;
    echo json_encode($ret_msg);
} else {
    echo "OK, Connected to Web Service Successful !";
}
$conn->close();
