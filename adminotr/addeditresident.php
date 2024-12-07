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
$sql = "SELECT * FROM residency WHERE id=$id";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    // found data
    $headline = "แก้ไข";
    $row = $result->fetch_assoc();

    $pos = strpos($row["picture_URL"], "http");
    if ($pos === false) {
        $row["picture_URL"] = $baseHTTP . $baseURL . $row["picture_URL"];
    }    
} else {
    $headline = "เพิ่ม";
    // Not found data
}
?>
<script>
    function doCancel() {
        window.location.href = "list_resident.php";
    }

    function updateCheckBox(thisobj) {

        if (thisobj.checked) {
            document.getElementById("status1").value = "Y";
        } else {
            document.getElementById("status1").value = "N";
        }
    }

    function validate_data() {

        var a = document.getElementById("name").value;
        var b = document.getElementById("surname").value;
        var c = document.getElementById("username").value;


        if (a == "" || a == null) {
            alert("กรุณาใส่ชื่อ");
            document.getElementById("name").focus();
            return false;
        }

        if (b == "" || b == null) {
            alert("กรุณาใส่นามสกุล");
            document.getElementById("surname").focus();
            return false;
        }
        if (c == "" || c == null) {
            alert("กรุณาใส่ Username");
            document.getElementById("username").focus();
            return false;
        }
        return true;
    }
</script>
<section id="main">
<h3><?php echo $headline ?> แพทย์ประจำบ้าน / แพทย์ประจำบ้านต่อยอด</h3>
<form method="POST" name="formedit" id="formedit">
    <div class="d-table w-100 mt-3">
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">ภาพ</div>
            <div class="ps-2 smallerFont w-100 column-input">
                <img src="<?php echo (($command == "EDIT") ? $row["picture_URL"] : '../images/static_image/a_person.png') ?>" title="" style="height:200px;width:auto" id="picture_URL_preview">
                <?php echo ($command!="EDIT") ? "<br><button type=\"button\" class=\"btnEDIT\" onclick=\"javascript:manage_resident.initupload();\">อัพโหลดภาพ</button>":"" ?>
        </div>
        </div>
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">ยศ/คำนำหน้า</div>
            <div class="ps-2 smallerFont w-100 column-input">
                <select name="titlename" id="titlename">
                    <?php
                    $sql = "SELECT titlename from titlename ORDER BY seq";
                    $result2 = $conn->query($sql);
                    ?>
                    <option value="" selected>กรุณาใส่คำนำหน้า</option>
                    <?php
                    if ($result2 && $result2->num_rows > 0) {
                        while ($row2 = $result2->fetch_assoc()) {
                            echo "<option value=\"{$row2["titlename"]}\"" . (($row2["titlename"] == $row["titlename"] && $command == "EDIT") ? "selected" : "") . ">{$row2["titlename"]}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">ชื่อ</div>
            <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="ชื่อ" class="w-75" id="name" name="name" value="<?php echo (($command == "EDIT") ? $row["name"] : '') ?>"></div>
        </div>
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">นามสกุล</div>
            <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="นามสกุล" class="w-75" id="surname" name="surname" value="<?php echo (($command == "EDIT") ? $row["surname"] : '') ?>"></div>
        </div>
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">ปีการศึกษา</div>
            <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="ปีการศึกษา ระบุเป็น พ.ศ.ที่เริ่มเข้า" class="w-75" id="start_year" name="start_year" value="<?php echo (($command == "EDIT") ? $row["start_year"] : '') ?>"></div>
        </div>
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">สังกัด</div>
            <div class="ps-2 smallerFont w-100 column-input">
                <select name="position" id="position">
                    <option value="0">กรุณาเลือกสังกัด</option>
                    <?php
                    $sql = "SELECT position FROM resident_type ORDER BY position";
                    $result0 = $conn->query($sql);
                    if ($result0 && $result0->num_rows > 0) {
                        while ($row0 = $result0->fetch_assoc()) {
                            echo "<option value=\"" . $row0["position"] . "\"" . ($command == "EDIT" && $row["position"] == $row0["position"] ? "selected" : "") . ">" . $row0["position"] . "</option>";
                        }
                    } ?>
                </select>
            </div>
        </div>
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">สถานะ</div>
            <div class="ps-2 smallerFont w-100 column-input"><input type="checkbox" title="ถ้าไม่ระบุจะให้ค่าเริ่มต้นเป็นแสดง" class="ms-1 me-1" onclick="updateCheckBox(this);" <?php echo (($command == "EDIT") && $row["status"] == 'Y' ? 'checked' : '') ?>>ประจำการ</div>
        </div>
    </div>
    <div class="d-table w-100 mt-3">
        <div class="ps-2 text-center d-table-cell">
            <button class="btnOK" type="button" onclick="javascript:manage_resident.saveInformation(document.forms.formedit);"><img src="../images/icon_save.png">บันทึก</button> <button type="button" class="btnCancel" onclick="doCancel();"><img src="../images/ic_close.png"> ยกเลิก</button>
        </div>
    </div>
    <input type="hidden" id="picture_URL" name="picture_URL" value="<?php echo ($command == "EDIT") ? $row["picture_URL"] : '' ?>">
    <input type="hidden" id="status1" name="status1" value="<?php echo ($command == "EDIT") ? $row["status"] : '' ?>">
    <input type="hidden" id="command" name="command" value="<?php echo $command?>">
    <input type="hidden" id="id" name="id" value="<?php echo ($command == "EDIT") ? $row["id"] : '' ?>">
</form>
</section>
<section id="uploadpicture">
    <div>
        <span class="biggerFont">อัพโหลดใหม่</span>
    </div>
    <div style="min-height:100px;overflow-y:auto;">
        <img src="../images/empty_image.png" style="width:300px;height:auto;border:2px solid gray;margin-bottom:5px" id="showPreviewPicture">
    </div>
    <div>
        <form id="form" method="post" name="formuploadimage" id="formuploadimage" action="javascript:manage_resident.uploadImage(document.forms.formuploadimage);">
            <input type="file" name="inputFile" id="inputFile" placeholder="เลือกไฟล์">
            <input type="hidden" name="typeupload" id="typeupload" value="officer">
            <button class="btnOK"><img src="../images/icon_upload_white.png"> อัพโหลด</button>
        </form>
    </div>
    <div class="text-center mt-4">
        <button type="button" class="btnOK" onclick="manage_resident.saveImage();"><img src="../images/icon_save.png"> บันทึกการเปลี่ยนแปลง</button>
        <button type="button" class="btnCancel" onclick="manage_resident.cancel();"><img src="../images/ic_close.png"> ยกเลิก</button>
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
<div id="showToast"></div>
<?php
$conn->close();
include "inc/footer.php";
?>