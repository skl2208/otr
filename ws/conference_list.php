<?php
include "../inc/config.php";

$num_row_per_page = 10; // default จำนวนข้อมูลต่อ 1 หน้า
$pageno = 1;
$condition = "1";
$condition1 = "1";
$condition2 = "1";
$searchTxt = "";
$type_confer = "";
$ret_msg = array("message" => "", "total_rec" => "", "total_page" => "", "current_page" => "", "info" => array());

if (isset($_POST["pageno"]) && $_POST["pageno"] > 0) {
    $pageno = $_POST["pageno"];
}

if (isset($_POST["searchTxt"]) && $_POST["searchTxt"] != "") {
    $searchTxt = trim($_POST["searchTxt"]);
    $condition1 = "(detail LIKE '%$searchTxt%' OR topic_confer LIKE '%$searchTxt%' )";
}

if (isset($_POST["type_confer"]) && $_POST["type_confer"] != "") {
    $type_confer = trim($_POST["type_confer"]);
    $condition2 = "(type_confer = '$type_confer')";
}

$condition = $condition1 . " AND " . $condition2;

//=====หาจำนวน Record ตามเงื่อนไข =======//
$sql = "SELECT COUNT(id) AS totalrec FROM conference WHERE 1 AND $condition";

$result_total = $conn->query($sql);
if ($result_total && $result_total->num_rows > 0) {
    $row0 = $result_total->fetch_assoc();
    $ret_msg["message"] = "OK";
    $ret_msg["total_rec"] = $row0["totalrec"];
    $ret_msg["total_page"] = ceil($row0["totalrec"] / $num_row_per_page);
    $ret_msg["current_page"] = $pageno;

    //========= ไป Query รายละเอียด =========//

    $sql = "SELECT id,topic_confer,picture_URL,type_confer,start_confer_date,end_confer_date,pin FROM conference WHERE 1 AND $condition ";
    $sql .= "ORDER BY pin DESC,start_confer_date DESC,end_confer_date DESC,create_date DESC LIMIT " . ($num_row_per_page) * ($pageno - 1) . "," . $num_row_per_page;

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Blinding
        $info = array("id" => "", "topic_confer" => "",  "picture_URL" => "",  "start_confer_date" => "", "end_confer_date" => "","type_confer" => "");
        while ($row = $result->fetch_assoc()) {
            $info["id"] = $row["id"];
            $info["topic_confer"] = $row["topic_confer"];
            $info["picture_URL"] = $row["picture_URL"];
            $info["start_confer_date"] = $row["start_confer_date"];
            $info["end_confer_date"] = $row["end_confer_date"];
            $info["type_confer"] = $row["type_confer"];

            array_push($ret_msg["info"], $info);
        }
    }
} else {
    // No Data
    $ret_msg["message"] = "FAIL";
}
echo json_encode($ret_msg);
$conn->close();
