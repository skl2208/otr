<?php
include "../inc/config.php";

$id = "";

$result_message = array("result"=>"","info"=>"");

$info = array("id"=>"");

//============= Required ===============
//====== tablename ชื่อตารางที่ต้องการลบข้อมูล
//====== id , key ของ rows ที่จะลบ
if(isset($_POST["tablename"]) && isset($_POST["id"])) {
    $tableName = $_POST["tablename"];
    $id =$_POST["id"];

    $data["id"] = $id;
    $data["tablename"] = $tableName;

    $sql = "DELETE FROM $tableName WHERE id=$id";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si',$tableName,$id);
    $stmt->execute();

    $data["sql"] = $sql;

    if($stmt->affected_rows > 0) {
        $data["detail"] = "ลบสำเร็จ";
        $result_message["result"] = "SUCCESS";
        $result_message["info"] = $data;
    } else {
        $data["detail"] = "ลบไม่สำเร็จ เนื่องจากหา id หรือ table ไม่พบ";
        $result_message["result"] = "FAIL";
        $result_message["info"] = $data;
    }

} else {
    $result_message["result"] = "FAIL";
    $result_message["info"] = "NO table name or id";
}

$conn->close();
echo json_encode($result_message);
