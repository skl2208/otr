<?php
include "include/header.php";
include "include/config.php";
?>
<section class="rootnavigator mx-lg-5 mx-md-1 mx-0 p-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col"><a href="index.php">หน้าแรก</a>/งานประชุม</div>
        </div>
    </div>
</section>
<section class="linkSection">
    <div class="container-fluid">
        <div class="row mx-lg-5 mx-md-1 mx-sm-0 align-items-center">
            <div class="col-lg-4 col-md-4 col-sm-12 mt-3 mb-2">
                <h2 class="topic-header">งานประชุม</h2>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 mt-2 mb-2 text-lg-end text-sm-end text-sm-center text-center">
                <select name="select_group" id="select_group">
                    <option value="">ทุกประเภทงานประชุม</option>
                    <option value="ภายในโรงพยาบาล">ภายในโรงพยาบาล</option>
                    <option value="ภายนอกโรงพยาบาล">ภายนอกโรงพยาบาล</option>
                </select>
                <input type="text" name="searchTxt" id="searchTxt">
                <button class="btn-submit-no-w100" onclick="javascript:model_View_Conference.blind_data(1,$('#select_group').val(),$('#searchTxt').val());">ค้นหา</button>
            </div>
        </div>
        <div class="row mx-lg-5 mx-md-1 mx-sm-0 list-conference shadow-sm">
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
        model_View_Conference.blind_data(1,'','');
    });
</script>
<?php include "include/footer.php" ?>