<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";
?>
<script>
    function validate_data() {
        const searchTxt = document.getElementById("searchTxt").value;
        const type_confer = document.getElementById("type_confer").value;

        manage_conference.list_conference(1,searchTxt,type_confer);

    }
    $(function() {
        manage_conference.list_conference(1, "", "");
    });
</script>
<h3>งานประชุม</h3>
<div><button class="btn1" onclick="javascript:manage_conference.add();">เพิ่มรายการ</button></div>
<div>
    <form method="POST" action="javascript:validate_data();">
        <input type="text" id="searchTxt" name="searchTxt" value="">
        <select id="type_confer" name="type_confer">
            <option value="">ทั้งหมด</option>
            <option value="ภายในโรงพยาบาล">ภายในโรงพยาบาล</option>
            <option value="ภายนอกโรงพยาบาล">ภายนอกโรงพยาบาล</option>
        </select>
        <button class="btnOK">ค้นหา</button>
    </form>
</div>
<div class="mt-3" id="displayTotalRec"></div>
<div class="d-table w-100 mt-1 list-conference">
</div>
<section class="paging mt-2">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center" id="displayPaging">
        </ul>
    </nav>
</section>
<div class="spinner-border text-info" id="spinner"></div>
<div id="showAlert">
    <div>
        <div>
            <img src="../images/icon_ok.png">
        </div>
        <div></div>
        <div>
            <button onclick="javascript:showAlert.hide();" class="btnOK">ปิด</button>
        </div>
    </div>
</div>
<div id="showToast"></div>
<?php
include "inc/footer.php";
?>