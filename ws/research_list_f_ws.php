<?php
include "../include/config.php";

$num_row_per_page = 15; // default จำนวนข้อมูลต่อ 1 หน้า
$pageno = 1;
$condition = "1";
$condition1 = "1";
$condition2 = "1";
$condition3 = "1";
$condition4 = "1";
$searchTxt = "";

$ret_msg = array("message" => "", "total_rec" => "", "total_page" => "", "current_page" => "", "info" => array(), "sql" => "");

if (isset($_GET["pageno"])) {
    $pageno = $_GET["pageno"];
}

if (isset($_GET["searchTxt"])) {
    $search_text = trim($_GET["searchTxt"]);
    if (!empty($search_text)) {
        $condition1 = " (topic LIKE '%$search_text%' OR content LIKE '%$search_text%' OR name LIKE '%$search_text%')";
    } else {
        $condition1 = "1";
    }
}
if (isset($_GET["group_name"])) {
    if ($_GET["group_name"] != "") {
        $condition2 = " (group_name='" . trim($_GET["group_name"]) . "') ";
    } else {
        $condition2 = "1";
    }
}
if (isset($_GET["search_year"])) {
    $search_year = trim($_GET["search_year"]);
    if (!empty($search_year)) {
        $condition3 = " (research_year = " . $search_year . ")";
    } else {
        $condition3 = "1";
    }
}
if (isset($_GET["status"])) {
    if ($_GET["status"] != "ALL") {
        $condition4 = " (status='" . $_GET["status"] . "')";
    } else {
        $condition4 = "1";
    }
}

$condition = $condition1 . " AND " . $condition2 . " AND " . $condition3 . " AND " . $condition4;

//=====หาจำนวน Record ตามเงื่อนไข =======//
$sql = "SELECT COUNT(id) AS totalrec FROM research  WHERE 1 AND " . $condition;

$result_total = $conn->query($sql);

if ($result_total && $result_total->num_rows > 0) {
    $row0 = $result_total->fetch_assoc();
    $ret_msg["message"] = "OK";
    $ret_msg["total_rec"] = $row0["totalrec"];
    $ret_msg["total_page"] = ceil($row0["totalrec"] / $num_row_per_page);
    $ret_msg["current_page"] = $pageno;

    //========= ไป Query รายละเอียด =========//
    $sql = "SELECT * FROM research WHERE 1 AND " . $condition . " ORDER BY research_year DESC,create_date DESC,group_name,topic LIMIT (? - 1) * $num_row_per_page,$num_row_per_page";
    $stmt = $conn->prepare($sql);
    $pageno = 1;
    $stmt->bind_param('i', $pageno);
    $stmt->execute();
    $result = $stmt->get_result();
    $ret_msg["sql"] = $sql;

    if ($result && $result->num_rows > 0) {
        $info = array(
            "id" => "",
            "topic" => "",
            "name" => "",
            "group_name" => "",
            "research_year" => "",
            "advisor" => "",
            "download_url" => "",
            "create_date" => "",
            "status" => ""
        );
        while ($row = $result->fetch_assoc()) {
            $info["id"] = $row["id"];
            $info["topic"] = trim($row["topic"]);
            $info["name"] = trim($row["name"]);
            $info["group_name"] = $row["group_name"];
            $info["research_year"] = $row["research_year"];
            $info["advisor"] = trim($row["advisor"]);
            $info["download_url"] = $row["download_url"];
            $info["create_date"] = $row["create_date"];
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
