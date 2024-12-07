<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";

$default_status = "Y";

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $command = "UPDATE";
    $headline = "แก้ไข";
    $sql = "SELECT * FROM research WHERE id=" . $id;
    $result = $conn->query($sql);
    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row["status"] == "Y") {
                $default_status = "Y";
            } else {
                $default_status = "N";
            }
        } else {
        }
    } else {
    }
} else {
    $headline = "เพิ่ม";
    $command = "INSERT";
    $id = 0;
}


?>
<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
<script>
    function doCancel() {
        window.location.href = "list_research.php";
    }

    function updateCheckBox(thisobj) {

        if (thisobj.checked) {
            document.getElementById("status").value = "Y";
        } else {
            document.getElementById("status").value = "N";
        }
    }

    function validate_data() {

        var a = document.getElementById("topic").value;
        var b = document.getElementById("name").value;

        if (a == "" || a == null) {

            showAlert.show("กรุณาใส่ชื่อลิงค์ด้วย", "DANGER", () => {
                document.getElementById("topic").focus();
            });

            return false;
        }
        if (b == "" || b == null) {

            showAlert.show("กรุณาใส่ชื่อ", "DANGER", () => {
                document.getElementById("name").focus();
            });

            return false;
        }
        document.getElementById("content").value = CKEDITOR.instances.content.getData();
        manage_res.saveInformation(document.forms.formedit);

    }
    $(function(){
        manage_res.updateValAttach();
    });
</script>
<h3><?php echo $headline ?> งานวิจัย</h3>
<section id="main">
    <form method="POST" name="formedit" id="formedit" target="javascript:void(0);">
        <div class="d-table w-100 mt-3">
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">หัวข้องานวิจัย</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="หัวข้องานวิจัย ไม่เกิน 200ตัวอักษร" class="w-100" title="จำเป็นต้องใส่" id="topic" name="topic" value="<?php echo ($command == "UPDATE" ? $row["topic"] : "") ?>"></div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">เจ้าของวิจัย</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="ระบุคำนำหน้า ชื่อ นามสกุล" class="w-75" title="จำเป็นต้องใส่" id="name" name="name" value="<?php echo ($command == "UPDATE" ? $row["name"] : "") ?>"></div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">อาจารย์ที่ปรึกษา</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="ระบุคำนำหน้า ชื่อ นามสกุลของอาจารย์ที่ปรึกษา ถ้าไม่มีให้เว้นไว้" class="w-75" id="advisor" name="advisor" value="<?php echo ($command == "UPDATE" ? $row["advisor"] : "") ?>"></div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">กลุ่มงานวิจัย</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <select id="group_name" name="group_name">
                        <optgroup label="กรุณาเลือกกลุ่มงานวิจัย">
                            <?php
                            $sql = "SELECT * FROM research_group ORDER BY seq";
                            $result0 = $conn->query($sql);
                            if ($result0 && $result0->num_rows > 0) {
                                while ($row0 = $result0->fetch_assoc()) {
                                    echo "<option value=\"" . trim($row0["group_name"]) . "\"" . ($command == "UPDATE" && trim($row["group_name"]) == trim($row0["group_name"]) ? "selected" : "") . ">" . $row0["group_name"] . "</option>";
                                }
                            }
                            ?>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">เอกสาร Download</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <span id="showdownload_url">
                        <?php echo (($command == "UPDATE") ? $row["download_url"] : '----- NO DOCUMENT -----') ?>
                    </span>
                    <span>
                        <button id="viewBtn" class="btnEDIT" onclick="manage_res.view($('#download_url').val());" type="button">ดู</button>
                        <button id="delBtn" class="btnEDIT" onclick="showConfirm.show('ยืนยันต้องการลบข้อมูลนี้');" type="button">ลบออก</button>
                        <button id="addBtn" class="btnEDIT" onclick="manage_res.addAttach();" type="button">เพิ่ม</button>
                    </span>
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">เนื้อหา</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <textarea id="content" name="content" rows="10" cols="80" onchange="this.value=this.value.replace(/'/g,'&quot;');"><?php echo ($command == "UPDATE" ? $row["content"] : "") ?></textarea>
                    <script>
                        CKEDITOR.replace('content', {
                            contentsCss: 'css/index.css',
                            disableNativeSpellChecker: true,
                            toolbar: [{
                                    name: 'document',
                                    items: ['Source', '-', 'Undo', 'Redo']
                                },
                                {
                                    name: 'basicstyles',
                                    items: ['Bold', 'Italic', 'Strike', '-', 'RemoveFormat']
                                },
                                {
                                    name: 'links',
                                    items: ['Link', 'Unlink', 'Anchor']
                                },
                                {
                                    name: 'insert',
                                    items: ['Image', 'Format']
                                },
                                {
                                    name: 'tools',
                                    items: ['Maximize', 'ShowBlocks', 'About']
                                }
                            ]
                        });
                    </script>
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">แสดงในหน้าเวป</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="checkbox" title="ถ้าไม่ระบุจะให้ค่าเริ่มต้นเป็นแสดง" class="ms-1 me-1" id="status1" name="status1" <?php echo ($default_status == "Y") ? "checked" : "" ?> onclick="updateCheckBox(this);">แสดง</div>
            </div>
        </div>
        <div class="d-table w-100 mt-3">
            <div class="ps-2 text-center d-table-cell">
                <button class="btnOK" type="button" onclick="javascript:validate_data();"><img src="../images/icon_save.png">บันทึก</button>
                <button type="button" class="btnCancel" onclick="doCancel();"><img src="../images/ic_close.png"> ยกเลิก</button>
            </div>
        </div>
        <input type="hidden" id="typesave" name="typesave" value="<?php echo ($command == "UPDATE" ? "UPDATE" : "INSERT") ?>">
        <input type="hidden" id="download_url" name="download_url" value="<?php echo ($command == "UPDATE" ? $row["download_url"] : "") ?>">
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
        <form id="formupload" method="post" action="javascript:manage_res.uploadAttach(document.getElementById('formupload'));">
            <input type="file" name="inputFile" id="inputFile" placeholder="เลือกไฟล์">
            <input type="hidden" name="typeupload" id="typeupload" value="attach">
            <button class="btnOK"><img src="../images/icon_upload_white.png"> อัพโหลด</button>
        </form>
    </div>
    <div class="text-center mt-4">
        <button type="button" class="btnCancel" onclick="manage_res.cancel_addAttach();"><img src="../images/ic_close.png"> ปิดหน้าต่าง</button>
    </div>
</section>
<div class="spinner-border text-info" id="spinner"></div>
<div id="showConfirm">
    <div>
        <div>
            <img src="../images/icon_ok.png">
        </div>
        <div></div>
        <div>
            <button onclick="showConfirm.confirm(manage_res.deleteAttach());" class="btnOK">ต้องการลบ</button> <button onclick="showConfirm.cancel();" class="btnCancel">ยกเลิก</button>
        </div>
    </div>
</div>
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
$conn->close();
include "inc/footer.php";
?>