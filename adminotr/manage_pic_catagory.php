<?php
session_start();
include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";

$numberRowPerPage = 10;
$max_page = 0;
$pageno = 1;
$totalrec = 0;
$search_text = "";
$condition = "";

if (isset($_POST["inSearch"])) {
    $search_text = str_replace("'","",trim($_POST["inSearch"]));
    $condition = " AND (catagory LIKE '%$search_text%' OR catagory_desc LIKE '%$search_text%')";
}

if (isset($_GET["pageno"])) {
    $pageno =  $_GET["pageno"];
}
$sql = "SELECT count(id) AS totalrec FROM pic_catagory";
$result0 = $conn->query($sql);
if ($result0 && $result0->num_rows > 0) {
    $row0 = $result0->fetch_assoc();
    $totalrec = $row0["totalrec"];
    $max_page = ceil($totalrec / $numberRowPerPage);
}
?>
<section id="main" class="position-relative">
    <h3>หมวดภาพ</h3>
    <div><button class="btn1" onclick="javascript:manage_pic_catagory.edit();">เพิ่มรายการ</button></div>
    <div>
        <form method="POST" action="">
            <input type="text" id="inSearch" name="inSearch" value="<?php echo $search_text ?>">
            <button class="btnOK">ค้นหา</button>
            <span class="smallerFont">คำค้นหา <?php echo (($search_text == "") ? "ทั้งหมด" : $search_text) ?></span>
        </form>
    </div>
    <?php
    $sql = "SELECT id,catagory,catagory_desc,status FROM pic_catagory WHERE 1 " . $condition . " ORDER BY catagory LIMIT " . (($pageno - 1) * $numberRowPerPage) . "," . $numberRowPerPage;
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {

        echo "<div class=\"mt-3\">จำนวนรายการข้อมูล " . $totalrec . " row(s) กำลังแสดงหน้าที่ $pageno/$max_page</div>";
    ?>
        <div class="d-table w-100 mt-1">
            <div style="display:table-row">
                <div style="width:10%;" class="ps-2 smallerFont head-row">แก้ไข</div>
                <div style="width:50%;" class="ps-2 smallerFont head-row">ชื่อหมวด</div>
                <div style="width:35%;" class="ps-2 smallerFont head-row">คำอธิบาย</div>
                <div style="width:5%;" class="ps-2 smallerFont head-row"></div>
            </div>
        <?php
        $i = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<div style=\"display:table-row\">";
            echo "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\"><button class=\"btnEDIT\" onclick=\"javascript:manage_pic_catagory.edit('" . $row["id"] . "');\">" . $i++ . "</button></div>";
            echo "<div style=\"width:50%;\" class=\"ps-2 border-bottom dp-data\">" . $row["catagory"] . "</div>";
            echo "<div style=\"width:35%;\" class=\"ps-2 border-bottom dp-data\">" . $row["catagory_desc"] . "</div>";
            echo "<div style=\"width:5%;\" class=\"ps-2 border-bottom dp-data\"></div>";
            echo "</div>";
        }
    } else {
        echo "<div class=\"mt-3 biggerFont\"><span class=\"info-warn-font\">ไม่มีข้อมูล</span></div>";
    }
        ?>
        </div>
        <section class="paging">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center" id="displayPaging">
                    <?php
                    echo ($max_page > 1 ? "<li class=\"page-item\"><a class=\"page-link\" href=\"?pageno=1\" title=\"หน้าแรกสุด\"><span aria-hidden=\"true\">&laquo;</span></a></li>" : "");
                    for ($i = 0; $i < $max_page; $i++) {
                        echo "<li class=\"page-item " . (($i + 1 == $pageno) ? "active" : "") . "\"><a class=\"page-link\" href=\"?pageno=" . ($i + 1) . "\">" . ($i + 1) . "</a></li>";
                    }
                    echo ($max_page > 1 ? "<li class=\"page-item\"><a class=\"page-link\" href=\"?pageno=" . $max_page . "\" title=\"หน้าสุดท้าย\"><span aria-hidden=\"true\">&raquo;</span></a></li>" : "");
                    ?>
                </ul>
            </nav>
        </section>
</section>
<section id="editContent2">
<h3>หมวดภาพ</h3>
<form method="POST" name="formedit" id="formedit">
        <div class="d-table w-100 mt-3">
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">ชื่อหมวด</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="หัวข้อข่าว" class="w-75" title="" id="catagory" name="catagory" value=""></div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">คำอธิบายหมวด</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="คำค้นหา" class="w-75" title="" id="catagory_desc" name="catagory_desc" value=""></div>
            </div>
        </div>
        <div class="d-table w-100 mt-3">
            <div class="ps-2 text-center d-table-cell">
                <button class="btnOK" onclick="manage_pic_catagory.save(document.forms['formedit']);"><img src="../images/icon_save.png">บันทึก</button>
                <button type="button" class="btnCancel" onclick="manage_pic_catagory.cancel();"><img src="../images/ic_close.png"> ยกเลิก</button>
            </div>
        </div>
        <input type="hidden" id="status" name="status" value="">
        <input type="hidden" id="command" name="command" value="">
        <input type="hidden" id="id" name="id" value="">
    </form>
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
<script>
    
    $(function(){
        var msgTxt = window.localStorage.getItem("msgtxt");
        if(msgTxt!="" && msgTxt != null && msgTxt != undefined) {
            const msg1 = JSON.parse(msgTxt);
            showToast(msg1.text,msg1.type);
            localStorage.removeItem("msgtxt");
        }
    });
</script>
<?php
$conn->close();
?>