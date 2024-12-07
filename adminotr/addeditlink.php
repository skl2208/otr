<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";

$default_status = "Y";
if (isset($_POST["command"])) {
    if ($_POST["command"] == "UPDATE") {

        $id = $_POST["id"];
        $link_name = $_POST["link_name"];
        $link_url = $_POST["link_url"];
        $status = $_POST["status"];
        $default_status = $status;
        $seq = $_POST["seq"];

        $sql = "UPDATE link SET seq=?,status=?,link_name=?,link_url=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isssi',$seq,$status,$link_name,$link_url,$id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            //successful update
            header("location: list_link.php?msg=อัพเดทข้อมูลเรียบร้อยแล้ว");
            exit(0);
        } else {
        }
    }
    if ($_POST["command"] == "INSERT") {

        $link_name = $_POST["link_name"];
        $link_url = $_POST["link_url"];
        $status = $_POST["status"];
        $seq = $_POST["seq"];

        $sql = "INSERT INTO link (seq,link_name,link_url,status) VALUES (?,?,?,?) ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isss',$seq,$link_name,$link_url,$status);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            //successful update
            header("location: list_link.php?msg=เพิ่มข้อมูลเรียบร้อยแล้ว");
            exit(0);
        } else {
        }
    }
} else {
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $command = "UPDATE";
        $headline = "แก้ไข";
        $sql = "SELECT seq,link_name,link_url,id,status FROM link WHERE id=" . $id;
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
        window.location.href = "list_link.php";
    }

    function updateCheckBox(thisobj) {

        if (thisobj.checked) {
            document.getElementById("status").value = "Y";
        } else {
            document.getElementById("status").value = "N";
        }
    }

    function validate_data() {

        var a = document.getElementById("seq").value;
        var b = document.getElementById("link_name").value;
        var c = document.getElementById("link_url").value;
        var d = document.getElementById("status").value;

        if (a == "" || a == null) {
            document.getElementById("seq").value = 999;
        }

        if (b == "" || b == null) {
            alert("กรุณาใส่ชื่อลิงค์ด้วย");
            document.getElementById("link_name").focus();
            return false;
        }
        if (c == "" || c == null) {
            alert("กรุณาใส่ URL ของลิงค์ด้วย");
            document.getElementById("link_url").focus();
            return false;
        }
        return true;
    }
</script>
<h3><?php echo $headline ?> รายการ LINK เวปไซต์อื่น</h3>
<form method="POST" target="" onsubmit="return validate_data();">
    <div class="d-table w-100 mt-3">
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">ลำดับแสดงผล</div>
            <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="ลำดับเป็นตัวเลข" class="w-25" title="ถ้าไม่ใส่ก็ได้" id="seq" name="seq" value="<?php echo ($command == "UPDATE" ? $row["seq"] : "") ?>"></div>
        </div>
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">ชื่อลิงค์</div>
            <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="ชื่อเรียกเวปไซต์" class="w-75" title="จำเป็นต้องใส่" id="link_name" name="link_name" value="<?php echo ($command == "UPDATE" ? $row["link_name"] : "") ?>"></div>
        </div>
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">URL ที่จะเรียกไป</div>
            <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="URL ของเวปไซต์" class="w-75" title="จำเป็นต้องใส่" id="link_url" name="link_url" value="<?php echo ($command == "UPDATE" ? $row["link_url"] : "") ?>"></div>
        </div>
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">แสดงในหน้าเวป</div>
            <div class="ps-2 smallerFont w-100 column-input"><input type="checkbox" title="ถ้าไม่ระบุจะให้ค่าเริ่มต้นเป็นแสดง" class="ms-1 me-1" id="status1" name="status1" <?php echo ($default_status == "Y") ? "checked" : "" ?> onclick="updateCheckBox(this);">แสดง</div>
        </div>
    </div>
    <div class="d-table w-100 mt-3">
        <div class="ps-2 text-center d-table-cell">
            <button class="btnOK"><img src="../images/icon_save.png">บันทึก</button> <button type="button" class="btnCancel" onclick="doCancel();"><img src="../images/ic_close.png"> ยกเลิก</button>
        </div>
    </div>
    <input type="hidden" id="status" name="status" value="<?php echo $default_status; ?>">
    <input type="hidden" id="command" name="command" value="<?php echo $command; ?>">
    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
</form>
<?php
$conn->close();
include "inc/footer.php";
?>