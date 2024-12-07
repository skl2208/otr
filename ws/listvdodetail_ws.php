<?php
include "../inc/config.php";

$ret_msg = array("message" => "", "info" => "");

if (isset($_POST["id"])) {
    $id = $_POST["id"];
    //========= ไป Query รายละเอียด =========//
    $sql = "SELECT id,catagory,src_clip,vdo_url,vdo_desc,attach_file_url,is_youtube,update_date,create_date,status FROM vdo WHERE id=$id";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        // Blinding
        $ret_msg["message"] = "OK";
        $info = array("id" => "", "vdo_url" => "", "vdo_desc" => "", "update_date" => "");

        $row = $result->fetch_assoc();
        $info["id"] = $row["id"];
        $info["catagory"] = $row["catagory"];
        $info["vdo_url"] = $row["vdo_url"];
        $pos = strpos($info["vdo_url"], "http");
        if ($pos === false) {
            $info["vdo_url"] = $baseHTTP . $baseURL . $info["vdo_url"];
        }
        $info["vdo_desc"] = ($row["vdo_desc"] == null ? "" : $row["vdo_desc"]);
        $info["attach_file_url"] = $row["attach_file_url"];

        if(strpos($info["attach_file_url"],"http")===false) {
            $info["attach_file_url"] = $baseHTTP.$baseURL.$info["attach_file_url"];
        }
        $info["src_clip"]=$row["src_clip"];
        $info["create_date"]=$row["create_date"];
        $info["update_date"]=$row["update_date"];
        $info["is_youtube"] = $row["is_youtube"];
        $info["status"] = $row["status"];
        $ret_msg["info"] = $info;
    } else {
        $ret_msg["message"] = "OK";
        $ret_msg["info"] = "NO DATA";
    }

    echo json_encode($ret_msg);
    $conn->close();
} else {
    $ret_msg["message"] = "FAIL:No id receive";
    echo json_encode($ret_msg);
}
