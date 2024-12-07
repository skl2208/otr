<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";

$default_status = "Y";
$default_parent_menu_name = "";
$parent_id = 0;

if (isset($_GET["id"]) && intval($_GET["id"])!=0) {
    $id = $_GET["id"];
    $command = "UPDATE";
    $headline = "แก้ไข";
    $sql = "SELECT menu.id,menu.parent_id,menu.menu_name,menu.level,menu.seq,menu.link_url,menu.status,p_menu.menu_name AS main_menu_name  FROM menu LEFT JOIN menu AS p_menu on menu.parent_id = p_menu.id WHERE menu.id = $id";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $default_parent_menu_name = $row["main_menu_name"];
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
    if (isset($_GET["parent_id"])) {
        $parent_id = $_GET["parent_id"];
        $sql = "SELECT menu_name FROM menu WHERE id=$parent_id";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $default_parent_menu_name = $row["menu_name"];
        }
    } else {
        $sql="No Information";
    }
}


?>
<script>
    function doCancel() {
        window.location.href = "list_excellence_center_menu.php";
    }

    function updateCheckBox(thisobj) {

        if (thisobj.checked) {
            document.getElementById("status").value = "Y";
        } else {
            document.getElementById("status").value = "N";
        }
    }

    function validate_data() {

        const a = document.getElementById("menu_name");
        const b = document.getElementById("seq");

        if (a.value == "" || a.value == null) {

            showAlert.show("กรุณาใส่ชื่อลิงค์ด้วย", "DANGER", () => {
                a.focus();
            });

            return false;
        }

        if (b.value == "" || b.value == null || b.value == 0) {
            b.value = 999;
        }

        manage_excellence_menu.save(document.forms.formedit);

    }
    $(function() {

        const menu_name = document.getElementById("menu_name");
        const seq = document.getElementById("seq");

        seq.addEventListener("change", function() {
            if (seq.value.length == 0) {
                seq.value = 999;
            }
        });
        menu_name.addEventListener("change", function() {
            if (menu_name.value.trim() == "") {
                showAlert.show("กรุณาใส่ชื่อเมนู", "WARN", () => {
                    menu_name.focus();
                });
            }
        });
        seq.focus();
    });
</script>
<h3><?php echo $headline ?> เมนู Excellence Center</h3>
<section id="main">
    <form method="POST" name="formedit" id="formedit">
        <div class="d-table w-100 mt-3">
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px;">ลำดับในการแสดงผล</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="" style="width:4em;" id="seq" name="seq" value="<?php echo ($command == "UPDATE" ? $row["seq"] : ($command=="INSERT"?"999":"")) ?>"> ถ้าไม่ระบุ ค่าเริ่มต้นจะเป็น 999</div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px;">ภายใต้เมนูหลัก</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="text" style="width:30em;" title="จำเป็นต้องใส่" disabled value="<?php echo $default_parent_menu_name ?>"></div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">* ชื่อเมนู</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="ต้องระบุ ไม่เกิน 200ตัวอักษร" style="width:30em;" title="จำเป็นต้องใส่" id="menu_name" name="menu_name" value="<?php echo ($command == "UPDATE" ? $row["menu_name"] : "") ?>"></div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">แสดงในหน้าเวป</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="checkbox" title="ถ้าไม่ระบุจะให้ค่าเริ่มต้นเป็นแสดง" class="ms-1 me-1" id="status1" name="status1" <?php echo (($command == "UPDATE" && $row["status"] == "Y") || $command == "INSERT") ? "checked" : "" ?> onclick="updateCheckBox(this);">แสดง</div>
            </div>
        </div>
        <div class="d-table w-100 mt-3">
            <div class="ps-2 text-center d-table-cell">
                <button class="btnOK" type="button" onclick="javascript:validate_data();"><img src="../images/icon_save.png">บันทึก</button>
                <button type="button" class="btnCancel" onclick="doCancel();"><img src="../images/ic_close.png"> ยกเลิก</button>
            </div>
        </div>
        <input type="hidden" id="parent_id" name="parent_id" value="<?php echo ($command=="INSERT" ? $parent_id:"")?>">
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
        <form id="formupload" method="post" action="javascript:manage_research.uploadAttach(document.getElementById('formupload'));">
            <input type="file" name="inputFile" id="inputFile" placeholder="เลือกไฟล์">
            <input type="hidden" name="typeupload" id="typeupload" value="attach">
            <button class="btnOK"><img src="../images/icon_upload_white.png"> อัพโหลด</button>
        </form>
    </div>
    <div class="text-center mt-4">
        <button type="button" class="btnCancel" onclick="manage_research.cancel_addAttach();"><img src="../images/ic_close.png"> ปิดหน้าต่าง</button>
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
            <button onclick="showConfirm.confirm(manage_research.deleteAttach());" class="btnOK">ต้องการลบ</button> <button onclick="showConfirm.cancel();" class="btnCancel">ยกเลิก</button>
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