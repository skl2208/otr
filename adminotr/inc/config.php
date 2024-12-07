<?php
//===== Production =====
// $servername = "192.168.100.150";
// $username = "med_user";
// $password = "med12345med";
// $dbname = "med_bh";
//===== z.com ====
$servername = "163.44.198.61";
$username = "cp187059_mbh";
$password = "7QhZD,]i[^!o";
$dbname = "cp187059_med";

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
$baseURL = $_SERVER['SERVER_NAME'] . "/";

// for Production at world server
// $image_upload_path = array(
//   "default" => "C:/xampp/htdocs/med/images/upload/",
//   "news"=>"C:/xampp/htdocs/med/images/upload/news/",
//   "officer"=>"C:/xampp/htdocs/med/images/upload/officer/",
//   "activity"=>"C:/xampp/htdocs/med/images/upload/activity/",
//   "carousel"=>"C:/xampp/htdocs/med/images/upload/carousel/",
//     "attach"=>"C:/xampp/htdocs/med/upload/attach/"
// );
// $image_url_path = array(
//   "default" => "images/upload/",
//   "news"=>"images/upload/news/",
//   "officer"=>"images/upload/officer/",
//   "activity"=>"images/upload/activity/",
//   "carousel" => "images/upload/carousel/",
//   "attach"=>"upload/attach/"
// );

// for developing at z.com
$image_upload_path = array(
  "default" => "../images/upload/",
  "news"=>"../images/upload/news/",
  "officer"=>"../images/upload/officer/",
  "activity"=>"../images/upload/activity/",
  "carousel"=>"../images/upload/carousel/",
  "attach"=>"../images/upload/attach/",
  "conference"=>"C:/xampp/htdocs/med/images/upload/conference/"
);
$image_url_path = array(
  "default" => "images/upload/",
  "news"=>"images/upload/news/",
  "officer"=>"images/upload/officer/",
  "activity"=>"images/upload/activity/",
  "carousel" => "images/upload/carousel/",
  "attach"=>"upload/attach/",
  "conference"=>"images/upload/conference/"
);
