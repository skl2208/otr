<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";

$numRecPerPage = 12;
$total_rec = 0;
$max_page = 0;
$condition = "";
$condition1 = "";
$condition2 = "";
$inSearch = (isset($_GET["inSearch"]) ? $_GET["inSearch"] : "");
$status = (isset($_GET["status"]) ? $_GET["status"] : "");
$pageno = (isset($_GET["pageno"]) ? $_GET["pageno"] : 1);

if ($inSearch != "") {
    $condition1 = " AND (link_name LIKE '%$inSearch%' OR description LIKE '%$inSearch%') ";
}
if ($status != "") {
    $condition2 = " AND status='$status'";
}
$condition = $condition1 . $condition2;
?>
<h3>Download เอกสาร</h3>
<div><button class="btn1" onclick="javascript:manage_download.add();">เพิ่มรายการ</button></div>
<div>
    <form method="GET" action="list_download.php">
        <!-- <label for=inSearch>ค้นหา</label> -->
        <input type="text" id="inSearch" name="inSearch" value="<?php echo $inSearch ?>">
        <select id="status" name="status">
            <option value="Y" <?php echo ($status == "Y" ? "selected" : "") ?>>เฉพาะแสดงบนเวป</option>
            <option value="N" <?php echo ($status == "N" ? "selected" : "") ?>>เฉพาะไม่ได้แสดงบนเวป</option>
            <option value="" <?php echo ($status == "" ? "selected" : "") ?>>ทั้งหมด</option>
        </select>
        <button class="btnOK">ค้นหา</button>
        <span class="smallerFont">คำค้นหา <?php echo (($inSearch == "") ? "ทั้งหมด" : $inSearch) ?></span>
    </form>
</div>
<div>
    <?php
    $sql = "SELECT COUNT(seq) as totalrec FROM download WHERE 1 " . $condition;
    $result0 = $conn->query($sql);
    if ($result0 && $result0->num_rows > 0) {
        $row0 = $result0->fetch_assoc();
        $total_rec = $row0["totalrec"];
        $max_page = ceil($total_rec / $numRecPerPage);
    }
    if ($total_rec != 0) {
        echo "จำนวนรายการ $total_rec รายการ กำลังแสดงหน้าที่ $pageno/$max_page";
    } else {
        echo "";
    }
    ?>

</div>
<?php
$sql = "SELECT seq,id,link_name,link_url,description,update_date,status FROM download WHERE 1 " . $condition . " ORDER BY seq LIMIT " . (($pageno - 1) * $numRecPerPage) . "," . $numRecPerPage;
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        echo "<div class=\"mt-3\">จำนวนรายการข้อมูล " . $result->num_rows . " row(s)</div>";
?>
        <div class="d-table w-100 mt-1">
            <div style="display:table-row">
                <div style="width:10%;" class="ps-2 smallerFont head-row">แก้ไข</div>
                <div style="width:10%;" class="ps-2 smallerFont head-row">ลำดับแสดงผล</div>
                <div style="width:70%;" class="ps-2 smallerFont head-row">ชื่อลิงค์</div>
                <div style="width:10%;" class="ps-2 smallerFont head-row">สถานะ</div>
            </div>
    <?php
        $i = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<div style=\"display:table-row\">";
            echo "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\"><button class=\"btnEDIT\" onclick=\"javascript:manage_download.show(" . $row["id"] . ");\">" . $i++ . "</button></div>";
            echo "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\">" . $row["seq"] . "</div>";
            echo "<div style=\"width:70%;\" class=\"ps-2 border-bottom dp-data\">" . $row["link_name"] . "</div>";
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
        <section class="paging mt-2">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center" id="displayPaging">
                    <?php
                    echo ($max_page > 1 ? "<li class=\"page-item\"><a class=\"page-link\" href=\"?pageno=1&inSearch=$inSearch&status=$status\" title=\"หน้าแรกสุด\"><span aria-hidden=\"true\">&laquo;</span></a></li>" : "");
                    for ($i = 0; $i < $max_page; $i++) {
                        echo "<li class=\"page-item " . (($i + 1 == $pageno) ? "active" : "") . "\"><a class=\"page-link\" href=\"?pageno=" . ($i + 1) . "&inSearch=$inSearch&status=$status\">" . ($i + 1) . "</a></li>";
                    }
                    echo ($max_page > 1 ? "<li class=\"page-item\"><a class=\"page-link\" href=\"?pageno=" . $max_page . "&inSearch=$inSearch&status=$status\" title=\"หน้าสุดท้าย\"><span aria-hidden=\"true\">&raquo;</span></a></li>" : "");
                    ?>
                </ul>
            </nav>
        </section>
        <div class="spinner-border text-info" id="spinner"></div>
        <div id="showToast"></div>
        <?php
        $conn->close();
        include "inc/footer.php";
        ?>