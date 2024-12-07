<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";

$id = 0;
$command = "";
$headline = "";
$attach_file_url = "";
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $command = "EDIT";

    $sql = "SELECT id,id_catagory,src_clip,vdo_url,vdo_desc,attach_file_url,is_youtube,update_date,create_date,status,pin FROM vdo WHERE id=$id";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        $command = "ADD";
        $id = 0;
    }
} else {
    $command = "ADD";
}

?>
<section id="main">
    <h3>VDO Clip</h3>
    <form method="POST" name="formedit" id="formedit">
        <div class="d-table w-100 mt-3">
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">แหล่งที่มา</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <select onchange="updateButton(this);" name="src_clip" id="src_clip">
                        <option value="" <?php echo ($command == "EDIT" && $row["src_clip"] == "") ? "selected" : "" ?>>กรุณาเลือกแหล่งที่มาของคลิป</option>
                        <option value="Youtube" <?php echo ($command == "EDIT" && $row["src_clip"] == "Youtube") ? "selected" : "" ?>>Youtube</option>
                        <option value="Googledrive" <?php echo ($command == "EDIT" && $row["src_clip"] == "Googledrive") ? "selected" : "" ?>>Google Drive</option>
                        <option value="Upload" <?php echo ($command == "EDIT" && $row["src_clip"] == "Upload") ? "selected" : "" ?>>Upload</option>
                        <option value="Other" <?php echo ($command == "EDIT" && $row["src_clip"] == "Other") ? "selected" : "" ?>>จาก URLอื่น</option>
                        <option value="Noclip" <?php echo ($command == "EDIT" && $row["src_clip"] == "Noclip") ? "selected" : "" ?>>ไม่มี VDO Clip มีเฉพาะเอกสาร</option>
                    </select>
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">กลุ่มเนื้อหา</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <select name="catagory" id="catagory">
                        <option value="">กรุณาเลือกกลุ่มเนื้อหา</option>
                        <?php
                        $sql1 = "SELECT catagory,id FROM vdo_group WHERE status='Y' ORDER BY catagory";
                        $result1 = $conn->query($sql1);
                        if ($result1 && $result1->num_rows > 0) {
                            while ($row1 = $result1->fetch_assoc()) {
                                if ($row["id_catagory"] == $row1["id"]) {
                                    $selected_text = "selected";
                                } else {
                                    $selected_text = "";
                                }
                                echo "<option value=\"" . $row1["id"] . "\" " . $selected_text . ">" . $row1["catagory"] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">URL</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <input type="text" name="vdo_url" id="vdo_url" style="width:80%" placeholder="http://" value="<?php echo (($command == "EDIT") ? $row["vdo_url"] : "") ?>">
                </div>
            </div>
            <!-- <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">VDO Clip</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <img src="../images/empty_image.png"><br>
                    <button type="button" id="uploadBtn" class="btnEDIT hide-first">อัพโหลดคลิป</button>
                </div>
            </div> -->
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">คำอธิบาย</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="คำอธิบายเนื้อหา" class="w-75" title="" id="vdo_desc" name="vdo_desc" value="<?php echo (($command == "EDIT") ? $row["vdo_desc"] : "") ?>"></div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">เอกสารแนบประกอบ</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <span id="showattach_file_url"><?php echo (($command == "EDIT") ? $row["attach_file_url"] : "----- NO DOCUMENT -----"); ?></span>
                    <button id="viewBtn" class="btnEDIT" onclick="manage_vdo.view($('#attach_file_url').val());" type="button">ดู</button>
                    <button id="delBtn" class="btnEDIT" onclick="showConfirm.show('ยืนยันต้องการลบข้อมูลนี้',manage_vdo.deleteAttach(),doCancel());" type="button">ลบออก</button>
                    <button id="addBtn" class="btnEDIT" onclick="manage_vdo.addAttach();" type="button">เพิ่ม</button>
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">ปักหมุดหรือไม่</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="checkbox" title="ถ้าไม่ระบุจะให้ค่าเริ่มต้นเป็นแสดง" class="ms-1 me-1" id="showpin" name="showpin" onclick="updatePin(this);" <?php echo (($command == "EDIT") && $row["pin"] == "Y" ? "checked" : "") ?>>ปักหมุด</div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">แสดงในหน้าเวป</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="checkbox" title="ถ้าไม่ระบุจะให้ค่าเริ่มต้นเป็นแสดง" class="ms-1 me-1" id="status2" name="status2" onclick="updateCheckBox(this);" <?php echo (($command == "EDIT") && $row["status"] == "Y" ? "checked" : "") ?>>แสดง</div>
            </div>
        </div>
        <div class="d-table w-100 mt-3">
            <div class="ps-2 text-center d-table-cell">
                <input type="hidden" name="type" id="type" value="">
                <?php
                if ($command == "EDIT") {
                    echo "<button type=\"button\" class=\"btnDelete\" style=\"margin-right:2em\" onclick=\"showConfirm.show('ยืนยันต้องการลบ VDO นี้',()=>{manage_vdo.delete('" . $id . "')},doCancel);\"><img src=\"../images/icon_delete.png\" class=\"icon_picture\"> ลบเนื้อหานี้</button>";
                }
                ?>
                <button class="btnOK" type="button" onclick="checkCondition();"><img src="../images/icon_save.png">บันทึก</button>
                <button type="button" class="btnCancel" onclick="doCancel();"><img src="../images/ic_close.png"> ยกเลิก</button>
            </div>
        </div>
        <input type="hidden" id="attach_file_url" name="attach_file_url" value="<?php echo (($command == "EDIT") ? $row["attach_file_url"] : "") ?>">
        <input type="hidden" id="pin" name="pin" value="<?php echo (($command=="EDIT" ? $row["pin"]:"N")) ?>">
        <input type="hidden" id="status1" name="status1" value="Y">
        <input type="hidden" id="is_youtube" name="is_youtube" value="<?php echo (($command == "EDIT") ? $row["is_youtube"] : "") ?>">
        <input type="hidden" id="id" name="id" value="<?php echo (($command == "EDIT") ? $row["id"] : "") ?>">
    </form>
</section>
<section id="uploadpicture">
    <div>
        <span class="biggerFont">อัพโหลดเอกสารแนบ (PDF)</span>
    </div>
    <div>
        <form id="formupload" method="post" action="javascript:manage_vdo.save_addAttach(document.getElementById('formupload'));">
            <input type="file" name="inputFile" id="inputFile" placeholder="เลือกไฟล์">
            <input type="hidden" name="typeupload" id="typeupload" value="attach">
            <button class="btnOK"><img src="../images/icon_upload_white.png"> อัพโหลด</button>
        </form>
    </div>
    <div class="text-center mt-4">
        <button type="button" class="btnOK" onclick="manage_vdo.save_addAttach();"><img src="../images/icon_save.png"> บันทึกการเปลี่ยนแปลง</button>
        <button type="button" class="btnCancel" onclick="manage_vdo.cancel_addAttach();"><img src="../images/ic_close.png"> ยกเลิก</button>
    </div>
</section>
<div class="spinner-border text-info" id="spinner"></div>
<div id="showToast"></div>
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
            <button onclick="showConfirm.confirm();" class="btnOK">ต้องการลบ</button> <button onclick="showConfirm.cancel();" class="btnCancel">ยกเลิก</button>
        </div>
    </div>
</div>
<script>
    function checkCondition() {

        const source_content = document.getElementById("src_clip");
        const attach_file_url = document.getElementById("attach_file_url");
        const vdo_url = document.getElementById("vdo_url");
        const showpin = document.getElementById("showpin");

        if(showpin.checked==true) {
            document.getElementById("pin").value = "Y";
        } else {
            document.getElementById("pin").value = "N";
        }

        if (source_content.value == "Noclip" && attach_file_url.value.trim() == "") {
            showAlert.show("คุณเลือกเนื้อหาแบบไม่มี VDO แต่คุณก็ยังไม่ได้ใส่เอกสารประกอบ", "DANGER");
            return false
        } else if (vdo_url.value == "" && source_content.value != "Noclip") {
            showAlert.show("กรุณาระบุ URL ของ VDO Clip", "DANGER", () => {
                vdo_url.focus();
            });
            return false;
        } else {
            manage_vdo.save(document.forms['formedit']);
            return true
        }

    }

    function validateForm() {

        const obj1 = document.forms["formedit"];

        if (obj1["vdo_url"].value == "") {
            showAlert.show("ต้องใส่ URL ของ VDO", "DANGER", () => {});

            updateVDO(obj1);
        }
    }

    function updateButton(obj1) {

        $("#vdo_url").prop("disabled", false);

        if (obj1.value == "Youtube") {
            $("#uploadBtn").addClass("hide-first");
            $("#is_youtube").val("Y");
        } else if (obj1.value == "Upload") {
            $("#uploadBtn").removeClass("hide-first");
            $("#is_youtube").val("N");
        } else if (obj1.value == "Noclip") {
            $("#vdo_url").prop("disabled", true);
        } else {
            $("#is_youtube").val("N");
        }
    }

    function doCancel() {
        window.location.href = "list_vdo.php";
    }

    function updatePin(thisobj) {

        if (thisobj.checked) {
            document.getElementById("pin").value = "Y";
        } else {
            document.getElementById("pin").value = "N";
        }
    }

    function updateCheckBox(thisobj) {

        if (thisobj.checked) {
            document.getElementById("status1").value = "Y";
        } else {
            document.getElementById("status1").value = "N";
        }
    }
    $(function() {

        const src_clip = document.getElementById("src_clip");
        if ($("#attach_file_url").val() != "") {
            $("#viewBtn").show();
            $("#delBtn").show();
            $("#addBtn").hide();
        } else {
            $("#viewBtn").hide();
            $("#delBtn").hide();
            $("#addBtn").show();
        }

        updateButton(src_clip);

    });
</script>

<?php
$conn->close();
include "inc/footer.php";
