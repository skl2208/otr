<?php
include "../inc/config.php";

$ret_msg = array("message" => "", "info" => "");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $seq = $_POST["seq"];
    $menu_name = $_POST["menu_name"];
    $status = $_POST["status"];
    $parent_id = $_POST["parent_id"];

    $sql = "INSERT INTO menu (seq,menu_name,parent_id,status,is_item,level,is_external,is_header) VALUES (?,?,?,?,'Y',1,'N','N') ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isis',$seq,$menu_name,$parent_id,$status);
    $stmt->execute();

    if ($stmt->affected_rows) {
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
    $menu_name = $post_vars["menu_name"];
    $status = $post_vars["status"];
    $seq = $post_vars["seq"];

    $sql = "UPDATE menu SET seq=?,status=?,menu_name=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi',$seq,$status,$menu_name,$id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $ret_msg["message"] = "OK";
        $ret_msg["info"] = "sql=" . $sql;
    } else {
        $ret_msg["message"] = "FAIL Update";
    }
    echo json_encode($ret_msg);
} else {
    echo "OK, Connected to Web Service Successful !";
}
$conn->close();
