<?php
include "../inc/config.php";

$num_row_per_page = 20; // default จำนวนข้อมูลต่อ 1 หน้า
$pageno = 1;
$condition = "1";
$condition1 = "1";
$condition2 = "1";

$ret_msg = array("message" => "", "total_rec" => "", "total_page" => "", "current_page" => "", "info" => array(), "sql" => "");

if (isset($_GET["pageno"]) && $_GET["pageno"] > 0) {
    $pageno = $_GET["pageno"];
}

if (isset($_GET["searchTxt"])) {
    $search_text = trim($_GET["searchTxt"]);
    if (!empty($search_text)) {
        $condition1 = " (nameen LIKE '%$search_text%' OR nameth LIKE '%$search_text%')";
    } else {
        $condition1 = "1";
    }
}
if (isset($_GET["status"])) {
    if ($_GET["status"] != "ALL") {
        $condition2 = " (status='" . $_GET["status"] . "')";
    } else {
        $condition2 = "1";
    }
}

$condition = $condition1 . " AND " . $condition2 ;

//=====หาจำนวน Record ตามเงื่อนไข =======//
$sql = "SELECT COUNT(id) AS totalrec FROM department WHERE 1 AND " . $condition;

$result_total = $conn->query($sql);

if ($result_total && $result_total->num_rows > 0) {
    $row0 = $result_total->fetch_assoc();
    $ret_msg["message"] = "OK";
    $ret_msg["total_rec"] = $row0["totalrec"];
    $ret_msg["total_page"] = ceil($row0["totalrec"] / $num_row_per_page);
    $ret_msg["current_page"] = $pageno;

    //========= ไป Query รายละเอียด =========//
    $sql = "SELECT nameth,nameen,id,status FROM department WHERE 1 AND " . $condition . " ORDER BY nameth LIMIT " . ($pageno - 1) * $num_row_per_page . "," . $num_row_per_page;
    $result = $conn->query($sql);
    $ret_msg["sql"] = $sql;

    if ($result && $result->num_rows > 0) {

        $info = array(
            "id" => "",
            "nameth" => "",
            "nameen" => "",
            "status" => ""
        );
        while ($row = $result->fetch_assoc()) {
            $info["id"] = $row["id"];
            $info["nameth"] = $row["nameth"];
            $info["nameen"] = $row["nameen"];
            $info["status"] = $row["status"];

            array_push($ret_msg["info"], $info);
        }
        
    }
} else {
    // No Data
    $ret_msg["message"] = "FAIL";
    $ret_msg["sql"] = $sql;
}
echo json_encode($ret_msg);
$conn->close();
