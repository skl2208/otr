<?php
include "../inc/config.php";

$num_row_per_page = 20; // default จำนวนข้อมูลต่อ 1 หน้า
$pageno = 1;
$parent_id = 0;

$ret_msg = array("message" => "", "total_rec" => "", "total_page" => "", "current_page" => "", "info" => array());

if (isset($_POST["pageno"]) && $_POST["pageno"] > 0) {
    $pageno = $_POST["pageno"];
}
if(isset($_POST["parent_id"]) && $_POST["parent_id"]!=0) {
    $parent_id = $_POST["parent_id"];
}

//=====หาจำนวน Record ตามเงื่อนไข =======//
$sql="SELECT COUNT(id) AS total_rec FROM menu WHERE parent_id = $parent_id AND is_item='Y' order by parent_id,level,seq";

$result_total = $conn->query($sql);
if ($result_total && $result_total->num_rows > 0) {
    $row0 = $result_total->fetch_assoc();
    $ret_msg["message"] = "OK";
    $ret_msg["total_rec"] = $row0["total_rec"];
    $ret_msg["total_page"] = ceil($row0["total_rec"] / $num_row_per_page);
    $ret_msg["current_page"] = $pageno;

    //========= ไป Query รายละเอียด =========//
    $sql = "SELECT id,parent_id,menu_name,level,seq,link_url,IF(is_item!='Y','MENU','LINK URL') AS menutype,status  FROM menu WHERE parent_id = $parent_id AND is_item='Y' order by parent_id,level,seq LIMIT " . ($num_row_per_page) * ($pageno - 1) . "," . $num_row_per_page;

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Blinding
        $info = array("id" => "", "menu_name" => "",  "seq" => "");
        while ($row = $result->fetch_assoc()) {
            $info["id"] = $row["id"];
            $info["menu_name"] = $row["menu_name"];
            $info["seq"] = $row["seq"];
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
