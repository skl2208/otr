<?php
include "include/config.php";
include "include/header.php";
include "include/checkmember.php";
?>

<section class="rootnavigator mx-lg-5 mx-md-1 mx-0 p-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col"><a href="index.php">หน้าแรก</a>/e-learning</div>
        </div>
    </div>
</section>
<section class="linkSection">
    <div class="container-fluid">
        <div class="row mx-lg-5 mx-md-1 mx-sm-0">
            <div class="col-lg-4 col-md-4 col-sm-12 mt-3 mb-2">
                <h3>ระบบ e-Learning</h3>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 mt-2 mb-2 text-lg-end text-sm-end text-sm-center text-center">
                <select name="select_group" id="select_group">
                    <option value="">เลือกทุกกลุ่ม</option>
                    <?php
                    $sql = "SELECT id,catagory FROM vdo_group ORDER BY catagory";
                    $result1 = $conn->query($sql);
                    if ($result1 && $result1->num_rows > 0) {
                        while ($row1 = $result1->fetch_assoc()) {
                            echo "<option value=" . $row1["id"] . ">" . $row1["catagory"] . "</option>";
                        }
                    }
                    ?>

                </select>
                <input type="text" name="searchTxt" id="searchTxt">
                <button class="btn-submit-no-w100" onclick="javascript:blind_vdo_data(1,$('#select_group').val(),$('#searchTxt').val());">ค้นหา</button>
            </div>
        </div>
        <div class="row mx-lg-5 mx-md-1 mx-sm-0 list-vdo shadow-sm">

            <!-- <div class="col-lg-6 col-md-6 col-sm-12 mt-2 mb-2">
                <video class="video-js myiframe" id="" controls controlsList="nodownload">
                    <source src="vdo/upload/Optimizing the Current Management of Gout by Dr.Indhira Urailert.mp4" type="video/mp4" autoplay muted>
                    <source src="vdo/upload/Optimizing the Current Management of Gout by Dr.Indhira Urailert.webm" type="video/webm" autoplay muted>
                    Your browser does not support the video tag.
                </video>
                <p>Gout Management 2021 อ.อินทิรา</p>
                <p><a href="upload/Lect Gout 2021.pdf" download>เอกสารประกอบ</a></p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 mt-2 mb-2">
                <div><iframe class="myiframe" src="https://www.youtube.com/embed/EAhTcXz_UQ8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
                <p>คำอธิบาย VDO เบื้องต้น คำอธิบาย VDO เบื้องต้น คำอธิบาย VDO เบื้องต้น คำอธิบาย VDO เบื้องต้น</p>
            </div> -->
        </div>
    </div>
</section>
<section class="paging">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center" id="displayPaging">
        </ul>
    </nav>
</section>
<div class="spinner-border text-info" id="spinner"></div>
<div id="showAlert">
    <div>
        <div>
            <img src="images/icon_ok.png">
        </div>
        <div></div>
        <div>
            <button onclick="javascript:showAlert.hide();" class="btnOK">ปิด</button>
        </div>
    </div>
</div>
<div id="showToast"></div>
<script type="text/javascript" src="https://vjs.zencdn.net/7.15.4/video.min.js"></script>
<script>
    $(function() {
        blind_vdo_data(1, '', '');
    });
</script>
<?php include "include/footer.php" ?>