<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";

$condition = "";

$search_text = "";
$status_text = "ALL";

$search_condition = "";
$status_condition = "";

if (isset($_POST["inSearch"])) {
    $search_text = str_replace("'","",trim($_POST["inSearch"]));
    if (!empty($search_text)) {
        $search_condition = " ( webpage_name LIKE '%" . $search_text . "%' OR content LIKE '%" . $search_text . "%' )";
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
$condition = $search_condition . $status_condition;
?>

<h3>หน้าเวปย่อย</h3>
<div><button class="btn1" onclick="javascript:manage_webpage.addWebPage();">เพิ่มหน้าเวปย่อย</button></div>
<div>
    <form method="POST" action="javascript:manage_webpage.list(1,$('#inSearch').val(),$('#status').val())">
        <!-- <label for=inSearch>ค้นหา</label> -->
        <input type="text" id="inSearch" name="inSearch" value="<?php echo $search_text ?>">
        <select id="status" name="status">
            <option value="Y">ใช้งาน</option>
            <option value="N">ไม่ใช้งาน</option>
            <option value="A" selected>ทั้งหมด</option>
        </select>
        <button class="btnOK">ค้นหา</button>
    </form>
</div>
<div class="mt-3">จำนวนรายการข้อมูล <span id="num_row"></span> row(s)</div>
<div class="d-table w-100 mt-1 list-webpage">
</div>
<br>
<section class="paging">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center" id="displayPaging">
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
<div id="showConfirm">
    <div>
        <div>
            <img src="../images/icon_ok.png">
        </div>
        <div></div>
        <div>
            <button onclick="showConfirm.confirm();" class="btnOK">ต้องการลบ</button> <button onclick="showConfirm.cancel();" class="btnCancel">ยกเลิก</button>
        </div>
    </div>
</div>
<div id="showToast"></div>
<script>
    $(function() {
        manage_webpage.list(1, "", "A");
    });
</script>
<?php
$conn->close();
include "inc/footer.php";
?>