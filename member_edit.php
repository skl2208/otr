<?php
include "include/config.php";
include "include/header.php";
include "include/checkmember.php";

$username = $_SESSION["username"];
$sql = "SELECT * FROM user WHERE username='$username'";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
}
?>

<section class="rootnavigator mx-lg-5 mx-md-1 mx-0 p-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col"><a href="index.php">หน้าแรก</a>/แก้ไขข้อมูลสมาชิก</div>
        </div>
    </div>
</section>
<section class="logoutSection">
    <div class="container-fluid">
        <div class="row mx-lg-5 mx-md-1 mx-sm-0 mb-3 pb-3 template-4 shadow-sm">
            <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                <h3>แก้ไขข้อมูลสมาชิก</h3>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                <?php echo ($_SESSION["username"] == "admin") ? "<button class=\"btn-green\" onclick=\"window.location.href='administrator/index.php'\">ระบบบริหารจัดการเวปไซต์ (CMS)</button>" : "" ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 mt-3">
                <img src="images/static_image/a_person.png" style="height:200px;width:auto">
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 mt-3">
                <h5>Username <?php echo $row["username"] ?></h5>
                <hr>
                <form name="form1" id="form1" method="POST">
                    <div class="row">
                        <div class="col-4 mb-1"><label for=titlename>คำนำหน้า</label></div>
                        <div class="col-8 mb-1">
                            <select name="titlename" id="titlename">
                                <?php
                                $sql = "SELECT titlename from titlename ORDER BY seq";
                                $result2 = $conn->query($sql);
                                ?>
                                <option value="" selected>กรุณาใส่คำนำหน้า</option>
                                <?php
                                if ($result2 && $result2->num_rows > 0) {
                                    while ($row2 = $result2->fetch_assoc()) {
                                        echo "<option value=\"{$row2["titlename"]}\"" . (($row2["titlename"] == $row["titlename"]) ? "selected" : "") . ">{$row2["titlename"]}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-4 mb-1">ชื่อ</div>
                        <div class="col-8 mb-1"><input type="text" name="name" id="name" value="<?php echo $row["name"] ?>"></div>
                        <div class="col-4 mb-1">นามสกุล</div>
                        <div class="col-8 mb-1"><input type="text" name="surname" id="surname" value="<?php echo $row["surname"] ?>"></div>
                        <div class="col-4 mb-1">email</div>
                        <div class="col-8 mb-1"><input type="text" name="email" id="email" value="<?php echo $row["email"] ?>" placeholder="email"></div>
                        <div class="col-4 mb-1">เบอร์โทร</div>
                        <div class="col-8 mb-1"><input type="text" name="telephone" id="telephone" value="<?php echo $row["telephone"] ?>" placeholder="ถ้าระบุมากว่า 1 เบอร์ให้คั่นด้วย ,"></div>
                        <div class="col-12 text-center mt-2">
                            <input type="hidden" name="username1" id="username1" value="<?php echo $row["username"] ?>">
                            <button class="btn-green btn-fitcontent" type="button" onclick="manage_member.savedata(document.forms.form1);">บันทึกการเปลี่ยนแปลง</button>
                            <button class="btn-cancel btn-fitcontent" type="button" onclick="window.location.href='member.php';">ยกเลิก</button>
                        </div>
                    </div>
                </form>
                <br><br>
                <form name="form2" id="form2" method="POST">
                    <h5>เปลี่ยนรหัสผ่าน</h5>
                    <hr>
                    <div class="row">
                        <div class="col-12 mb-1">
                            <h5>เปลี่ยนรหัสผ่าน</h5>
                        </div>
                        <div class="col-4 mb-1">รหัสผ่านเดิม</div>
                        <div class="col-8 mb-1"><input type="password" name="oldpassword" id="oldpassword"></div>
                        <div class="col-4 mb-1">รหัสผ่านใหม่</div>
                        <div class="col-8 mb-1"><input type="password" name="newpassword1" id="newpassword1"></div>
                        <div class="col-4 mb-1">ยืนยันรหัสอีกครั้ง</div>
                        <div class="col-8 mb-1"><input type="password" name="newpassword2" id="newpassword2"></div>
                        <div class="col-12 text-center mt-2">
                            <input type="hidden" name="id" id="id" value="<?php echo $row["id"] ?>">
                            <input type="hidden" name="username" id="username" value="<?php echo $row["username"] ?>">
                            <button class="btn-green btn-fitcontent" type="button" onclick="manage_member.savepassword(document.forms.form2);">เปลี่ยนรหัสผ่าน</button>
                            <button class="btn-cancel btn-fitcontent" type="button" onclick="window.location.href='member.php';">ยกเลิก</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<div id="showToast"></div>
<?php
$conn->close();
include "include/footer.php";
?>