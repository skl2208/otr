<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";

$id = 0;
$command = "";
$headline = "";
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $command = "EDIT";
} else {
    $command = "ADD";
}

//Blind data
$sql = "SELECT * FROM resident_group WHERE id=$id";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    // found data
    $headline = "แก้ไข";
    $row = $result->fetch_assoc();
} else {
    $headline = "เพิ่ม";
    // Not found data
}
?>
<script>
    function doCancel() {
        window.location.href = "list_resident_group.php";
    }

    function updateCheckBox(thisobj) {

        if (thisobj.checked) {
            document.getElementById("status1").value = "Y";
        } else {
            document.getElementById("status1").value = "N";
        }
    }

    function validate_data() {

        var a = document.getElementById("group_name").value;

        if (a == "" || a == null) {
            showAlert.show("กรุณาใส่ชื่อ", "DANGER",()=>{
                document.getElementById("group_name").focus();
            });
            //document.getElementById("group_name").focus();
            return false;
        }
        manage_residentgroup.saveInformation(document.forms.formedit);
    }
    $(function() {
        const dl = document.getElementById("download_url").value;
        if (dl != "") {
            $("#viewBtn").show();
            $("#delBtn").show();
            $("#addBtn").hide();
        } else {
            $("#viewBtn").hide();
            $("#delBtn").hide();
            $("#addBtn").show();
        }
    });
</script>
<section id="main">
    <h3><?php echo $headline ?> แพทย์ประจำบ้าน / แพทย์ประจำบ้านต่อยอด</h3>
    <form method="POST" name="formedit" id="formedit">
        <div class="d-table w-100 mt-3">
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">ชื่อกลุ่ม</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="ระบุชื่อกลุ่ม เช่น แพทย์ประจำบ้านประจำปี 2564" class="w-75" id="group_name" name="group_name" value="<?php echo (($command == "EDIT") ? $row["group_name"] : '') ?>"></div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">URL Link เอกสาร</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <span id="showdownload_url">
                        <?php echo (($command == "EDIT") ? $row["download_url"] : '----- NO DOCUMENT -----') ?></span>
                    <span>
                        <button id="viewBtn" class="btnEDIT" onclick="manage_residentgroup.view($('#download_url').val());" type="button">ดู</button>
                        <button id="delBtn" class="btnEDIT" onclick="showConfirm.show('ยืนยันต้องการลบข้อมูลนี้');" type="button">ลบออก</button>
                        <button id="addBtn" class="btnEDIT" onclick="manage_residentgroup.addAttach();" type="button">เพิ่ม</button>
                    </span>
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">สถานะ</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="checkbox" title="ถ้าไม่ระบุจะให้ค่าเริ่มต้นเป็นแสดง" class="ms-1 me-1" onclick="updateCheckBox(this);" <?php echo (($command == "EDIT") && $row["status"] == 'Y' ? 'checked' : '') ?>>Active</div>
            </div>
        </div>
        <div class="d-table w-100 mt-3">
            <div class="ps-2 text-center d-table-cell">
                <button class="btnOK" type="button" onclick="validate_data();">
                    <img src="../images/icon_save.png"> บันทึก
                </button> 
                <button type="button" class="btnCancel" onclick="doCancel();">
                    <img src="../images/ic_close.png"> ยกเลิก
                </button>
            </div>
        </div>
        <input type="hidden" id="download_url" name="download_url" value="<?php echo ($command == "EDIT") ? $row["download_url"] : '' ?>">
        <input type="hidden" id="status1" name="status1" value="<?php echo ($command == "EDIT") ? $row["status"] : '' ?>">
        <input type="hidden" id="command" name="command" value="<?php echo $command ?>">
        <input type="hidden" id="id" name="id" value="<?php echo ($command == "EDIT") ? $row["id"] : '' ?>">
    </form>
</section>
<section id="uploadpicture">
    <div>
        <span class="biggerFont">อัพโหลดเอกสารแนบ (PDF)</span>
    </div>
    <div>
        <form id="formupload" method="post" action="javascript:manage_residentgroup.save_addAttach(document.getElementById('formupload'));">
            <input type="file" name="inputFile" id="inputFile" placeholder="เลือกไฟล์">
            <input type="hidden" name="typeupload" id="typeupload" value="attach">
            <button class="btnOK"><img src="../images/icon_upload_white.png"> อัพโหลด</button>
        </form>
    </div>
    <div class="text-center mt-4">
        <button type="button" class="btnOK" onclick="manage_residentgroup.save_addAttach();"><img src="../images/icon_save.png"> บันทึกการเปลี่ยนแปลง</button>
        <button type="button" class="btnCancel" onclick="manage_residentgroup.cancel();"><img src="../images/ic_close.png"> ยกเลิก</button>
    </div>
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
<div id="showConfirm">
    <div>
        <div>
            <img src="../images/icon_ok.png">
        </div>
        <div></div>
        <div>
            <button onclick="showConfirm.confirm(manage_residentgroup.deleteAttach());" class="btnOK">ต้องการลบ</button> <button onclick="showConfirm.cancel();" class="btnCancel">ยกเลิก</button>
        </div>
    </div>
</div>
<div id="showToast"></div>
<?php
$conn->close();
include "inc/footer.php";
?>