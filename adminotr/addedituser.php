<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";

$default_status = "Y";
$default_titlename = "";

if (isset($_POST["command"])) {
    if ($_POST["command"] == "UPDATE") {
        $headline = "";
        $id = $_POST["id"];
        $titlename = $_POST["titlename"];
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $telephone = $_POST["telephone"];
        $status = $_POST["status"];
        $default_status = $status;
        echo "INSERT..." . $headline . $_POST["command"];
        $sql = "UPDATE user SET titlename=?,name=?,surname=?,username=?,password=?,email=?,telephone=?,status=?,update_date=CURRENT_TIMESTAMP() WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssssi',$titlename,$name,$surname,$username,$password,$email,$telephone,$status,$id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            //successful update
            $conn->close();
            header("location: list_user.php?msg=อัพเดทข้อมูลเรียบร้อยแล้ว");
            exit(0);
        }
    }
    if ($_POST["command"] == "INSERT") {

        $headline = "";
        $titlename = $_POST["titlename"];
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $telephone = $_POST["telephone"];
        $status = $_POST["status"];
        $default_status = $status;

        $sql = "INSERT INTO user (titlename,name,surname,username,password,email,telephone,status) VALUES (?,?,?,?,?,?,?,?) ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssss',$titlename,$name,$surname,$username,$password,$email,$telephone,$status);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            //successful update
            header("location: list_user.php?msg=เพิ่มข้อมูลเรียบร้อยแล้ว");
            exit(0);
        }
    }
} else {
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $command = "UPDATE";
        $headline = "แก้ไข";
        $sql = "SELECT id,titlename,name,surname,username,password,picture_URL,email,telephone,status FROM user WHERE id=" . $id;
        $result = $conn->query($sql);
        if ($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $default_titlename = $row["titlename"];
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
        window.location.href = "list_user.php";
    }

    function updateCheckBox(thisobj) {

        if (thisobj.checked) {
            document.getElementById("status").value = "Y";
        } else {
            document.getElementById("status").value = "N";
        }
    }

    function validate_data() {

        var a = document.getElementById("name").value;
        var b = document.getElementById("surname").value;
        var c = document.getElementById("username").value;


        if (a == "" || a == null) {

            showAlert.show("กรุณาใส่ชื่อ", "DANGER", () => {
                document.getElementById("name").focus();
            });

            return false;
        }

        if (b == "" || b == null) {

            showAlert.show("กรุณาใส่นามสกุล", "DANGER", () => {
                document.getElementById("surname").focus();
            });

            return false;
        }
        if (c == "" || c == null) {

            showAlert.show("กรุณาใส่ Username", "DANGER", () => {
                document.getElementById("username").focus();
            });

            return false;
        }
        return true;
    }
</script>
<h3><?php echo $headline ?> สมาชิกเวปไซต์</h3>
<form method="POST" target="" onsubmit="return validate_data();">
    <div class="d-table w-100 mt-3">
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">คำนำหน้า</div>
            <div class="ps-2 smallerFont w-100 column-input">
                <select name="titlename" id="titlename">
                    <?php
                    $sql = "SELECT titlename from titlename ORDER BY titlename";
                    $result2 = $conn->query($sql);
                    ?>
                    <option value="" selected>กรุณาใส่คำนำหน้า</option>
                    <?php
                    if ($result2 && $result2->num_rows > 0) {
                        while ($row2 = $result2->fetch_assoc()) {
                            echo "<option value=\"{$row2["titlename"]}\"" . (($row2["titlename"] == $default_titlename) ? "selected" : "") . ">{$row2["titlename"]}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">ชื่อ</div>
            <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="ชื่อ" class="w-75" title="จำเป็นต้องใส่" id="name" name="name" value="<?php echo ($command == "UPDATE" ? $row["name"] : "") ?>"></div>
        </div>
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">นามสกุล</div>
            <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="นามสกุล" class="w-75" title="จำเป็นต้องใส่" id="surname" name="surname" value="<?php echo ($command == "UPDATE" ? $row["surname"] : "") ?>"></div>
        </div>
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">Username</div>
            <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="Username" class="w-75" title="จำเป็นต้องใส่" id="username" name="username" value="<?php echo ($command == "UPDATE" ? $row["username"] : "") ?>"></div>
        </div>
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">password</div>
            <div class="ps-2 smallerFont w-100 column-input"><input type="password" placeholder="password" class="w-75" title="ไม่จำเป็นต้องใส่" id="password" name="password" value="<?php echo ($command == "UPDATE" ? $row["password"] : "") ?>"></div>
        </div>
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">email</div>
            <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="email" class="w-75" title="ไม่จำเป็นต้องใส่" id="email" name="email" value="<?php echo ($command == "UPDATE" ? $row["email"] : "") ?>"></div>
        </div>
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">telephone</div>
            <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="เบอร์โทรติดต่อ" class="w-75" title="ไม่จำเป็นต้องใส่" id="telephone" name="telephone" value="<?php echo ($command == "UPDATE" ? $row["telephone"] : "") ?>"></div>
        </div>
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">อนุญาตให้ใช้งาน</div>
            <div class="ps-2 smallerFont w-100 column-input"><input type="checkbox" title="ถ้าไม่ระบุจะให้ค่าเริ่มต้นเป็นแสดง" class="ms-1 me-1" id="status1" name="status1" <?php echo ($default_status == "Y") ? "checked" : "" ?> onclick="updateCheckBox(this);">ใช้งาน</div>
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
<?php
$conn->close();
include "inc/footer.php";
?>