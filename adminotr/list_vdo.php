<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";
?>

<h3>VDO Clip</h3>
<div><button class="btn1" onclick="javascript:addeditvdo();">เพิ่มรายการ</button></div>
<div>
    <form method="POST" action="javascript:blind_list_vdo(1,$('#searchTxt').val(),$('#select_status').val(),$('#select_catagory').val());">
        <!-- <label for=searchTxt>ค้นหา</label> -->
        <input type="text" id="searchTxt" name="searchTxt" value="">
        <select id="select_catagory" name="select_catagory">
            <option value="">แสดงทั้งหมด</option>
            <?php
            $sql = "SELECT catagory,id FROM vdo_group WHERE status='Y' ORDER BY catagory";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                $selected_text = "";
                while ($row = $result->fetch_assoc()) {
                    echo "<option value=\"" . $row["id"] . "\" " . $selected_text . ">" . $row["catagory"] . "</option>";
                }
            }
            ?>
        </select>
        <select id="select_status" name="select_status">
            <option value="Y">เฉพาะที่แสดง</option>
            <option value="N">เฉพาะไม่แสดง</option>
            <option value="A" selected>ทั้งหมด</option>
        </select>
        <input type="hidden" name="pageno" id="pageno" value="1">
        <button class="btnOK">ค้นหา</button>
    </form>
</div>
<div class="mt-3">จำนวนรายการข้อมูล <span id="num_row"></span> row(s)</div>
<div class="d-table w-100 mt-1 list-vdo">
</div>
<br>
<section class="paging">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center" id="displayPaging">
        </ul>
    </nav>
</section>
<div class="spinner-border text-info" id="spinner"></div>
<div id="showToast"></div>
<script>
    $(function() {

        const p = document.getElementById("pageno");
        const s = document.getElementById("searchTxt");
        const ss = document.getElementById("select_status");
        const c = document.getElementById("catagory");

        blind_list_vdo(1,"","A","");
    });
</script>
<?php
$conn->close();
include "inc/footer.php";
?>