<?php include "include/header.php" ?>
<section class="rootnavigator mx-lg-5 mx-md-1 mx-0 p-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col"><a href="index.php">หน้าแรก</a>/หน่วยงาน/รายนามผู้อำนวยการ</div>
        </div>
    </div>
</section>
<section class="detailSection">
    <div class="container-fluid">
        <div class="row mx-lg-5 mx-md-1 mx-sm-0 template-2 shadow-sm">
            <div class="col-12 mt-lg-3 mt-md-2">
                <h2 class="topic-header">กองอายุรกรรม รพ.ภูมิพลอดุลยเดช พอ.</h2>
            </div>
            <div class="col-12 mt-2 mb-2">
                <h4 class="topic-header">รายนามผู้อำนวยการ</h4>
            </div>
            <div class="col-12 mb-3 pb-3">
                <div class="row" id="list_Director">
                    <div class="col-lg-1 col-md-1 col-2 p-2 text-center double-line listitem-header">ลำดับ</div>
                    <div class="col-lg-3 col-md-3 col-3 p-2 double-line listitem-header">ยศ-ชื่อ</div>
                    <div class="col-lg-3 col-md-3 col-3 p-2 double-line listitem-header">นามสกุล</div>
                    <div class="col-lg-5 col-md-5 col-4 p-2 double-line listitem-header">ช่วงเวลาดำรงตำแหน่ง</div>

                    <?php 
                        include "include/config.php";

                        $sql = "SELECT seq,titlename,name,surname,duration from director_name order by seq desc";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // output data of each row
                            while($row = $result->fetch_assoc()) {
                                echo "<div class=\"col-lg-1 col-md-1 col-2 p-2 text-center listitem\">".$row["seq"]."</div>";
                                echo "<div class=\"col-lg-3 col-md-3 col-3 p-2 listitem\">".$row["titlename"]." ".$row["name"]."</div>";
                                echo "<div class=\"col-lg-3 col-md-3 col-3 p-2 listitem\">".$row["surname"]."</div>";
                                echo "<div class=\"col-lg-5 col-md-5 col-4 p-2 listitem\">".$row["duration"]."</div>";
                            }
                        } else {
                            echo "<div class=\"col-12 text-center\">ไม่มีข้อมูล</div>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include "include/footer.php" ?>