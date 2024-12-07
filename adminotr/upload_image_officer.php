<?php

include "inc/config.php";

$responseWS = array("status" => "OK", "url" => "");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fileName = $_FILES['inputFile']['name'];
    $filePath = $image_upload_path["officer"] . $fileName;

    $file_type = $_FILES['inputFile']['type'];
    $allowed = array("image/jpeg", "image/gif", "image/png");

    if(!in_array($file_type, $allowed)) {
        $responseWS["status"] = "FAIL";
        $responseWS["url"] = "";
        $responseWS["type"] = $default_type;
        $responseWS["detail"] = "อนุญาตให้ Upload เฉพาะไฟล์ภาพ jpge,gif และ png เท่านั้น";

    } else {
        if (move_uploaded_file($_FILES["inputFile"]["tmp_name"], $filePath)) {
            $responseWS["status"] = "OK";
            $responseWS["url"] = $image_url_path["officer"].$fileName;
        } else {
            $responseWS["status"] = "FAIL";
            $responseWS["url"] = "-----";
        }
    }

    echo json_encode($responseWS);
}
