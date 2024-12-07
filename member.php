<?php 
include "include/header.php";
include "include/config.php";
include "include/checkmember.php";

$sql = "SELECT * FROM user WHERE username='{$_SESSION["username"]}'";
$result = $conn->query($sql);
if($result && $result->num_rows > 0){
    $row = $result->fetch_assoc();
}
?>
<section class="rootnavigator mx-lg-5 mx-md-1 mx-0 p-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col"><a href="index.php">หน้าแรก</a>/ข้อมูลสมาชิก</div>
        </div>
    </div>
</section>
<section class="logoutSection">
    <div class="container-fluid">
        <div class="row mx-lg-5 mx-md-1 mx-sm-0 mb-3 pb-3 template-4 shadow-sm">
            <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                <h3>ข้อมูลสมาชิก</h3>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                <?php echo ($_SESSION["usergroup"] == "ADMIN") ? "<button class=\"btn-green\" onclick=\"window.location.href='administrator/index.php'\">ระบบบริหารจัดการเวปไซต์ (CMS)</button>" : "" ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 mt-3">
                <img src="images/static_image/a_person.png" style="height:200px;width:auto">
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 mt-3">
                <h5>Username <?php echo $_SESSION["username"] ?></h5><br>
                <span class="biggerFont">ชื่อ <?php echo $row["titlename"] . " " . $row["name"] . " " . $row["surname"] ?></span><br>
                <span class="biggerFont">email <?php echo $row["email"] ?></span><br>
                <span class="biggerFont">เบอร์โทร <?php echo $row["telephone"] ?></span><br>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 mt-3 text-center">
                <a href="member_edit.php"><img src="images/icon_config.png" style="width:50px" title="แก้ไขข้อมูล"></a>
            </div>
        </div>
    </div>
</section>
<?php include "include/footer.php" ?>