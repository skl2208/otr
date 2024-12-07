<?php
include "include/header.php";
include "include/config.php";
?>
<section class="rootnavigator mx-lg-5 mx-md-1 mx-0 p-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col"><a href="index.php">หน้าแรก</a>/<a href="medical_educate2.php">การศึกษาหลังปริญญา</a>/รายชื่อแพทย์ประจำบ้าน</div>
        </div>
    </div>
</section>
<section class="residentSection">
    <div class="container-fluid">
        <div class="row mx-lg-5 mx-md-1 mx-sm-0 template-2 pb-3 shadow-sm">
            <div class="col-12 mt-lg-3 mt-md-2">
                <h2 class="topic-header">รายชื่อแพทย์ประจำบ้าน</h2>
            </div>
            <?php
            $sql = "SELECT * FROM resident_group WHERE NOT(group_name LIKE 'แพทย์ประจำบ้านต่อยอด%') AND status='Y' ORDER BY group_name DESC ";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class=\"col-lg-6 col-md-6 mt-2 mb-2 text-center\">";
                    echo "<h5>".$row["group_name"]."</h5></div>";
                    echo "<div class=\"col-lg-6 col-md-6 mt-2 mb-2 text-center\">";
                    echo "<a href=\"".$row["download_url"]."\" download>ดาวน์โหลดรายชื่อ</a></div><hr>";
                }
            } else {
                echo "<div class=\"col-12 mt-2 mb-2 text-center biggerFont\">ไม่มีข้อมูล</div>";
            }
            ?>
        </div>
    </div>
</section>
<?php include "include/footer.php" ?>