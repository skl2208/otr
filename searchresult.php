<?php
include "include/config.php";
include "include/header.php";

$search = (isset($_GET["search"]) ? $_GET["search"] : "");
$pageno = strval(isset($_GET["pageno"]) ? $_GET["pageno"] : 1);
$total_rec = 0;
$numRowsPerPage = 10;
$max_page = 0;

if ($search != "") {
    $original_search = $search;
    $search = str_replace("'","",$search);
    $search = '%'.trim($search).'%';

    //$sql = "SELECT (SELECT COUNT(topic) FROM news WHERE status='Y' AND (keyword LIKE '%?%' OR topic LIKE '%?%' OR detail LIKE '%?%' )) as table1Count, (SELECT COUNT(topic) FROM research WHERE status='Y' AND (topic LIKE '%?%' OR content LIKE '%?%' OR name LIKE '%?%' )) as table2Count";
    $sql = "SELECT (SELECT COUNT(topic) FROM news WHERE status='Y' AND (keyword LIKE ? OR topic LIKE ? OR detail LIKE ? )) as table1Count, (SELECT COUNT(topic) FROM research WHERE status='Y' AND (topic LIKE ? OR content LIKE ? OR name LIKE ? )) as table2Count";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssss',$search,$search,$search,$search,$search,$search);
    $stmt->execute();
    $result = $stmt->get_result();
    $pageno = strip_tags($pageno);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total_rec = $row["table1Count"] + $row["table2Count"];

        $max_page = ceil($total_rec / $numRowsPerPage);
        $pageno = $pageno<1?1:$pageno;
        $pageno = $pageno>$max_page?$max_page:$pageno;

        //$sql = "SELECT * FROM news WHERE status='Y' AND (keyword LIKE '%$search%' OR topic LIKE '%$search%' OR detail LIKE '%$search%') ORDER BY update_date DESC,create_date DESC LIMIT " . ($pageno - 1) * $numRowsPerPage . "," . $numRowsPerPage;
        $sql = "SELECT topic,detail,id,'news' as typeresult,update_date FROM news WHERE status='Y' AND (keyword LIKE '%$search%' OR topic LIKE '%$search%' OR detail LIKE '%$search%' ) UNION ";
        $sql = $sql . "SELECT topic,content,id,'research' as typeresult,update_date FROM research WHERE status='Y' AND (topic LIKE '%$search%' OR content LIKE '%$search%' OR name LIKE '%$search%') ";
        $sql = $sql . " ORDER BY update_date DESC LIMIT " . ($pageno - 1) * $numRowsPerPage . "," . $numRowsPerPage;
        $result1 = $conn->query($sql);
    }
} else {
}
?>

<section class="rootnavigator mx-lg-5 mx-md-1 mx-0 p-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col"><a href="index.php">หน้าแรก</a>/LINK</div>
        </div>
    </div>
</section>
<section class="searchResult shadow-sm">
    <div class="container-fluid">
        <div class="row template-4">
            <div class="col-lg-12 col-md-12 col-sm-12 mt-5" id="displayHeader">
                <h3>ผลการค้นหา</h3>
            </div>
            <article id="displayCaption">
                <div class="mt-3 mb-2">
                    <?php
                    
                    if ($total_rec == 0) {
                        $report_outcome = "ไม่พบผลการค้นหาคำ <span class=\"font-bold\">$original_search</span>";
                    } else {
                        $report_outcome = "ผลการค้นหาคำ <span class=\"font-bold\">$original_search</span> พบจำนวน $total_rec รายการ กำลังแสดงหน้าที่ $pageno/$max_page";
                    }
                    echo strip_tags($report_outcome);
                    ?>

                </div>
            </article>
            <div class="col-lg-12 col-md-12 col-sm-12 mt-3 p-0 searchResult-item" id="displayListItems">
                <ul>
                    <?php
                    if ($result1 && $result1->num_rows > 0) {
                        while ($rowData = $result1->fetch_assoc()) {
                            if ($rowData["typeresult"] == "news") {
                                $headline = "ข่าว";
                                $fullURL = $baseHTTP . $baseURL . "shownewsdetail.php?id=" . $rowData["id"];
                            } else if ($rowData["typeresult"] == "research") {
                                $headline = "งานวิจัย";
                                $fullURL = $baseHTTP . $baseURL . "researchdetail.php?id=" . $rowData["id"];
                            }

                            echo "<li><a href=\"".$fullURL . $rowData["id"] . "\" target=\"_blank\" title=\"\">";
                            echo "<h5>$headline {$rowData["topic"]}</h5>";

                            echo "<p>$fullURL</p></a>";

                            $someDetail = strip_tags($rowData["detail"]);
                            $someDetail = substr($someDetail, strrpos($someDetail, $search), 500) . "...";
                            $someDetail = str_replace($search, "<strong>" . $search . "</strong>", $someDetail);
                            // เอารูปที่มีอยู่ข้างในออกให้หมด
                            // เอาข้อความบางส่วนมาเท่านั้น ประมาณ 200 ตัว
                            echo "<p>" . $someDetail . "</p></li>";
                        }
                    } else {
                        echo "<br><br><br><span class=\"topic-header font-warning\">ไม่พบข้อมูลตามเงื่อนไขที่ค้นหา กรุณาลองค้นหาใหม่อีกครั้ง</span>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</section>
<section class="paging">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center" id="displayPaging">
            <?php
            echo ($max_page > 1 ? "<li class=\"page-item\"><a class=\"page-link\" href=\"?pageno=1&search=$search\" title=\"หน้าแรกสุด\"><span aria-hidden=\"true\">&laquo;</span></a></li>" : "");
            for ($i = 0; $i < $max_page; $i++) {
                echo "<li class=\"page-item " . (($i + 1 == $pageno) ? "active" : "") . "\"><a class=\"page-link\" href=\"?pageno=" . ($i + 1) . "&search=$search\">" . ($i + 1) . "</a></li>";
            }
            echo ($max_page > 1 ? "<li class=\"page-item\"><a class=\"page-link\" href=\"?pageno=" . $max_page . "&search=$search\" title=\"หน้าสุดท้าย\"><span aria-hidden=\"true\">&raquo;</span></a></li>" : "");
            ?>

        </ul>
    </nav>
</section>
<section class="footer-sect shadow-sm">
    <div class="container-fluid mt-4 mb-4">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <ul>แผนกอายุรกรรม โรงพยาบาลภูมิพล
                    <li>ข่าวกิจกรรม</li>
                    <li>ประชาสัมพันธ์</li>
                    <li>ประกาศ</li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <ul>ตารางเวรแพทย์<li>ตารางเวรแพทย์ในเวลา</li>
                    <li>ตารางเวรแพทย์นอกเวลา</li>
                </ul>

            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <ul>ดาวน์โหลด<li>แบบฟอร์ม 1</li>
                    <li>แบบฟอร์ม 2</li>
                </ul>

            </div>
            <div class="col-lg-4 col-md-4 col-sm-12"></div>
            <div class="col-lg-4 col-md-4 col-sm-12" style="border-top:1px solid white"></div>
            <div class="col-lg-4 col-md-4 col-sm-12"></div>
            <div class="col-12 text-center mt-4"><a href=""><img src="images/fb_logo.png"></a><a href="#"><img src="images/line_logo.png"></a><a href="#"><img src="images/twitter_logo.png"></a><a href="#"><img src="images/youtube_logo.png"></a><br>&copy; โรงพยาบาลภูมิพลอดุลยเดช
                กรมแพทย์ทหารอากาศ All
                Rights Reserved<br>
                171 กองอายุรกรรมชั้น 4 อาคาร คุ้มเกล้า โรงพยาบาลภูมิพลอดุลยเดช แขวงคลองถนน เขตสายไหม กทม. 10220<br>
                e-mail: </div>
        </div>

    </div>
</section>
</body>

</html>