<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";

$default_status = "Y";
if (isset($_POST["command"])) {
    if ($_POST["command"] == "UPDATE") {

        $id = $_POST["id"];
        // $nameth = $_POST["nameth"];
        $catagory = $_POST["catagory"];
        $status = $_POST["status"];

        $default_status = $status;


        $sql = "UPDATE vdo_group SET catagory=?,status=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssi',$catagory,$status,$id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            //successful update
            header("location: list_vdogroup.php?msg=อัพเดทข้อมูลเรียบร้อยแล้ว");
            exit(0);
        } else {
        }
    }
    if ($_POST["command"] == "INSERT") {

        $catagory = $_POST["catagory"];
        $status = $_POST["status"];

        $sql = "INSERT INTO vdo_group (catagory,status) VALUES (?,?) ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss',$catagory,$status);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            //successful update
            header("location: list_vdogroup.php?msg=เพิ่มข้อมูลเรียบร้อยแล้ว");
            exit(0);
        } else {
        }
    }
} else {
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $command = "UPDATE";
        $headline = "แก้ไข";
        $sql = "SELECT id,catagory,status FROM vdo_group WHERE id=" . $id;
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
}

?>

<script>
    function doCancel() {
        window.location.href = "list_vdogroup.php";
    }

    function updateCheckBox(thisobj) {

        if (thisobj.checked) {
            document.getElementById("status").value = "Y";
        } else {
            document.getElementById("status").value = "N";
        }
    }

    function validate_data() {

        var a = document.getElementById("catagory");

        const check_catagory = a.value.trim();

        if (check_catagory == "" || a == null) {

            showAlert.show("กรุณาใส่ชื่อกลุ่มเนื้อหา e-Learning ให้ถูกต้อง", "DANGER", () => {
                a.focus();
            });
            return false;
        } else {
            return true;
        }
    }
</script>
<h3><?php echo $headline ?> กลุ่มเนื้อหา e-Learning</h3>
<form method="POST" target="" onSubmit="return validate_data();">
    <div class="d-table w-100 mt-3">
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">ชื่อ</div>
            <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="ชื่อกลุ่ม" class="w-75" title="จำเป็นต้องใส่" id="catagory" name="catagory" value="<?php echo ($command == "UPDATE" ? $row["catagory"] : "") ?>"></div>
        </div>
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">ใช้งานปกติ</div>
            <div class="ps-2 smallerFont w-100 column-input"><input type="checkbox" title="ถ้าไม่ระบุจะให้ค่าเริ่มต้นเป็นใช้งานปกติ" class="ms-1 me-1" id="status1" name="status1" <?php echo ($default_status == "Y") ? "checked" : "" ?> onclick="updateCheckBox(this);">แสดง</div>
        </div>
    </div>
    <div class="d-table w-100 mt-3">
        <div class="ps-2 text-center d-table-cell">
            <button type="submit" class="btnOK"><img src="../images/icon_save.png">บันทึก</button> <button type="button" class="btnCancel" onclick="doCancel();"><img src="../images/ic_close.png"> ยกเลิก</button>
        </div>
    </div>
    <input type="hidden" id="status" name="status" value="<?php echo $default_status; ?>">
    <input type="hidden" id="command" name="command" value="<?php echo $command; ?>">
    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
</form>
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