<?php include "include/header.php" ?>
<section class="rootnavigator mx-lg-5 mx-md-1 mx-0 p-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col"><a href="index.php">หน้าแรก</a>/หน่วยงาน/บุคลากร</div>
        </div>
    </div>
</section>
<section class="officerSection">
    <div class="container-fluid">
        <div class="row mx-lg-5 mx-md-1 mx-sm-0 mb-4 template-2 shadow-sm">
            <div class="col-12 mt-lg-3 mt-md-2">
                <h2 class="topic-header">บุคลากร</h2>
            </div>
            <!-- <div class="col-12 mt-2 mb-2">
                <h4 class="topic-header">ผู้ตรวจการพยาบาล</h4>
            </div>
            <div class="col-12 mb-3 pb-3">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-6 p-2 double-line listitem-header">ยศ-ชื่อ</div>
                    <div class="col-lg-3 col-md-3 col-6 p-2 double-line listitem-header">นามสกุล</div>
                    <div class="col-lg-6 col-md-6 col-12 p-2 double-line listitem-header">หน่วยงาน</div>
                    <?php
                    include "include/config.php";

                    $sql = "SELECT titlename,name,surname,picture_URL,controlunit from officer where position='ผู้ตรวจการพยาบาล' and status='Y' ORDER BY seq";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // output data of each row
                        while ($row = $result->fetch_assoc()) {
                            if (isset($row["picture_URL"])) {
                                $source_pic = $row["picture_URL"];
                            } else {
                                $source_pic = "images/static_image/a_person.png";
                            }
                            // echo "<div class=\"col-lg-2 col-md-2 col-12 p-2 listitem text-center\"><a href=\"javascript:showFullPicture('" . $source_pic . "');\"><img src=\"" . $source_pic . "\"></a></div>";
                            echo "<div class=\"col-lg-3 col-md-3 col-6 p-2 listitem3\">" . $row["titlename"] . " " . $row["name"] . "</div>";
                            echo "<div class=\"col-lg-3 col-md-3 col-6 p-2 listitem3\">" . $row["surname"] . "</div>";
                            echo "<div class=\"col-lg-6 col-md-6 col-12 p-2 listitem3 text-lg-start text-md-start text-center\">" . $row["controlunit"] . "</div>";
                        }
                    } else {
                        echo "<div class=\"col-12 text-center\">ไม่มีข้อมูล</div>";
                    }
                    ?>
                </div>
            </div> -->
            <!-- <div class="col-12 mt-2 mb-2">
                <h4 class="topic-header">พยาบาลหัวหน้าหน่วยงาน</h4>
            </div>
            <div class="col-12 mb-3 pb-3">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-6 p-2 double-line listitem-header">ยศ-ชื่อ</div>
                    <div class="col-lg-3 col-md-3 col-6 p-2 double-line listitem-header">นามสกุล</div>
                    <div class="col-lg-6 col-md-6 col-12 p-2 double-line listitem-header">หน่วยงาน</div>
                    <?php
                    include "include/config.php";

                    $sql = "SELECT titlename,name,surname,picture_URL,controlunit from officer where position='พยาบาลหัวหน้าหน่วยงาน' and status='Y' order by seq";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // output data of each row
                        while ($row = $result->fetch_assoc()) {
                            if (isset($row["picture_URL"])) {
                                $source_pic = $row["picture_URL"];
                            } else {
                                $source_pic = "images/static_image/a_person.png";
                            }
                            // echo "<div class=\"col-lg-2 col-md-2 col-12 p-2 listitem text-center\"><a href=\"javascript:showFullPicture('" . $source_pic . "');\"><img src=\"" . $source_pic . "\"></a></div>";
                            echo "<div class=\"col-lg-3 col-md-3 col-6 p-2 listitem3\">" . $row["titlename"] . " " . $row["name"] . "</div>";
                            echo "<div class=\"col-lg-3 col-md-3 col-6 p-2 listitem3\">" . $row["surname"] . "</div>";
                            echo "<div class=\"col-lg-6 col-md-6 col-12 p-2 listitem3 text-lg-start text-md-start text-center\">" . $row["controlunit"] . "</div>";
                        }
                    } else {
                        echo "<div class=\"col-12 text-center\">ไม่มีข้อมูล</div>";
                    }
                    ?>
                </div>
            </div> -->
            <div class="col-12 mt-2 mb-2">
                <h4 class="topic-header">บุคลากรประจำกองอายุรกรรม</h4>
            </div>
            <div class="col-12 mb-3 pb-3">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-12 p-2 double-line listitem-header"></div>
                    <div class="col-lg-2 col-md-2 col-6 p-2 double-line listitem-header">ยศ-ชื่อ</div>
                    <div class="col-lg-2 col-md-2 col-6 p-2 double-line listitem-header">นามสกุล</div>
                    <div class="col-lg-2 col-md-2 col-6 p-2 double-line listitem-header">หน่วยงาน</div>
                    <div class="col-lg-4 col-md-4 col-6 p-2 double-line listitem-header">ตำแหน่ง</div>
                    <?php
                    include "include/config.php";

                    $sql = "SELECT titlename,name,surname,picture_URL,controlunit,position from officer where controlunit = 'บุคลากรประจำกองอายุรกรรม' and status='Y' order by seq";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // output data of each row
                        while ($row = $result->fetch_assoc()) {

                            if ($row["picture_URL"] != null) {
                                $source_pic = $row["picture_URL"];
                            } else {
                                $source_pic = "images/static_image/a_person.png";
                            }
                            echo "<div class=\"col-lg-2 col-md-2 col-12 p-2 listitem5 text-center\"><a href=\"javascript:showFullPicture('" . $source_pic . "');\"><img src=\"" . $source_pic . "\"></a></div>";
                            echo "<div class=\"col-lg-2 col-md-2 col-6 p-2 listitem5\">" . $row["titlename"] . " " . $row["name"] . "</div>";
                            echo "<div class=\"col-lg-2 col-md-2 col-6 p-2 listitem5\">" . $row["surname"] . "</div>";
                            echo "<div class=\"col-lg-2 col-md-2 col-6 p-2 listitem5 text-lg-start text-md-start text-center\">" . ($row["controlunit"]) . "</div>";
                            echo "<div class=\"col-lg-4 col-md-4 col-6 p-2 listitem5 text-lg-start text-md-start text-center\">" . ($row["position"]==0 ? "" : $row["position"]) . "</div>";

                        }
                    } else {
                        echo "<div class=\"col-12 text-center\">ไม่มีข้อมูล</div>";
                    }

                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="showFullPicture" style="display:none">
        <div>
            <img src title="" id="pictureFull">
            <div>
                <a href="javascript:showFullPicture('close');">X</a>
            </div>
        </div>
    </div>
</section>
<?php
include "include/footer.php"
?>