<?php

include "../inc/config.php";

$responseWS = array("status" => "OK", "url" => "", "type","");
$txt= "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fileName = $_FILES['inputFile']['name'];
    $default_type = "default";
    $typeupload = $_POST["typeupload"];
    
    if(isset($_POST["typeupload"])) {
        $txt= "Post of typeupload is OK";
        $filePath = $image_upload_path[$_POST["typeupload"]] . $fileName;
        $default_type = $_POST["typeupload"];
    } else {
        $txt= "Post of typeupload is not OK";
        $filePath = $image_upload_path["default"] . $fileName;
    }
    //echo $_POST["typeupload"];
    $responseWS["status"] = "FAIL";
    $responseWS["url"] = $baseHTTP.$baseURL.$image_url_path[$_POST["typeupload"]].$fileName;
    $responseWS["type"] = $default_type;
    $responseWS["detail"] = $txt;

    if (move_uploaded_file($_FILES["inputFile"]["tmp_name"], $filePath)) {
        $responseWS["status"] = "OK";
        $responseWS["url"] = $baseHTTP.$baseURL.$image_url_path[$_POST["typeupload"]].$fileName;
        $responseWS["type"] = $default_type;
        $responseWS["detail"] = "";
    }
    echo json_encode($responseWS);
}
