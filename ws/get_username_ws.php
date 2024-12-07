<?php

include "../inc/config.php";

// "message" : "" ( OK , NO Data , FAIL )
$list_result = array();
$ret_msg = array("message" => "", "list" => "");

$sql = "SELECT username FROM user ORDER BY username";
$result = $conn->query($sql);

if ($result) {
    $ret_msg["message"] = "OK";

    if ($result->num_rows > 0) {
        $ret_msg["list"] = $search_catagory;

        while ($row = $result->fetch_assoc()) {
            array_push($list_result, $row["username"]);
        }
    }

    $ret_msg["list"] = $list_result;
} else {
    $ret_msg["message"] = "FAIL : Cannot connect database";
}

echo json_encode($ret_msg);
