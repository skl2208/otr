<?php
include "include/header.php";
?>
<section class="rootnavigator mx-lg-5 mx-md-1 mx-0 p-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col"><a href="index.php">หน้าแรก</a>/สาขาวิชาตจวิทยา</div>
        </div>
    </div>
</section>
<section class="listOrganization">
    <div class="container-fluid">
        <div class="row mx-lg-5 mx-md-1 mx-sm-0">
            <?php
            include "include/config.php";

            $sql = "SELECT nameth,nameen,description from department where nameth='ตจวิทยา'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<div class=\"col-lg-12 col-md-12 col-sm-12 mt-3\"><h3>" . $row["nameth"] . " (" . $row["nameen"] . ")</h3></div>";
                    echo "<div class=\"mt-3 mb-1\">" . $row["description"] . "</div>";
                }
            } else {
                echo "ไม่มีหัวข้อ";
            }
            $sql = "select seq,titlename,name,surname,picture_URL,graduation,download_URL from officer where controlunit='ตจวิทยา' and position='อาจารย์แพทย์' and status='Y' order by seq,name,surname";
            $result1 = $conn->query($sql);
            if ($result1->num_rows > 0) {

                echo "<article><div class=\"mt-3 mb-1\"><h4>อาจารย์แพทย์</h4></div></article>";
                echo "<div class=\"col-lg-12 col-md-12 col-sm-12 mt-3 mb-3 pt-3 listOrganization-item shadow-sm\"><div>";

                while ($row = $result1->fetch_assoc()) {

                    if (empty($row["picture_URL"])) {
                        $img_URL = "images/static_image/a_person.png";
                    } else {
                        $img_URL = $row["picture_URL"];
                    }

                    if ($row["seq"] == 1) {
                        $comment = "(หัวหน้าหน่วย)";
                    } else {
                        $comment = "";
                    }
                    echo "<div><div class=\"text-center\"><img src=\"" . $img_URL . "\" title=\"" . $row["titlename"] . $row["name"] . " " . $row["surname"] . " " . $comment . "\"></div>";
                    echo "<div style=\"font-weight:bold;margin:5px 0;text-align:center\">" . $row["titlename"] . $row["name"] . " " . $row["surname"] . " " . $comment . "</div>";
                    echo "<div style=\"text-align:left;width:100%\"><div class=\"listOrganization-item-graduation\">".$row["graduation"]."</div></div>";

                    if ($row["download_URL"] != "") {
                        echo "<div class=\"text-center\"><a href=\"" . $row["download_URL"] . "\" target=\"_blank\" download>ประวัติการทำงาน</a></div>";
                    }
                    echo "</div>";

                }
                echo "</div></div>";
            }
            $sql = "select titlename,name,surname,picture_URL,graduation,download_URL from officer where controlunit='ตจวิทยา' and position='อาจารย์พิเศษ' and status='Y' order by seq,name,surname";
            $result1 = $conn->query($sql);
            if ($result1->num_rows > 0) {

                echo "<article><div class=\"mt-3 mb-1\"><h4>อาจารย์พิเศษ</h4></div></article>";
                echo "<div class=\"col-lg-12 col-md-12 col-sm-12 mt-3 mb-3 pt-3 listOrganization-item shadow-sm\"><div>";

                while ($row = $result1->fetch_assoc()) {

                    if (empty($row["picture_URL"])) {
                        $img_URL = "images/static_image/a_person.png";
                    } else {
                        $img_URL = $row["picture_URL"];
                    }
                    echo "<div><div class=\"text-center\"><img src=\"" . $img_URL . "\" title=\"" . $row["titlename"] . $row["name"] . " " . $row["surname"] . "\"></div>";
                    echo "<div style=\"font-weight:bold;margin:5px 0;text-align:center\">" . $row["titlename"] . $row["name"] . " " . $row["surname"] . "</div>";
                    echo "<div style=\"text-align:left;width:100%\"><div class=\"listOrganization-item-graduation\">" . $row["graduation"] . "</div></div>";

                    if ($row["download_URL"] != "") {
                        echo "<div class=\"text-center\"><a href=\"" . $row["download_URL"] . "\" target=\"_blank\" download>ประวัติการทำงาน</a></div>";
                    }
                    echo "</div>";
                }
                echo "</div></div>";
            }
            ?>
        </div>
    </div>
</section>
<section class="paging" style="display: none;">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center" id="displayPaging">

            <li class="page-item disabled"><a class="page-link" href="#" title="หน้าแรกสุด"><span aria-hidden="true">&laquo;</span></a></li>
            <li class="page-item"><a class="page-link" href="#" title="ก่อนหน้า"><span aria-hidden="true">&lt;</span></a></li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#" title="หน้าถัดไป"><span aria-hidden="true">&gt;</span></a></li>
            <li class="page-item"><a class="page-link" href="#" title="หน้าสุดท้าย"><span aria-hidden="true">&raquo;</span></a></li>

        </ul>
    </nav>
</section>
<?php
include "include/footer.php";
?>