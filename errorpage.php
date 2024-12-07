<?php include "include/header.php" ?>
<section class="rootnavigator mx-lg-5 mx-md-1 mx-0 p-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col"><a href="index.php">หน้าแรก</a></div>
        </div>
    </div>
</section>
<section class="detailSection">
    <div class="container-fluid">
        <div class="row mx-lg-5 mx-md-1 mx-sm-0 mb-3 pb-3 template-4 shadow-sm" style="min-height:300px">
            <div class="col-lg-2 col-md-2 col-sm-12 mt-3 position-relative">
                <div class="center-content">
                    <img src="images/icon_warn.png" class="error-page-img">
                </div>
            </div>
            <div class="col-lg-10 col-md-10 col-sm-12 mt-3 position-relative">
                <div class="center-content">
                    <p class="emphasize-font">ไม่พบหน้าเวปที่คุณต้องการ<br>
                    หรือถ้าคุณไม่ใช่สมาชิกคุณอาจจะกำลังเข้าหน้าเวปเฉพาะสมาชิกเท่านั้น</p>
                    <?php
                    //echo "name ".$_SESSION["username"]."<br>";
                    //echo "group : ".$_SESSION["usergroup"];
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include "include/footer.php" ?>