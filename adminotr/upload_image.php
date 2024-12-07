<?php

include "inc/config.php";

$responseWS = array("status" => "OK", "url" => "", "type","");

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $fileName = $_FILES['inputFile']['name'];
        $default_type = "default";
        $typeupload = $_POST["typeupload"];
    
        if(isset($_POST["typeupload"])) {
            $filePath = $image_upload_path[$typeupload] . $fileName;
            $default_type = $typeupload;
        } else {
            $filePath = $image_upload_path["default"] . $fileName;
        }
    
        $file_type = $_FILES['inputFile']['type'];
        $allowed = array("image/jpeg", "image/gif", "image/png");

        if(!in_array($file_type, $allowed)) {
            $responseWS["status"] = "FAIL";
            $responseWS["url"] = "";
            $responseWS["type"] = $default_type;
            $responseWS["detail"] = "อนุญาตให้ Upload เฉพาะไฟล์ภาพ jpge,gif และ png เท่านั้น";

        } else {

            if (move_uploaded_file($_FILES['inputFile']['tmp_name'], $filePath)) {
                $responseWS["status"] = "OK";
                $responseWS["url"] = $baseHTTP.$baseURL.$image_url_path[$_POST["typeupload"]].$fileName;
                $responseWS["type"] = $default_type;
                $responseWS["detail"] = "moving from ".$_FILES["inputFile"]["tmp_name"]." to ".$filePath;
            } else {
                $responseWS["status"] = "FAIL";
                $responseWS["url"] = $baseHTTP.$baseURL.$image_url_path[$_POST["typeupload"]].$fileName;
                $responseWS["type"] = $default_type;
                $responseWS["detail"] = "tmp_name".$_FILES['inputFile']['tmp_name']." filePath: ".$filePath." fileName:".$fileName." error reason:".$_FILES["inputFile"]["error"];
            }
        }

        echo json_encode($responseWS);
    }
} catch(Exception $ex) {
    throw $ex;
}
