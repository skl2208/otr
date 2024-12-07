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
        $nameen = $_POST["nameen"];
        $status = $_POST["status"];
        $description = $_POST["description"];
        $description2 = $_POST["description2"];
        $default_status = $status;
        $seq = $_POST["seq"];

        $sql = "UPDATE department SET seq=?,status=?,nameen=?,description=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isssi',$seq,$status,$nameen,$description,$id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            //successful update
            header("location: list_department.php?msg=อัพเดทข้อมูลเรียบร้อยแล้ว");
            exit(0);
        } else {
        }
    }
    if ($_POST["command"] == "INSERT") {

        $nameth = $_POST["nameth"];
        $nameen = $_POST["nameen"];
        $status = $_POST["status"];
        $description = $_POST["description"];
        $seq = $_POST["seq"];

        $sql = "INSERT INTO department (seq,nameth,nameen,description,status) VALUES (?,?,?,?,?) ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('issss',$seq,$nameth,$nameen,$description,$status);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            //successful update
            header("location: list_department.php?msg=เพิ่มข้อมูลเรียบร้อยแล้ว");
            exit(0);
        } else {
        }
    }
} else {
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $command = "UPDATE";
        $headline = "แก้ไข";
        $sql = "SELECT seq,nameth,nameen,id,description,status FROM department WHERE id=" . $id;
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
<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
<script>
    function doCancel() {
        window.location.href = "list_department.php";
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
        var b = document.getElementById("nameth").value;
        var c = document.getElementById("nameen").value;
        var d = document.getElementById("description").value;
        var e = document.getElementById("status").value;

        if (a == "" || a == null) {
            document.getElementById("seq").value = 999;
        }

        if (b == "" || b == null) {
            alert("กรุณาใส่ชื่อสาขาภาษาไทยด้วย");
            document.getElementById("nameth").focus();
            return false;
        }
    }
</script>
<h3><?php echo $headline ?> หน่วยงาน,สาขาวิชา</h3>
<form method="POST" target="" onsubmit="return validate_data();">
    <div class="d-table w-100 mt-3">
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">ลำดับแสดงผล</div>
            <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="ลำดับเป็นตัวเลข" class="w-25" title="ถ้าไม่ใส่ก็ได้" id="seq" name="seq" value="<?php echo ($command == "UPDATE" ? $row["seq"] : "") ?>"></div>
        </div>
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">ชื่อภาษาไทย</div>
            <div class="ps-2 smallerFont w-100 column-input"><input type="text" <?php echo($command=="UPDATE" ? "disabled":"")?> placeholder="ชื่อหน่วยงานเป็นภาษาไทย" class="w-75" title="จำเป็นต้องใส่" id="nameth" name="nameth" value="<?php echo ($command == "UPDATE" ? $row["nameth"] : "") ?>"></div>
        </div>
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">ชื่อภาษาอังกฤษ</div>
            <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="ชื่อหน่วยงานเป็นภาษาอังกฤษ" class="w-75" title="จำเป็นต้องใส่" id="nameen" name="nameen" value="<?php echo ($command == "UPDATE" ? $row["nameen"] : "") ?>"></div>
        </div>
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">คำอธิบายเกี่ยวกับหน่วย</div>
            <div class="ps-2 smallerFont w-100 column-input">
                <textarea rows="5" cols="100" placeholder="คำอธิบายเกี่ยวกับหน่วยงาน" class="w-75" title="" id="description" name="description"><?php echo ($command == "UPDATE" ? $row["description"] : "") ?></textarea>
                <script>
                        CKEDITOR.replace('description', {
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
                                    name: 'paragraph',
                                    items: ['NumberedList', 'BulletedList','Indent','-', 'Blockquote']
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