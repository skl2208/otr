<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";

$default_status = "Y";
$headline = "แก้ไข";

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $command = "UPDATE";
    $headline = "แก้ไข";
    $sql = "SELECT * FROM download WHERE id=" . $id;
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {

        $row = $result->fetch_assoc();
        if ($row["status"] == "Y") {
            $default_status = "Y";
        } else {
            $default_status = "N";
        }
    }
} else {
    $headline = "เพิ่ม";
    $command = "INSERT";
    $id = 0;
}
?>
<script>
    function updateCheckBox(thisobj) {

        if (thisobj.checked) {
            document.getElementById("status").value = "Y";
        } else {
            document.getElementById("status").value = "N";
        }
    }

    function validate_data() {

        var b = document.getElementById("link_name").value;

        if (b == "" || b == null) {
            showAlert("กรุณาใส่ชื่อลิงค์ด้วย", "DANGER");
            document.getElementById("link_name").focus();
            return false;
        }

        return true;
    }
    $(function() {
        const dl = document.getElementById("link_url").value;
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
    <h3><?php echo $headline ?> Download เอกสาร</h3>
    <form method="POST" name="formedit" action="javascript:void(0)" onsubmit="return validate_data();">
        <div class="d-table w-100 mt-3">
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">ลำดับแสดงผล</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <input type="text" placeholder="ลำดับเป็นตัวเลข" class="w-25" title="ถ้าไม่ใส่ก็ได้" id="seq" name="seq" value="<?php echo ($command == "UPDATE" ? $row["seq"] : "") ?>">
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">ชื่อเอกสาร</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <input type="text" placeholder="ชื่อเรียกเวปไซต์" class="w-75" title="จำเป็นต้องใส่" id="link_name" name="link_name" value="<?php echo ($command == "UPDATE" ? $row["link_name"] : "") ?>">
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">คำอธิบาย</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <input type="text" placeholder="คำอธิบาย" class="w-75" title="" id="description" name="description" value="<?php echo ($command == "UPDATE" ? $row["description"] : "") ?>">
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">URL เก็บเอกสาร</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <span id="showlink_url">
                        <?php echo (($command == "UPDATE") ? $row["link_url"] : '----- NO DOCUMENT -----') ?></span>
                    <span>
                        <button id="viewBtn" class="btnEDIT" onclick="manage_download.view($('#link_url').val());" type="button">ดู</button>
                        <button id="delBtn" class="btnEDIT" onclick="showConfirm.show('ยืนยันต้องการลบข้อมูลนี้');" type="button">ลบออก</button>
                        <button id="addBtn" class="btnEDIT" onclick="manage_download.addAttach();" type="button">เพิ่ม</button>
                    </span>
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">แสดงในหน้าเวป</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="checkbox" title="ถ้าไม่ระบุจะให้ค่าเริ่มต้นเป็นแสดง" class="ms-1 me-1" id="status1" name="status1" <?php echo ($default_status == "Y") ? "checked" : "" ?> onclick="updateCheckBox(this);">แสดง</div>
            </div>
        </div>
        <div class="d-table w-100 mt-3">
            <div class="ps-2 text-center d-table-cell">
                <button class="btnOK" onclick="javascript:manage_download.save(document.forms.formedit);"><img src="../images/icon_save.png">บันทึก</button> <button type="button" class="btnCancel" onclick="manage_download.close();"><img src="../images/ic_close.png"> ยกเลิก</button>
            </div>
        </div>
        <input type="hidden" id="link_url" name="link_url" value="<?php echo ($command == "UPDATE") ? $row["link_url"] : '' ?>">
        <input type="hidden" id="status" name="status" value="<?php echo $default_status; ?>">
        <input type="hidden" id="command" name="command" value="<?php echo $command; ?>">
        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
    </form>
</section>
<section id="uploadpicture">
    <div>
        <span class="biggerFont">อัพโหลดเอกสารแนบ (PDF)</span>
    </div>
    <div>
        <form id="formupload" method="post" action="javascript:manage_download.save_addAttach(document.getElementById('formupload'));">
            <input type="file" name="inputFile" id="inputFile" placeholder="เลือกไฟล์">
            <input type="hidden" name="typeupload" id="typeupload" value="attach">
            <button class="btnOK"><img src="../images/icon_upload_white.png"> อัพโหลด</button>
        </form>
    </div>
    <div class="text-center mt-4">
        <button type="button" class="btnOK" onclick="manage_download.save_addAttach();"><img src="../images/icon_save.png"> บันทึกการเปลี่ยนแปลง</button>
        <button type="button" class="btnCancel" onclick="manage_download.cancel_addAttach();"><img src="../images/ic_close.png"> ยกเลิก</button>
    </div>
</section>
<div class="spinner-border text-info modal" id="spinner"></div>
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
            <button onclick="showConfirm.confirm(manage_download.deleteAttach());" class="btnOK">ต้องการลบ</button> <button onclick="showConfirm.cancel();" class="btnCancel">ยกเลิก</button>
        </div>
    </div>
</div>
<div id="showToast"></div>
<?php
$conn->close();
include "inc/footer.php";
?>