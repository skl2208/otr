<?php

include "../inc/config.php";

$responseWS = array("status" => "OK", "url" => "", "type", "");
$txt = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fileName = $_FILES['inputFileAttach']['name'];
    $filePath = $image_upload_path["attach"] . $fileName;

    $responseWS["status"] = "FAIL";
    $responseWS["url"] = $baseHTTP . $baseURL . $image_url_path["attach"] . $fileName;
    $responseWS["type"] = "attach";
    $responseWS["detail"] = "moving from " . $_FILES["inputFileAttach"]["tmp_name"] . " to " . $filePath;

    if (move_uploaded_file($_FILES["inputFileAttach"]["tmp_name"], $filePath)) {
        $responseWS["status"] = "OK";
        $responseWS["url"] = $baseHTTP . $baseURL . $image_url_path["attach"] . $fileName;
        $responseWS["type"] = "attach";
        $responseWS["detail"] = "";
    } else {
        $someError = $_FILES['inputFileAttach']['error'];
        $responseWS["detail"] .= ":" . $someError;
    }
    echo json_encode($responseWS);
}
