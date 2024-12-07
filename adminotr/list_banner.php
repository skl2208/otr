<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";

$search_text = "";
$status_text = "ALL";

$search_condition = "";
$status_condition = "";
$condition = " WHERE 1 ";

if (isset($_POST["inSearch"])) {
    $search_text = trim($_POST["inSearch"]);
    if (!empty($search_text)) {
        $search_condition = " AND ( keyword LIKE '%".$search_text."%' OR topic LIKE '%" . $search_text . "%')";
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
$condition = $condition.$search_condition.$status_condition;
?>

<h3>Banner</h3>
<div><button class="btn1" onclick="addBanner();">เพิ่มรายการ</button></div>
<div>
    <form method="POST" action="list_banner.php">
        <!-- <label for=inSearch>ค้นหา</label> -->
        <input type="text" id="inSearch" name="inSearch" value="<?php echo $search_text ?>">
        <select id="status" name="status">
            <option value="Y" <?php echo ($status_text=="Y" ? "selected":"")?>>เฉพาะแสดงบนเวป</option>
            <option value="N" <?php echo ($status_text=="N" ? "selected":"")?>>เฉพาะไม่ได้แสดงบนเวป</option>
            <option value="ALL" <?php echo ($status_text=="ALL" ? "selected":"")?>>ทั้งหมด</option>
        </select>
        <button class="btnOK">ค้นหา</button>
        <span class="smallerFont">คำค้นหา <?php echo (($search_text == "") ? "ทั้งหมด" : $search_text) ?></span>
    </form>
</div>
<?php
$sql = "SELECT id,topic,keyword,image_link,url,create_date,status,seq FROM carousel " . $condition . " ORDER BY create_date DESC";
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        echo "<div class=\"mt-3\">จำนวนรายการข้อมูล " . $result->num_rows . " row(s)</div>";
?>
        <div class="d-table w-100 mt-1">
            <div style="display:table-row">
                <div style="width:10%;" class="ps-2 smallerFont head-row">แก้ไข</div>
                <div style="width:50%;" class="ps-2 smallerFont head-row">ภาพ</div>
                <div style="width:20%;" class="ps-2 smallerFont head-row">วันที่สร้าง</div>
                <div style="width:10%;" class="ps-2 smallerFont head-row">ลำดับการแสดง</div>
                <div style="width:10%;" class="ps-2 smallerFont head-row">สถานะ</div>
            </div>
    <?php
        $i = 1;
        while ($row = $result->fetch_assoc()) {
            $image_src = strval($row["image_link"]);
            $pos = strpos($image_src,"http");
            if ($pos===false) {
                $image_src = $baseHTTP . $baseURL . $row["image_link"];
            } 
            echo "<div style=\"display:table-row\">";
            echo "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\"><button class=\"btnEDIT\" onclick=\"javascript:editBanner('" . $row["id"] . "');\">" . $i++ . "</button></div>";
            echo "<div style=\"width:50%;\" class=\"ps-2 border-bottom dp-data\"><img src=\"".$image_src."\" class=\"mt-2 mb-2 shadow\" style=\"height:100px;width:auto;\"></div>";
            echo "<div style=\"width:20%;\" class=\"ps-2 border-bottom dp-data\">" . $row["create_date"] . "</div>";
            echo "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\">" . $row["seq"]. "</div>";
            echo "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\">" . ($row["status"] == "Y" ? "<span class=\"text-success\">แสดง</span>" : "<span class=\"text-danger\">ไม่แสดง</span>") . "</div>";
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