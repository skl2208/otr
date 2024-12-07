<?php

$servername = "192.168.100.233";
$username = "med_user";
$password = "med12345med";
$dbname = "med";
//$servername = "163.44.198.61";
//$username = "cp187059_mbh";
//$password = "7QhZD,]i[^!o";
//$dbname = "cp187059_med";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

mysqli_set_charset($conn, "utf8");

// Check connection

if ($conn->connect_error) {

  die("ไม่สามารถเชื่อมต่อฐานข้อมูล : " . $conn->connect_error);

}
$baseHTTP = "http://";

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {

  $baseHTTP = "https://";

}
$baseURL = $_SERVER['SERVER_NAME']."/";

//============= z.com (Developing) Configuration ===========
$image_upload_path = array(
  "default" => "/public_html/mbh/images/upload/",
  "news" => "/public_html/mbh/images/upload/news/",
  "officer" => "/public_html/mbh/images/upload/officer/",
  "activity" => "/public_html/mbh/images/upload/activity/",
  "carousel" => "/public_html/mbh/images/upload/carousel/"
);
//============= Production Configuration ===========
// $image_upload_path = array(
//   "default" => "c:/xampp/htdocs/med/images/upload/",
//   "news" => "c:/xampp/htdocs/med/images/upload/news/",
//   "officer" => "c:/xampp/htdocs/med/images/upload/officer/",
//   "activity" => "c:/xampp/htdocs/med/images/upload/activity/",
//   "carousel" => "c:/xampp/htdocs/med/images/upload/carousel/"
// );

$image_url_path = array(

  "default" => "images/upload/",
  "news" => "images/upload/news/", 
  "officer" => "images/upload/officer/", 
  "activity" => "images/upload/activity/",
  "carourel" => "images/upload/carousel/"
);
?>
