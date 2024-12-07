<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";

$numberRowPerPage = 10;
$max_page = 0;
$pageno = 1;
$totalrec = 0;
$inSearch = "";
$condition = "";
$condition2 = "";
$condition3 = "";
$position = "";
$status1 = "A";

if (isset($_GET["pageno"])) {
    $pageno = $_GET["pageno"];
}

if (isset($_GET["status1"])) {
    $status1 = trim($_GET["status1"]);
    if ($status1 == "A") {
        $condition2 = "";
    } else {
        $condition2 = " AND status='$status1'";
    }
}
if (isset($_GET["inSearch"])) {
    $inSearch = str_replace("'","",trim($_GET["inSearch"]));
    if ($inSearch == "") {
        $condition3 = "";
    } else {
        $condition3 = " AND (position LIKE '%$inSearch%' OR name LIKE '%$inSearch%' OR surname LIKE '%$inSearch%')";
    }
}
$condition = $condition2 . $condition3;

$sql = "SELECT count(id) AS totalrec FROM resident_group WHERE 1 " . $condition;
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalrec = $row["totalrec"];
    $max_page = ceil($totalrec / $numberRowPerPage);
}

?>

<h3>แพทย์ประจำบ้าน / แพทย์ประจำบ้านต่อยอด</h3>
<div>
    <button class="btn1" onclick="javascript:window.location.href='addeditresidentgroup.php'">เพิ่มรายการ</button>
</div>
<div>
    <form method="GET" name="form0" id="form1" action="list_resident_group.php">
        <!-- <label for=inSearch>ค้นหา</label> -->
        <input type="text" id="inSearch" name="inSearch" value="<?php echo $inSearch ?>">
        <select id="status1" name="status1">
            <option value="A" <?php echo ((trim($status1) == "A") ? "selected" : "") ?>>สถานะทั้งหมด</option>
            <option value="Y" <?php echo ((trim($status1) == "Y") ? "selected" : "") ?>>สถานะที่ยังปฏิบัติงานอยู่</option>
            <option value="N" <?php echo ((trim($status1) == "N") ? "selected" : "") ?>>สถานะไม่ได้ปฏิบัติงานแล้ว</option>
        </select>
        <button class="btnOK">ค้นหา</button>
    </form>
</div>
<?php
$sql = "SELECT * FROM resident_group WHERE 1 " . $condition . " ORDER BY group_name LIMIT " . (($pageno - 1) * $numberRowPerPage) . "," . $numberRowPerPage;
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        echo "<div class=\"mt-3\">จำนวนรายการข้อมูล " . $totalrec . " row(s) กำลังแสดงหน้าที่ $pageno/$max_page</div>";
?>
        <div class="d-table w-100 mt-1">
            <div style="display:table-row">
                <div style="width:10%;" class="ps-2 smallerFont head-row">แก้ไข</div>
                <div style="width:70%;" class="ps-2 smallerFont head-row">ชื่อกลุ่ม</div>
                <div style="width:10%;" class="ps-2 smallerFont head-row">Download</div>
                <div style="width:10%;" class="ps-2 smallerFont head-row">สถานะ</div>
            </div>
    <?php
        $i = 1;
        while ($row = $result->fetch_assoc()) {
            $str_image_download = "";
            if($row["download_url"] != "") {
                $str_image_download = "<a href=\"".$row["download_url"]."\" target=\"_blank\"><img src=\"../images/icon_document.png\" style=\"height:25px\" title=\"\" alt=\"\"></a>";
            }
            echo "<div style=\"display:table-row\">";
            echo "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\"><button class=\"btnEDIT\" onclick=\"javascript:window.location.href='addeditresidentgroup.php?id=". $row["id"] . "'\">" . $i++ . "</button></div>";
            echo "<div style=\"width:70%;\" class=\"ps-2 border-bottom dp-data\">" . $row["group_name"] . "</div>";
            echo "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\">" . $str_image_download . "</div>";
            echo "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\">" . ($row["status"] == "Y" ? "<span class=\"text-success\">Active</span>" : "<span class=\"text-danger\">Inactive</span>") . "</div>";
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
                    echo ($max_page > 1 ? "<li class=\"page-item\"><a class=\"page-link\" href=\"?pageno=1&position=$position&status1=$status1&inSearch=$inSearch\" title=\"หน้าแรกสุด\"><span aria-hidden=\"true\">&laquo;</span></a></li>" : "");
                    for ($i = 0; $i < $max_page; $i++) {
                        echo "<li class=\"page-item " . (($i + 1 == $pageno) ? "active" : "") . "\"><a class=\"page-link\" href=\"?pageno=" . ($i + 1) . "&position=$position&status1=$status1&inSearch=$inSearch\">" . ($i + 1) . "</a></li>";
                    }
                    echo ($max_page > 1 ? "<li class=\"page-item\"><a class=\"page-link\" href=\"?pageno=" . $max_page . "&position=$position&status1=$status1&inSearch=$inSearch\" title=\"หน้าสุดท้าย\"><span aria-hidden=\"true\">&raquo;</span></a></li>" : "");
                    ?>
                </ul>
            </nav>
        </section>
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