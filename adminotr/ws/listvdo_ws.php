<?php
include "../inc/config.php";

$num_row_per_page = 5; // default จำนวนข้อมูลต่อ 1 หน้า
$pageno = 1;
$condition = "1";
$condition1 = "1";
$condition2 = "1";
$condition3 = "1";
$searchTxt = "";
$select_status = "A";
$catalogy = "";
$ret_msg = array("message" => "", "total_rec" => "", "total_page" => "", "current_page" => "", "info" => array());

if (isset($_POST["pageno"]) && $_POST["pageno"] > 0) {
    $pageno = $_POST["pageno"];
}

if (isset($_POST["searchTxt"]) && $_POST["searchTxt"] != "") {
    $searchTxt = trim($_POST["searchTxt"]);
    $condition1 = "(vdo_desc LIKE '%$searchTxt%' OR catagory LIKE '%$searchTxt%' )";
}
if (isset($_POST["select_status"]) && $_POST["select_status"] != "" && $_POST["select_status"] != 'A') {
    $select_status = $_POST["select_status"];
    $condition2 = "(vdo.status='$select_status')";
}

if (isset($_POST["catagory"]) && $_POST["catagory"] != "") {
    $catagory = $_POST["catagory"];
    $condition3 = "(id_catagory='$catagory')";
}

$condition = $condition1 . " AND " . $condition2 . " AND " . $condition3;

//=====หาจำนวน Record ตามเงื่อนไข =======//
$sql = "SELECT COUNT(vdo.id) AS totalrec FROM vdo LEFT JOIN vdo_group ON vdo.id_catagory=vdo_group.id WHERE 1 AND $condition";

$result_total = $conn->query($sql);
if ($result_total && $result_total->num_rows > 0) {
    $row0 = $result_total->fetch_assoc();
    $ret_msg["message"] = "OK";
    $ret_msg["total_rec"] = $row0["totalrec"];
    $ret_msg["total_page"] = ceil($row0["totalrec"] / $num_row_per_page);
    $ret_msg["current_page"] = $pageno;

    //========= ไป Query รายละเอียด =========//

    $sql = "SELECT 
        vdo.id as id,vdo_group.catagory,id_catagory,vdo_url,vdo_desc,create_date,src_clip,update_date,attach_file_url,is_youtube,pin,vdo.status ";
    $sql .= " FROM vdo left join vdo_group on vdo.id_catagory=vdo_group.id WHERE 1 AND $condition ";
    $sql .= "ORDER BY pin DESC,update_date DESC LIMIT " . ($num_row_per_page) * ($pageno - 1) . "," . $num_row_per_page;

    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        // Blinding
        $info = array("id" => "", "vdo_url" => "", "vdo_group" => "", "vdo_desc" => "", "update_date" => "");
        while ($row = $result->fetch_assoc()) {
            $info["id"] = $row["id"];
            $info["vdo_url"] = $row["vdo_url"];
            $pos = strpos($info["vdo_url"], "http");
            if ($pos === false) {
                $info["vdo_url"] = $baseHTTP . $baseURL . $info["vdo_url"];
            }
            $info["catagory"] = $row["catagory"];
            $info["pin"] = $row["pin"];
            $info["vdo_desc"] = ($row["vdo_desc"] == null ? "" : $row["vdo_desc"]);
            $info["update_date"] = $row["update_date"];
            $info["is_youtube"] = $row["is_youtube"];
            $info["src_clip"] = $row["src_clip"];
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
