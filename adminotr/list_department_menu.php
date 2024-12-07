<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";
?>
<script>
    $(function() {
        manage_department_menu.list(1);
    });
</script>
<h3>จัดการเมนู สาขาวิชา</h3>
<div><button class="btn1" onclick="javascript:manage_department_menu.add(2);">เพิ่มเมนู</button></div>
<div class="mt-3" id="displayTotalRec"></div>
<div class="d-table w-100 mt-1 list-department-menu">
    <div style="display:table-row">
        <div style="width:10%;" class="ps-2 smallerFont head-row">แก้ไข</div>
        <div style="width:10%;" class="ps-2 smallerFont head-row">ลำดับแสดงผล</div>
        <div style="width:40%;" class="ps-2 smallerFont head-row">ชื่อเมนู</div>
        <div style="width:10%;" class="ps-2 smallerFont head-row">สถานะ</div>
    </div>
</div>
<section class="paging mt-2">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center" id="displayPaging">
        </ul>
    </nav>
</section>
<div class="spinner-border text-info" id="spinner"></div>
<div id="showToast"></div>
<?php
$conn->close();
include "inc/footer.php";
?>