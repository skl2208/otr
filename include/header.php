<?php
if (!isset($_SESSION)) {
    session_start();
}
include "config.php";
include "util.php";

$title_og = "เวปไซต์กองอายุรกรรม โรงพยาบาลภูมิพลอดุลยเดช กรมแพทย์ทหารอากาศ";
$description_og = $title_og;
$image_og = $baseHTTP.$baseURL."images/med_thumbnail.png";
$url_og = $baseHTTP.$baseURL;

if (basename($_SERVER["SCRIPT_FILENAME"]) == "shownewsdetail.php") {

    $id = isset($_GET["id"])?$_GET["id"]:"";

    if(is_numeric($id) && $id>0){
        $sql = "SELECT topic,picture_URL FROM news WHERE id=? and status='Y'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $title_og = str_replace('"', "'",$row["topic"]);
            $description_og = $title_og;
            $image_og = $row["picture_URL"];
            $url_og .= "shownewsdetail.php?id=".$id;
        }
    } 
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="language" content="Thai">
    <meta name="keyword" content="กองอายุรกรรม,โรงพยาบาลภูมิพลอดุลยเดช,กรมแพทย์ทหารอากาศ">
    <meta property="fb:app_id" content="2826546344155379">
    <meta property="og:url" content=<?php echo $url_og?>>
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo $title_og ?>">
    <meta property="og:description" content="<?php echo $description_og?>">
    <meta property="og:image" content="<?php echo $image_og ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $title_og ?></title>
    <link rel="icon" type="image/png" href="https://www.medicinebhumibol.com/images/logo_MED.png" />
    <link rel="stylesheet" type="text/css" href="bootstrap-5.1.0-dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/color.css" title="default">
    <link rel="stylesheet" type="text/css" href="../css/blind.css" title="blind_yellow">
    <link rel="stylesheet" type="text/css" href="../css/normal.css">
    <link rel="stylesheet" href="https://vjs.zencdn.net/7.15.4/video-js.css">

    <script type="text/javascript" src="../jquery-3.6.3/jquery-3.6.3.min.js"></script>
    <script type="text/javascript" src="../bootstrap-5.1.0-dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/util.js"></script>
    <script type="text/javascript" src="../js/index.js"></script>
</head>
<body>
    <section class="head-menu">
        <div>
            <div class="container-fluid content_width">
                <div class="row px-4">
                    <div class="col-lg-1 col-md-1 col-2 text-start text-lg-center text-md-center p-3 head-image-section1">
                    </div>
                    <div class="col-lg col-md col-sm col-5 unit-name p-3">
                        <div class="unit-name-1 pl-3 ml-3">
                            กองอายุรกรรม
                        </div>
                    </div>
                    <div class="col-lgcol-md col-sm col-5 hospital-name p3 text-end" style="position: relative;">
                        <div class="unit-name-2 pl-3 ml-3 text-end">
                            <span>โรงพยาบาลภูมิพลอดุลยเดช
                                กรมแพทย์ทหารอากาศ</span><br><span>BHUMIBOL ADULYADEJ HOSPITAL</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="top-icon-header">
                <!-- ส่วนบนสุดที่จะใส่ icon login,ตัวเปลี่ยนธีม -->
                <?php
                $html1 = "<a href=\"login.php\"><img src=\"images/icon_login.png\" title=\"เข้าสู่ระบบสำหรับเจ้าหน้าที่\" alt=\"Login สำหรับเจ้าหน้าที่\"> Login</a>";
                if (isset($_SESSION["username"])) {
                    $html1 = "<a href=\"member.php\"><img src=\"images/icon_member.png\" title=\"" . $_SESSION["username"] . "\"></a> <a href=\"ws/logout.php\">Logout</a>";
                }

                echo $html1;
                ?>
                | <a href="javascript:void(0)" id="setNormalTheme"><img src="images/icon_normal_theme.png" title="ธีมสีแบบปกติ" alt="เลือกธีมปกติ"></a> <a href="javascript:void(0)" id="setBlindTheme"><img src="images/icon_blind_theme.png" title="ธีมสีผู้ที่ตาบอดสี" alt="เลือกธีมผู้ที่ตาบอดสี"></a> | <a href="" title="ลดขนาดลง" class="smallerFont" id="setSmallerSize">A</a> <a href="" title="ขนาดปกติ" id="setNormalSize">A</a>
                <a href="" title="ขนาดใหญ่ขึ้น" class="biggerFont" id="setBiggerSize">A</a>
                <!-- <a href="<?php echo (isset($_SESSION["username"]) ? "ws/logout.php" : "login.php") ?>"><?php echo (isset($_SESSION["username"]) ? "User:" . $_SESSION["username"] : "<img src=\"images/icon_login.png\"
                        title=\"Login สำหรับเจ้าหน้าที่\" alt=\"\">") ?> <?php echo (isset($_SESSION["name"]) ? "logout" : "login") ?>
                    </a> | <a href="javascript:void(0)" title="ธีมสีแบบปกติ" id="setNormalTheme"><img
                        src="images/icon_normal_theme.png"></a> <a href="javascript:void(0)" title="ธีมสีผู้ที่ตาบอดสี"
                    id="setBlindTheme"><img src="images/icon_blind_theme.png"></a> | <a href="" title="ลดขนาดลง"
                    class="smallerFont" id="setSmallerSize">A</a> <a href="" title="ขนาดปกติ" id="setNormalSize">A</a>
                <a href="" title="ขนาดใหญ่ขึ้น" class="biggerFont" id="setBiggerSize">A</a> -->
            </div>
        </div>
    </section>
    <section class="navbar-sect">
        <div class="nav-section shadow-sm">
            <nav class="navbar navbar-expand-lg navbar-dark pt-0 pb-0">
                <div class="container-fluid text-center">
                    <!-- <a class="navbar-brand" href="#">Navbar</a> -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="index.php"><img src="images/logo_MED_white.png" style="height:20px;width:auto" title="หน้าแรก" alt="Home"></a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown1" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    หน่วยงาน
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown1">
                                    <li>
                                        <a class="dropdown-item dropdown-header" href="bhumibol_hospital.php">รพ.ภูมิพลอดุลยเดช พอ.</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <h5 class="dropdown-header">กองอายุรกรรม รพ.ภูมิพลอดุลยเดช พอ.</h5>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="medical_history.php">ประวัติ</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="medical_mission_vision.php">พันธกิจ | วิสัยทัศน์
                                            | ภารกิจ</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="medical_list_director.php">รายนามผู้อำนวยการ</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="medical_list_officer.php">บุคลากร</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-bs-toggle="dropdown" aria-expanded="false">สาขาวิชา</a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown2">
                                    <?php
                                    display_items_menu($conn, 2);
                                    ?>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown3" role="button" data-bs-toggle="dropdown" aria-expanded="false">การศึกษา</a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown3">
                                    <?php
                                    display_items_menu($conn, 3);
                                    ?>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown4" role="button" data-bs-toggle="dropdown" aria-expanded="false">วิชาการ</a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown4">
                                    <li>
                                        <a class="dropdown-item dropdown-header" href="research_list.php">งานวิจัย</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item dropdown-header" href="listconference.php">งานประชุม</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <?php
                                        if (!isset($_SESSION["username"])) {
                                            echo "<li><h5 class=\"dropdown-header\">Login เข้าระบบเพื่อใช้งาน</h5></li>";
                                        }
                                    ?>
                                    <li>
                                        <a class="dropdown-item dropdown-header <?php echo (isset($_SESSION["username"]) ? "" : "disabled") ?>" href="listvdo.php">E-learning</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown5" role="button" data-bs-toggle="dropdown" aria-expanded="false">Excellence Center</a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown5" data-menu-item="5">
                                    <?php
                                    display_items_menu($conn, 5);
                                    ?>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown6" role="button" data-bs-toggle="dropdown" aria-expanded="false">คลินิก</a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown6">
                                    <li>
                                        <a class="dropdown-item" href="opd_clinic.php">ในเวลาราชการ</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="http://www.bhumibolhospital.rtaf.mi.th/index.asp?parent=739&pageid=828&directory=3212&contents=871&pagename=content" target="_blank">นอกเวลาราชการ</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="download.php">ดาวน์โหลด</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="linkbanner.php">LINK</a>
                            </li>
                            <li class="nav-item search-menu dropdown ">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown7" role="button" data-bs-toggle="dropdown" aria-expanded="false"><img src="images/icon_search.png" style="height:20px;width:auto" alt="ค้นหา" title="ค้นหา"></a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown7">
                                    <li>
                                        <form class="d-flex" method="GET" action="searchresult.php">
                                            <input class="form-control ms-2 me-2" type="search" placeholder="คำค้นหา" aria-label="Search" style="min-width:200px;width:100%;border-color:#eaa2c5;" name="search" id="search">
                                            <button class="btn btn-outline-success" type="submit">ค้นหา</button>
                                        </form>

                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <!--<a href="#"><img src="images/icon_search.png" style="height:20px;width:auto"></a>
                         <form class="d-flex">
                      <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                      <button class="btn btn-outline-success" type="submit">Search</button>
                    </form> -->
                    </div>
                </div>
            </nav>
        </div>
    </section>