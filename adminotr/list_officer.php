<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";
include "../include/util.php";

$numberRowPerPage = 15;
$max_page = 0;
$pageno = 1;
$totalrec = 0;
$inSearch = "";
$condition = "";
$condition1 = "";
$condition2 = "";
$condition3 = "";
$controlunit = "";
$status1 = "A";

if (isset($_GET["encryption"])) {
    try {    
        $intext = $_GET["encryption"];
        echo $intext;
        echo "<br>";
        $decrypt_msg = decryptmsg($intext);
        echo $decrypt_msg;
        echo "<br>";
        $decrypt_ary = explode("&", $decrypt_msg);
        $pageno = explode("=", $decrypt_ary[0])[1]; //$decrypt_ary[0];
        $controlunit = explode("=",$decrypt_ary[1])[1];
        $status1 = explode("=",$decrypt_ary[2])[1]; //$decrypt_ary[2];
        $inSearch = explode("=",$decrypt_ary[3])[1]; //$decrypt_ary[3];

        if ($controlunit == "A" || $controlunit=="") {
            $condition1 = "";
        } else {
            $condition1 = " AND controlunit='$controlunit'";
        }

        if ($status1 == "A") {
            $condition2 = "";
        } else {
            $condition2 = " AND status='$status1'";
        }

        if ($inSearch == "") {
            $condition3 = "";
        } else {
            $condition3 = " AND (position LIKE '%$inSearch%' OR controlunit LIKE '%$inSearch%' OR name LIKE '%$inSearch%' OR surname LIKE '%$inSearch%')";
        }
    } catch (Exception $e) {

    }

}

if (isset($_GET["pageno"])) {
    $pageno = $_GET["pageno"];
}

if (isset($_GET["status1"])) {
    $status1 = $_GET["status1"];
    if ($status1 == "A") {
        $condition2 = "";
    } else {
        $condition2 = " AND status='$status1'";
    }
}

if (isset($_GET["controlunit"])) {
    $controlunit = trim($_GET["controlunit"]);
    if ($controlunit == "A" || $controlunit=="") {
        $condition1 = "";
    } else {
        $condition1 = " AND controlunit='$controlunit'";
    }
}

if (isset($_GET["inSearch"])) {
    $inSearch = trim($_GET["inSearch"]);
    if ($inSearch == "") {
        $condition3 = "";
    } else {
        $condition3 = " AND (position LIKE '%$inSearch%' OR controlunit LIKE '%$inSearch%' OR name LIKE '%$inSearch%' OR surname LIKE '%$inSearch%')";
    }
}


// if (isset($_GET["inSearch"])) {
//     $inSearch = str_replace("'","",trim($_GET["inSearch"]));
//     if ($inSearch == "") {
//         $condition3 = "";
//     } else {
//         $condition3 = " AND (position LIKE '%$inSearch%' OR controlunit LIKE '%$inSearch%' OR name LIKE '%$inSearch%' OR surname LIKE '%$inSearch%')";
//     }
// }
$condition = $condition1 . $condition2 . $condition3;

$sql = "SELECT count(id) AS totalrec FROM officer WHERE 1 " . $condition;
// echo $pageno." ".$inSearch." ".$status1;
// echo $_SESSION['pageno']." ".$_SESSION['inSearch']." ".$_SESSION['status1'];
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalrec = $row["totalrec"];
    $max_page = ceil($totalrec / $numberRowPerPage);
}

?>

<h3>เจ้าหน้าที่ในสาขาวิชา</h3>
<div><button class="btn1" onclick="javascript:window.location.href='addeditofficer.php'">เพิ่มรายการ</button></div>
<div>
    <form method="GET" name="form0" id="form1" action="list_officer.php">
        <!-- <label for=inSearch>ค้นหา</label> -->
        <input type="text" id="inSearch" name="inSearch" value="<?php echo $inSearch ?>">

        <select id="controlunit" name="controlunit">
            <option value="A" selected>ทุกสาขา</option>
            <?php
            $sql = "SELECT id,nameth FROM department ORDER BY nameth";
            $result0 = $conn->query($sql);
            if ($result0 && $result0->num_rows > 0) {
                while ($row0 = $result0->fetch_assoc()) {
                    echo "<option value=\"" . $row0["nameth"] . "\"" . ($controlunit == $row0["nameth"] ? "selected" : "") . ">" . $row0["nameth"] . "</option>";
                }
            }
            echo "<option value = \"ผู้ตรวจการพยาบาล\"".($controlunit == "ผู้ตรวจการพยาบาล" ? "selected" : "").">ผู้ตรวจการพยาบาล</option>";
            echo "<option value = \"พยาบาลหัวหน้าหน่วยงาน\"".($controlunit == "พยาบาลหัวหน้าหน่วยงาน" ? "selected" : "").">พยาบาลหัวหน้าหน่วยงาน</option>";
            echo "<option value = \"หน่วยตรวจพิเศษอายุรกรรม\"".($controlunit == "หน่วยตรวจพิเศษอายุรกรรม" ? "selected" : "") .">หน่วยตรวจพิเศษอายุรกรรม</option>";
            echo "<option value = \"บุคลากรประจำกองอายุรกรรม\"".($controlunit == "บุคลากรประจำกองอายุรกรรม" ? "selected" : "").">บุคลากรประจำกองอายุรกรรม</option>";
            ?>

        </select>
        <select id="status1" name="status1">
            <option value="A" <?php echo ((trim($status1) == "A") ? "selected" : "") ?>>สถานะทั้งหมด</option>
            <option value="Y" <?php echo ((trim($status1) == "Y") ? "selected" : "") ?>>สถานะที่ยังปฏิบัติงานอยู่</option>
            <option value="N" <?php echo ((trim($status1) == "N") ? "selected" : "") ?>>สถานะไม่ได้ปฏิบัติงานแล้ว</option>
        </select>
        <?php
        $_SESSION['pageno'] = $pageno;
        $_SESSION['status1'] = $status1;
        $_SESSION['inSearch'] = $inSearch;
        ?>
        <button class="btnOK">ค้นหา</button>
    </form>
</div>
<?php
$sql = "SELECT id,seq,titlename,name,surname,position,controlunit,status FROM officer WHERE 1 " . $condition . " ORDER BY controlunit,position,seq,name,surname LIMIT " . (($pageno - 1) * $numberRowPerPage) . "," . $numberRowPerPage;
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        echo "<div class=\"mt-3\">จำนวนรายการข้อมูล " . $totalrec . " row(s) กำลังแสดงหน้าที่ $pageno/$max_page</div>";
?>
        <div class="d-table w-100 mt-1">
            <div style="display:table-row">
                <div style="width:10%;" class="ps-2 smallerFont head-row">แก้ไข</div>
                <div style="width:10%;" class="ps-2 smallerFont head-row">ลำดับแสดงผล</div>
                <div style="width:30%;" class="ps-2 smallerFont head-row">ชื่อ-นามสกุล</div>
                <div style="width:40%;" class="ps-2 smallerFont head-row">ตำแหน่ง-สาขา</div>
                <div style="width:10%;" class="ps-2 smallerFont head-row">สถานะ</div>
            </div>
    <?php
        $i = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<div style=\"display:table-row\">";
            echo "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\"><button class=\"btnEDIT\" onclick=\"javascript:window.location.href='addeditofficer.php?id=". $row["id"] . "'\">" . $i++ . "</button></div>";
            echo "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\">" . $row["seq"] . "</div>";
            echo "<div style=\"width:30%;\" class=\"ps-2 border-bottom dp-data\">" . $row["titlename"] . $row["name"] . " " . $row["surname"] . "</div>";
            echo "<div style=\"width:40%;\" class=\"ps-2 border-bottom dp-data\">" . $row["position"] . " " . ($row["controlunit"]==0 ? "" : $row["controlunit"]) . "</div>";
            echo "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\">" . ($row["status"] == "Y" ? "<span class=\"text-success\">ประจำการ</span>" : "<span class=\"text-danger\">ไม่ได้ประจำการ</span>") . "</div>";
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
                    $gofirstpage = encryptmsg("pageno=1&controlunit=$controlunit&status1=$status1&inSearch=$inSearch");
                    $golastpage = encryptmsg(($max_page > 1 ? "pageno=$max_page&controlunit=$controlunit&status1=$status1&inSearch=$inSearch" : ""));
                    echo ("<li class=\"page-item\"><a class=\"page-link\" href=\"?pageno=1&controlunit=$controlunit&status1=$status1&inSearch=$inSearch\" title=\"หน้าแรก\"><span aria-hidden=\"true\">&laquo;</span></a></li>");
                    //echo ("<li class=\"page-item\"><a class=\"page-link\" href=\"?encryption=" . $gofirstpage . "\" title=\"หน้าแรกสุด\"><span aria-hidden=\"true\">&laquo;</span></a></li>");
                    for ($i = 0; $i < $max_page; $i++) {
                        echo "<li class=\"page-item " . (($i + 1 == $pageno) ? "active" : "") . "\"><a class=\"page-link\" href=\"?pageno=" . ($i + 1) . "&controlunit=$controlunit&status1=$status1&inSearch=$inSearch\">" . ($i + 1) . "</a></li>";
                    }
                    echo ($max_page > 1 ? "<li class=\"page-item\"><a class=\"page-link\" href=\"?pageno=" . $max_page . "&controlunit=$controlunit&status1=$status1&inSearch=$inSearch\" title=\"หน้าสุดท้าย\"><span aria-hidden=\"true\">&raquo;</span></a></li>" : "");
                    //echo ("<li class=\"page-item\"><a class=\"page-link\" href=\"?encryption=" . $golastpage."\" title=\"หน้าสุดท้าย\"><span aria-hidden=\"true\">&raquo;</span></a></li>");
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