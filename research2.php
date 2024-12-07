<?php
include "include/header.php";
include "include/config.php";

$numRecPerPage = 10;
$max_page = 0;
$pageno = 1;
$total_rec = 0;
?>
<section class="rootnavigator mx-lg-5 mx-md-1 mx-0 p-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col"><a href="index.php">หน้าแรก</a>/งานวิจัย</div>
        </div>
    </div>
</section>
<section class="residentSection">
    <div class="container-fluid">
        <div class="row mx-lg-5 mx-md-1 mx-sm-0 template-2 pb-3 shadow-sm">
            <div class="col-12 mt-lg-3 mt-md-2">
                <h2 class="topic-header">งานวิจัยของแพทย์ประจำบ้าน</h2>
            </div>
            <?php
            $sql = "SELECT count(id) as Totalrec FROM research WHERE status='Y' AND group_name='งานวิจัยของแพทย์ประจำบ้าน'";
            $result0 = $conn->query($sql);
            if($result0 && $result0->num_rows > 0) {
                $row0 = $result0->fetch_assoc();
                $total_rec = $row0["Totalrec"];
                $max_page = ceil($total_rec/$numRecPerPage);

                $sql = "SELECT * FROM research WHERE status='Y' AND group_name='งานวิจัยของแพทย์ประจำบ้าน' ORDER BY create_date DESC,topic ";
                $result = $conn->query($sql);
                if ($result && $result->num_rows > 0) {
                    $total_rec_thispage = $result->num_rows;
                    $i = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class=\"col-12 mt-2 mb-2\">";
                        echo "<span class=\"topic-header\">".$row["name"]." อาจารย์ที่ปรึกษา ".$row["advisor"]."</span></div>";
                        echo "<div class=\"col-12 mt-2 mb-2\"><a href=\"researchdetail.php?id=".$row["id"]."\" title=\"\">".$row["topic"] . "</a></div>";
                        if($row["download_url"]!="") {
                            echo "<div class=\"col-12 mt-2 mb-2\"><a href=\"".$row["download_url"]."\" download>เอกสารงานวิจัย</a></div>";
                        }
                        if ($i++ < $total_rec_thispage) {
                            echo "<hr>";
                        }
                    }
                } else {
                    echo "<div class=\"col-12 mt-2 mb-2 text-center biggerFont\">ไม่มีข้อมูล</div>";
                }

            } else {
                echo "<div class=\"col-12 mt-2 mb-2 text-center biggerFont\">ไม่มีข้อมูล</div>";
            }

            ?>
        </div>
    </div>
</section>
<section class="paging">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center" id="displayPaging">
            <?php
            echo ($max_page > 1 ? "<li class=\"page-item\"><a class=\"page-link\" href=\"?pageno=1&typenews=\" title=\"หน้าแรกสุด\"><span aria-hidden=\"true\">&laquo;</span></a></li>":"");
            for ($i=0;$i<$max_page;$i++) {
                echo "<li class=\"page-item ".(($i+1==$pageno) ? "active":"")."\"><a class=\"page-link\" href=\"?pageno=".($i+1)."\">".($i+1)."</a></li>";
            }
            echo ($max_page > 1 ? "<li class=\"page-item\"><a class=\"page-link\" href=\"?pageno=".$max_page."\" title=\"หน้าสุดท้าย\"><span aria-hidden=\"true\">&raquo;</span></a></li>":"");
            ?>
            <!-- <li class="page-item disabled"><a class="page-link" href="#" title="หน้าแรกสุด"><span aria-hidden="true">&laquo;</span></a></li>
            <li class="page-item"><a class="page-link" href="#" title="ก่อนหน้า"><span aria-hidden="true">&lt;</span></a></li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#" title="หน้าถัดไป"><span aria-hidden="true">&gt;</span></a></li>
            <li class="page-item"><a class="page-link" href="#" title="หน้าสุดท้าย"><span aria-hidden="true">&raquo;</span></a></li> -->

        </ul>
    </nav>
</section>
<?php include "include/footer.php" ?>