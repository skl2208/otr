<?php
include "../inc/config.php";

$num_row_per_page = 10; // default จำนวนข้อมูลต่อ 1 หน้า
$pageno = 1;
$condition = "1";
$condition1 = "1";
$condition2 = "1";

$searchTxt = "";
$select_status = "A";
$ret_msg = array("message" => "", "total_rec" => "", "total_page" => "", "current_page" => "", "info" => array());

if (isset($_POST["pageno"]) && $_POST["pageno"] > 0) {
    $pageno = $_POST["pageno"];
}

if (isset($_POST["searchTxt"]) && $_POST["searchTxt"] != "") {
    $searchTxt = trim($_POST["searchTxt"]);
    $condition1 = "(webpage_name LIKE '%$searchTxt%' OR content LIKE '%$searchTxt%' )";
}
if (isset($_POST["select_status"]) && $_POST["select_status"] != "" && $_POST["select_status"] != 'A') {
    $select_status = $_POST["select_status"];
    $condition2 = "(status='$select_status')";
}

$condition = $condition1 . " AND " . $condition2 ;

//=====หาจำนวน Record ตามเงื่อนไข =======//
$sql = "SELECT COUNT(id) AS totalrec FROM webpage WHERE 1 AND $condition";

$result_total = $conn->query($sql);
if ($result_total && $result_total->num_rows > 0) {
    $row0 = $result_total->fetch_assoc();
    $ret_msg["message"] = "OK";
    $ret_msg["total_rec"] = $row0["totalrec"];
    $ret_msg["total_page"] = ceil($row0["totalrec"] / $num_row_per_page);
    $ret_msg["current_page"] = $pageno;

    //========= ไป Query รายละเอียด =========//

    $sql = "SELECT id,webpage_name,user_create,create_date,user_update,update_date,status FROM webpage WHERE 1 AND $condition ORDER BY update_date DESC,create_date DESC LIMIT " . ($num_row_per_page) * ($pageno - 1) . "," . $num_row_per_page;
    $result = $conn->query($sql);
    //$ret_msg["query"] = $sql;

    if ($result && $result->num_rows > 0) {
        // Blinding
        while ($row = $result->fetch_assoc()) {
            $info["id"] = $row["id"];
            $info["webpage_name"] = $row["webpage_name"];
            $info["user_update"] = ($row["user_update"] == null ? "" : $row["user_update"]);
            $info["update_date"] = $row["update_date"];
            $info["user_create"] = ($row["user_create"] == null ? "" : $row["user_create"]);
            $info["create_date"] = $row["create_date"];
            $info["status"] = $row["status"];

            array_push($ret_msg["info"], $info);
        }
    }
} else {
    // No Data
    $ret_msg["message"] = "FAIL";
}
echo json_encode($ret_msg);
$conn->close();
