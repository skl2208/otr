<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";

$search_text = "";
$status_text = "ALL";
$condition = " WHERE 1 ";
$search_condition = "";
$status_condition = "";
if (isset($_POST["inSearch"])) {
    $search_text = str_replace("'","",trim($_POST["inSearch"]));
    if (!empty($search_text)) {
        $search_condition = " AND (name LIKE '%" . $search_text . "%' OR surname LIKE '%" . $search_text . "%' OR email LIKE '%" . $search_text . "%' OR telephone LIKE '%" . $search_text . "%')";
    }
}
if (isset($_POST["status"])) {
    if ($_POST["status"] == "ALL") {
        $status_text = "ALL";
        $status_condition = "";
    } else {
        $status_text = $_POST["status"];
        $status_condition = " AND status='" . $_POST["status"] . "'";
    }
}
$condition = $condition . $search_condition . $status_condition;
?>
<h3>สมาชิกเวปไซต์</h3>
<div><button class="btn1" onclick="javascript:addUser();">เพิ่มรายการ</button></div>
<div>
    <form method="POST" action="list_user.php">
        <!-- <label for=inSearch>ค้นหา</label> -->
        <input type="text" id="inSearch" name="inSearch" value="<?php echo $search_text ?>">
        <select id="status" name="status">
            <option value="Y" <?php echo ($status_text == "Y" ? "selected" : "") ?>>เฉพาะใช้งานได้</option>
            <option value="N" <?php echo ($status_text == "N" ? "selected" : "") ?>>เฉพาะห้ามใช้</option>
            <option value="ALL" <?php echo ($status_text == "ALL" ? "selected" : "") ?>>ทั้งหมด</option>
        </select>
        <button class="btnOK">ค้นหา</button>
        <span class="smallerFont">คำค้นหา <?php echo (($search_text == "") ? "ทั้งหมด" : $search_text) ?></span>
    </form>
</div>
<?php
$sql = "SELECT id,titlename,name,surname,username,password,email,telephone,status FROM user " . $condition . " ORDER BY update_date DESC";
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        echo "<div class=\"mt-3\">จำนวนรายการข้อมูล " . $result->num_rows . " row(s)</div>";
?>
        <div class="d-table w-100 mt-1">
            <div style="display:table-row">
                <div style="width:10%;" class="ps-2 smallerFont head-row">แก้ไข</div>
                <div style="width:40%;" class="ps-2 smallerFont head-row">ชื่อ-นามสกุล</div>
                <div style="width:20%;" class="ps-2 smallerFont head-row">email</div>
                <div style="width:20%;" class="ps-2 smallerFont head-row">telephone</div>
                <div style="width:10%;" class="ps-2 smallerFont head-row">สถานะ</div>
            </div>
    <?php
        $i = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<div style=\"display:table-row\">";
            echo "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\"><button class=\"btnEDIT\" onclick=\"javascript:editUser('" . $row["id"] . "');\">" . $i++ . "</button></div>";
            echo "<div style=\"width:40%;\" class=\"ps-2 border-bottom dp-data\">{$row["titlename"]}{$row["name"]} {$row["surname"]} </div>";
            echo "<div style=\"width:20%;\" class=\"ps-2 border-bottom dp-data\">" . $row["email"] . "</div>";
            echo "<div style=\"width:20%;\" class=\"ps-2 border-bottom dp-data\">" . $row["telephone"] . "</div>";            
            echo "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\">" . ($row["status"] == "Y" ? "<span class=\"text-success\">ใช้งานได้</span>" : "<span class=\"text-danger\">ห้ามใช้งาน</span>") . "</div>";
            echo "</div>";
        }
    } else {
        echo "<div class=\"mt-3 biggerFont\">ไม่มีข้อมูล</div>";
    }
} else {
    echo "<div class=\"mt-3 biggerFont\">ไม่มีข้อมูล</div>";
}

    ?>
        </div>
        <div id="showToast"></div>
        <?php
        $conn->close();
        include "inc/footer.php";
        ?>