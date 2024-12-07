<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";

if(isset($_GET["type"])) {
    $typenews = $_GET["type"];
} else {
    $typenews = 'ข่าวประชาสัมพันธ์';
} 
$condition = " WHERE typenews='".$typenews."' ";

$search_text = "";
$status_text = "ALL";

$search_condition = "";
$status_condition = "";


if (isset($_POST["inSearch"])) {
    $search_text = trim($_POST["inSearch"]);
    if (!empty($search_text)) {
        $search_text = str_replace("'","",$search_text);
        $search_condition = " AND ( keyword LIKE '%".$search_text."%' OR topic LIKE '%" . $search_text . "%' OR detail LIKE '%" . $search_text . "%')";
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

<h3><?php echo $typenews ?></h3>
<div><button class="btn1" onclick="javascript:addNews('<?php echo $typenews ?>');">เพิ่มรายการ</button></div>
<div>
    <form method="POST" action="list_news.php">
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
$sql = "SELECT id,topic,update_date,status,pin FROM news " . $condition . " ORDER BY update_date DESC,create_date DESC";
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        echo "<div class=\"mt-3\">จำนวนรายการข้อมูล " . $result->num_rows . " row(s)</div>";
?>
        <div class="d-table w-100 mt-1">
            <div style="display:table-row">
                <div style="width:10%;" class="ps-2 smallerFont head-row">แก้ไข</div>
                <div style="width:50%;" class="ps-2 smallerFont head-row">หัวข้อข่าว</div>
                <div style="width:20%;" class="ps-2 smallerFont head-row">วันที่ลงข่าว</div>
                <div style="width:10%;" class="ps-2 smallerFont head-row">ปักหมุด</div>
                <div style="width:10%;" class="ps-2 smallerFont head-row">สถานะ</div>
            </div>
    <?php
        $i = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<div style=\"display:table-row\">";
            echo "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\"><button class=\"btnEDIT\" onclick=\"javascript:editNews('" . $row["id"] . "','".$typenews."');\">" . $i++ . "</button></div>";
            echo "<div style=\"width:50%;\" class=\"ps-2 border-bottom dp-data\">" . $row["topic"] . "</div>";
            echo "<div style=\"width:20%;\" class=\"ps-2 border-bottom dp-data\">" . $row["update_date"] . "</div>";
            echo "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\">" . $row["pin"] . "</div>";
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
        <div id="showToast"></div>
        <?php
        $conn->close();
        include "inc/footer.php";
        ?>