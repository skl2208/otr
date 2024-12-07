<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";

$numRecPerPage = 10;
$total_rec = 0;
$max_page = 0;
$pageno = 1;

$search_text = "";
$status_text = "ALL";
$select_text = "";
$condition = " WHERE 1 ";

$search_condition = "";
$select_condition = "";
$status_condition = "";

if(isset($_GET["pageno"])) {
    $pageno = $_GET["pageno"];
}
if (isset($_GET["inSearch"])) {
    $search_text = str_replace("'","",trim($_GET["inSearch"]));
    if (!empty($search_text)) {
        $search_condition = " AND (topic LIKE '%" . $search_text . "%' OR content LIKE '%" . $search_text . "%' OR name LIKE '%$search_text%')";
    }
}
if (isset($_GET["select_group"])) {
    $select_text = $_GET["select_group"];
    if ($_GET["select_group"] == "") {
        $select_condition = "";
    } else {
        $select_condition = " AND group_name='" . trim($_GET["select_group"]) . "'";
    }
}
if (isset($_GET["status"])) {
    if ($_GET["status"] == "ALL") {
        $status_text = "ALL";
        $status_condition = "";
    } else {
        $status_text = $_GET["status"];
        $status_condition = " AND status='" . $_GET["status"] . "'";
    }
}
$condition = $condition . $search_condition . $select_condition . $status_condition;
?>
<h3>งานวิจัย</h3>
<div><button class="btn1" onclick="javascript:window.location.href='addeditresearch.php'">เพิ่มรายการ</button></div>
<div>
    <form method="GET" action="list_research.php">
        <!-- <label for=inSearch>ค้นหา</label> -->
        <input type="text" id="inSearch" name="inSearch" value="<?php echo $search_text ?>">
        <select id="select_group" name="select_group">
            <option value="" <?php echo ($select_text == "" ? "selected" : "") ?>>แสดงทั้งหมด</option>
            <?php
            $sql = "SELECT group_name FROM research_group";
            $res0 = $conn->query($sql);
            if ($res0 && $res0->num_rows > 0) {
                while ($row0 = $res0->fetch_assoc()) {
                    echo "<option value=\"" . $row0["group_name"] . "\"" . ($select_text == $row0["group_name"] ? "selected" : "") . ">" . $row0["group_name"] . "</option>";
                }
            }
            ?>
        </select>
        <select id="status" name="status">
            <option value="Y" <?php echo ($status_text == "Y" ? "selected" : "") ?>>เฉพาะแสดงบนเวป</option>
            <option value="N" <?php echo ($status_text == "N" ? "selected" : "") ?>>เฉพาะไม่ได้แสดงบนเวป</option>
            <option value="ALL" <?php echo ($status_text == "ALL" ? "selected" : "") ?>>ทั้งหมด</option>
        </select>
        <button class="btnOK">ค้นหา</button>
        <span class="smallerFont">คำค้นหา <?php echo (($search_text == "") ? "ทั้งหมด" : $search_text) ?></span>
    </form>
</div>
<?php

$sql = "SELECT COUNT(id) as totalrec FROM research " . $condition;
$result0 = $conn->query($sql);
if ($result0 && $result0->num_rows > 0) {
    $row1 = $result0->fetch_assoc();
    $total_rec = $row1["totalrec"];
    $max_page = ceil($total_rec / $numRecPerPage);
}
if ($total_rec != 0) {
    echo "จำนวนรายการ $total_rec รายการ กำลังแสดงหน้าที่ $pageno/$max_page";
} else {
    echo "";
}

$sql = "SELECT * FROM research " . $condition . " ORDER BY create_date DESC,group_name,topic LIMIT ".($pageno-1)*$numRecPerPage.",".$numRecPerPage;
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
?>
        <div class="d-table w-100 mt-1">
            <div style="display:table-row">
                <div style="width:5%;" class="ps-2 smallerFont head-row">แก้ไข</div>
                <div style="width:55%;" class="ps-2 smallerFont head-row">ชื่องานวิจัย</div>
                <div style="width:20%;" class="ps-2 smallerFont head-row">เจ้าของงานวิจัย</div>
                <div style="width:10%;" class="ps-2 smallerFont head-row">ประเภท</div>
                <div style="width:10%;" class="ps-2 smallerFont head-row">สถานะ</div>
            </div>
    <?php
        $i = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<div style=\"display:table-row\">";
            echo "<div style=\"width:5%;\" class=\"ps-2 border-bottom dp-data\"><button class=\"btnEDIT\" onclick=\"javascript:window.location.href='addeditresearch.php?id=" . $row["id"] . "';\">>></button></div>";
            echo "<div style=\"width:55%;\" class=\"ps-2 border-bottom dp-data smallerFont\">" . $row["topic"] . "</div>";
            echo "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\">" . $row["name"] . "</div>";
            echo "<div style=\"width:20%;\" class=\"ps-2 border-bottom dp-data\">" . $row["group_name"] . "</div>";
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
                    echo ($max_page > 1 ? "<li class=\"page-item\"><a class=\"page-link\" href=\"?pageno=1&inSearch=$search_text&status=$status_text&select_group=$select_text\" title=\"หน้าแรกสุด\"><span aria-hidden=\"true\">&laquo;</span></a></li>" : "");
                    for ($i = 0; $i < $max_page; $i++) {
                        echo "<li class=\"page-item " . (($i + 1 == $pageno) ? "active" : "") . "\"><a class=\"page-link\" href=\"?pageno=" . ($i + 1) . "&inSearch=$search_text&status=$status_text&select_group=$select_text\">" . ($i + 1) . "</a></li>";
                    }
                    echo ($max_page > 1 ? "<li class=\"page-item\"><a class=\"page-link\" href=\"?pageno=" . $max_page . "&inSearch=$search_text&status=$status_text&select_group=$select_text\" title=\"หน้าสุดท้าย\"><span aria-hidden=\"true\">&raquo;</span></a></li>" : "");
                    ?>
                </ul>
            </nav>
        </section>
        <div id="showToast"></div>
        <?php
        $conn->close();
        include "inc/footer.php";
        ?>