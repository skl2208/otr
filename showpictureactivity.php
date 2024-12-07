<?php
include "include/header.php";
include "include/config.php";

$pageno = (isset($_GET["pageno"]) ? $_GET["pageno"] : 1);
$typeactivity = (isset($_GET["typeactivity"]) ? $_GET["typeactivity"] : "");
$displayHead = ($typeactivity != "" ? $typeactivity : "ภาพกิจกรรมทั้งหมด");
$condition = "";
if ($typeactivity != "") {
    $condition = " AND catagory='$typeactivity'";
}
$total_record = 0;
$numberRecPerPage = 12;
$max_page = 0;

$sql = "SELECT count(id) as Totalrec FROM pic_activity WHERE status='Y' $condition";
$result0 = $conn->query($sql);
if ($result0 && $result0->num_rows > 0) {
    $row0 = $result0->fetch_assoc();
    $total_record = $row0["Totalrec"];

    $max_page = ceil($total_record / $numberRecPerPage);

    $sql = "SELECT * FROM pic_activity WHERE status='Y' $condition ORDER BY create_date DESC LIMIT " . ($pageno - 1) * $numberRecPerPage . "," . $numberRecPerPage;
    $result = $conn->query($sql);
}

?>
<section class="rootnavigator mx-lg-5 mx-md-1 mx-0 p-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col"><a href="index.php">หน้าแรก</a>/แสดงภาพกิจกรรม</div>
        </div>
    </div>
</section>
<section class="showpicActivity">
    <div class="container-fluid">
        <div class="row mx-lg-5 mx-md-1 mx-sm-0 template-3 shadow-sm">
            <div class="col-12 mt-3">
                <h3><?php echo $displayHead?></h3>
                จำนวนรายการ <?php echo $total_record ?> กำลังแสดงข้อมูลหน้าที่ <?php echo $pageno . "/" . $max_page ?>
            </div>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class=\"col-lg-4 col-md-4 col-sm-6 mt-3 overflow-hidden position-relative\">";
                    echo "<div class=\"h-100 a2\">";
                    echo "<a href=\"javascript:showFullPicture('" . $row["image_url"] . "');\">";
                    echo "<img src=\"" . $row["image_url"] . "\" title=\"" . $row["catagory"] . "\" class=\"picture-portriat\" onload=\"checkImage(this);\">";
                    echo "</a></div></div>";
                }
            } else {
                echo "<div class=\"col-lg-12 col-md-12 col-sm-12 mt-3 overflow-hidden position-relative\">";
                echo "<div class=\"center-content\">ไม่มีภาพกิจกรรม</div></div>";
            }
            ?>
        </div>
    </div>
</section>
<section class="paging mt-3">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center" id="displayPaging">
            <?php
            echo ($max_page > 1 ? "<li class=\"page-item\"><a class=\"page-link\" href=\"?pageno=1\" title=\"หน้าแรกสุด\"><span aria-hidden=\"true\">&laquo;</span></a></li>":"");
            for ($i=0; $i < $max_page; $i++) {
                echo "<li class=\"page-item ".(($i+1==$pageno) ? "active":"")."\"><a class=\"page-link\" href=\"?pageno=".($i+1)."\">".($i+1)."</a></li>";
            }
            echo ($max_page > 1 ? "<li class=\"page-item\"><a class=\"page-link\" href=\"?pageno=".$max_page."\" title=\"หน้าสุดท้าย\"><span aria-hidden=\"true\">&raquo;</span></a></li>":"");
            ?>
        </ul>
    </nav>
</section>
<div class="showFullPicture" style="display:none">
        <div>
            <img src title="" id="pictureFull">
            <div>
                <a href="javascript:showFullPicture('close');">X</a>
            </div>
        </div>
    </div>
<?php
$conn->close();
include "include/footer.php";
?>