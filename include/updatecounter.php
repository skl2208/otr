<?php

include "config.php";

$today = date("Y-m-d");
$today_visitor = 0;
$acc_visitor = 0;

$sql = "SELECT visitor,lastvisitor FROM visitor WHERE indate='$today'";
$result0 = $conn->query($sql);
if($result0 && $result0->num_rows > 0) {
    //======== กรณีวันนั้นมีการสร้าง record ไว้แล้ว ============
    $row0 = $result0->fetch_assoc();
    $today_visitor = $row0["visitor"] + 1;
    $acc_visitor = $row0["lastvisitor"] + 1;

} else {
    //========= กรณีเป็นวันใหม่ ไม่มี record มาก่อน ก็ให้ดึงจากวันที่ล่าสุดที่มีการเข้าเวป ========
    $sql = "SELECT visitor,lastvisitor FROM visitor ORDER BY indate DESC LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $today_visitor = 1;
        $acc_visitor = $row["lastvisitor"] + 1;
    } else {
        //======= เกิดขึ้นเฉพาะเมื่อสร้างเวปใหม่ ยังไม่มีคนเข้าชม ========
        $today_visitor = 1;
        $acc_visitor = 1;
    }
}

$sql = "INSERT INTO visitor (indate,visitor,lastvisitor) VALUES ";
$sql = $sql . "('$today',$today_visitor,$acc_visitor) ON DUPLICATE KEY UPDATE visitor=$today_visitor,lastvisitor=$acc_visitor";
$result0 = $conn->query($sql);

