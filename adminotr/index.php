<?php
session_start();

include "../include/checkadmin.php";

?>
<!DOCTYPE html>
<html>
<meta charset="utf-8">
<meta lang="TH">
<title>ระบบบริหารงานเวป ก.อ.ย.</title>

<head>
    <link rel="icon" type="image/png" href="../images/logo_MED.png" />
    <link rel="stylesheet" type="text/css" href="../bootstrap-5.1.0-dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/showalert.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <script type="text/javascript" src="../jquery-3.6.0/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="../bootstrap-5.1.0-dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/showalert.js"></script>
    <script type="text/javascript" src="js/index.js"></script>
    <style>
        .column-left::-webkit-scrollbar {
            width: 1em;
        }

        /* .column-left::-webkit-scrollbar-track {
            border-radius: 10px;
            box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        } */

        .column-left::-webkit-scrollbar-thumb {
            border-radius: 5px;
            background-color: #cccccc;
        }

        ul {
            margin-left: -10px;
        }
    </style>
</head>

<body>
    <div class="header position-relative">
        <div>
            ระบบบริหารงานเวปไซต์ กองอายุรกรรม โรงพยาบาลภูมิพลอดุลยเดช พ.อ.
        </div>
    </div>
    <div class="workspace">
        <div class="column-left">
            <div class="text-center mb-3"><a href="../index.php"><img src="../images/logo_MED_white.png" style="height:25px"></a></div>
            <h5>ระบบงาน</h5>
            <ul>
                <li>
                    <a href="list_user.php" target="content_iframe">สมาชิกเวปไซต์</a>
                </li>
                <li>
                    <a href="list_department.php" target="content_iframe">หน่วยงาน,สาขาวิชา</a>
                </li>
                <li>
                    <a href="manage_pic_catagory.php" target="content_iframe">หมวดภาพกิจกรรม</a>
                </li>
                <li>
                    <a href="manage_pic_activity.php" target="content_iframe">ภาพกิจกรรม/ข่าว</a>
                </li>
            </ul>
            <h5>บริหารจัดการบุคลากร</h5>
            <ul>
                <li>
                    <a href="list_officer.php" target="content_iframe">เจ้าหน้าที่ในสาขาวิชา</a>
                </li>
                <li>
                    <a href="list_resident_group.php" target="content_iframe">แพทย์ประจำบ้าน</a>
                </li>
            </ul>
            <h5>หน้าหลักเวปไซต์</h5>
            <ul>
                <li>
                    <a href="list_news.php?type=Highlight" target="content_iframe">Highlight</a>
                </li>
                <li>
                    <a href="list_news.php?type=Activity" target="content_iframe">Activities</a>
                </li>
                <li>
                    <a href="list_news.php?type=Upcoming Events" target="content_iframe">Upcoming Events</a>
                </li>
                <!-- <li>
                    <a href="list_news.php?type=ข่าวกิจกรรม" target="content_iframe">ข่าวกิจกรรม</a>
                </li> -->
                <li>
                    <a href="list_news.php?type=Annoucement" target="content_iframe">Annoucement</a>
                </li>
                <li>
                    <a href="list_banner.php" target="content_iframe">ป้ายโฆษณา (Banner)</a>
                </li>
                <li>
                    <a href="list_download.php" target="content_iframe">Download เอกสาร</a>
                </li>
                <li>
                    <a href="list_link.php" target="content_iframe">LINK เวปไซต์อื่น</a>
                </li>
                <li>
                    <a href="list_research.php" target="content_iframe">งานวิจัย</a>
                </li>
                <li>
                    <a href="list_conference.php" target="content_iframe">งานประชุม</a>
                </li>
            </ul>
            <h5>จัดการเมนูย่อย</h5>
            <ul>
                <li>
                    <a href="list_department_menu.php" target="content_iframe">เมนู สาขาวิชา</a>
                </li>
                <li>
                    <a href="list_education_menu.php" target="content_iframe">เมนู การศึกษา</a>
                </li>
                <li>
                    <a href="list_excellence_center_menu.php" target="content_iframe">เมนู Excellence Center</a>
                </li>
            </ul>
            <h5>หน้าเวปย่อย</h5>
            <ul>
                <li>
                    <a href="list_webpage.php" target="content_iframe">จัดการหน้าเวปย่อย</a>
                </li>
                <li>
                    <a href="linkwebpage.php" target="content_iframe">เชื่อมโยงเวปย่อยกับเมนู</a>
                </li>
            </ul>
            <h5>ระบบงานเนื้อหาสมาชิก</h5>
            <ul>
                <li>
                    <a href="list_vdogroup.php" target="content_iframe">กลุ่ม e-Learning</a>
                </li>
                <li>
                    <a href="list_vdo.php" target="content_iframe">e-Learning</a>
                </li>
            </ul>
            <a href="javascript:void(0);" target="content_iframe">
                <h5>DB Maintenance</h5>
            </a>
            <a href="../ws/logout.php">
                <h5>ออกจากระบบ</h5>
            </a>
        </div>
        <div class="content">
            <iframe src="" id="content_iframe" name="content_iframe">
            </iframe>
        </div>
    </div>

</body>

</html>