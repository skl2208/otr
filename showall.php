<?php
session_start();

include "include/header.php";
include "include/config.php";

function myErrorHandler($errno, $errstr, $errfile, $errline) {
    echo "<script>window.location.href='errorpage.php';</script>";
}

// Set user-defined error handler function
set_error_handler("myErrorHandler");

$numberRowPerPage = 8;
$max_page = 0;
$pageno = 1;
$typenews = "";
$totalrec = 0;
if (isset($_GET["typenews"])) {
    $typenews =  (string)$_GET["typenews"];
}
if (isset($_GET["pageno"])) {
    $pageno =  (int)$_GET["pageno"];
}

try {
    if (isset($_GET["encryption"])) {
        //echo $_GET["encryption"]."<br>";
        $decryptmsg = decryptmsg($_GET["encryption"]);
        //echo $decryptmsg."<br>";
        $decryptary = explode("&", $decryptmsg);
        $pageno = explode("=", $decryptary[0])[1];
        //echo "pageno=$pageno<br>";
        $typenews = explode("=", $decryptary[1])[1];
        //echo "typenews=$typenews<br>";
    }

    $sql = "SELECT count(id) as totalrec FROM news WHERE typenews=? AND status='Y'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $typenews);
    $stmt->execute();
    $result0 = $stmt->get_result();
    if ($result0 && $result0->num_rows > 0) {
        $row0 = $result0->fetch_assoc();
        $totalrec = $row0["totalrec"];
    }
    $max_page = ceil($totalrec / $numberRowPerPage);
    $pageno = $pageno < 1 ? 1 : $pageno;
    $pageno = $pageno > $max_page ? $max_page : $pageno;
    $start_page = ($pageno - 1) * $numberRowPerPage;

    $sql = "SELECT topic,picture_URL,id,update_date FROM news WHERE typenews=? AND status='Y' ORDER BY update_date DESC LIMIT {$start_page},{$numberRowPerPage}";
    $stmt = $conn->prepare($sql);
    //echo "typenews=$typenews<br>";
    $stmt->bind_param('s', $typenews);
    $stmt->execute();
    $result = $stmt->get_result();

} catch (Exception $e) {
    throw new Exception($e->getMessage());
    echo "<script>window.location.href='errorpage.php';</script>";
}

?>
<section class="rootnavigator mx-lg-5 mx-md-1 mx-0 p-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col"><a href="index.php">หน้าแรก</a>/รายการทั้งหมด (<?php echo $totalrec . " รายการ" ?>)
                <?php
                if ($max_page > 0) {
                    echo " กำลังแสดงหน้าที่ $pageno/$max_page)</div>";
                }
                ?>
            </div>
        </div>
</section>
<section class="listTopic">
    <div class="container-fluid">
        <div class="row mx-lg-5 mx-md-1 mx-sm-0">
            <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                <h3><?php echo $typenews ?></h3>
            </div>
            <div class="news shadow-sm">
                <div class="container-fluid mx-0 px-lg-5 px-md-4 px-sm-0 py-lg-4 py-md-4 py-3">
                    <div class="row gx-3 listnews">
                        <?php
                        if ($result && $result->num_rows > 0) {
                            // output data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo "<div class=\"col-4 mt-2 mb-2\"><a href=\"shownewsdetail.php?id=" . $row["id"] . "\"><img src=\"" . $row["picture_URL"] . "\"></a></div>";
                                echo "<div class=\"col-8 mt-2 mb-2 border-bottom position-relative\">";
                                echo "<div class=\"center-content\"><a href=\"shownewsdetail.php?id=" . $row["id"] . "\"><h5>{$row["topic"]}</h5></a></div>";
                                echo "<div class=\"position-absolute text-end h-auto smallerFont\" style=\"bottom:5px;right:0;\">Update " . convertDBToThai($row["update_date"]) . "</div></div>";
                            }
                        } else {
                            echo "<div class=\"col-12 mt-lg-2 mt-md-2\" style=\"height:400px;position:relative\"><div class=\"center-content-info\"><h2 class=\"topic-header\">ไม่พบเนื้อหาหรือข้อความ</h2><br><img src=\"images/icon_character1.png\" style=\"height:300px;width:auto\"></div></div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<section class="paging">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center" id="displayPaging">
            <?php
            $gofirstpage = encryptmsg("pageno=1&typenews=" . $typenews);
            $golastpage = encryptmsg("pageno=" . $max_page . "&typenews=" . $typenews);
            //echo ($gofirstpage);
            //echo ($max_page > 1 ? "<li class=\"page-item\"><a class=\"page-link\" href=\"?pageno=1&typenews=".$typenews."\" title=\"หน้าแรกสุด\"><span aria-hidden=\"true\">&laquo;</span></a></li>":"");
            echo ($max_page > 1 ? "<li class=\"page-item\"><a class=\"page-link\" href=\"?encryption=$gofirstpage\" title=\"หน้าแรกสุด\"><span aria-hidden=\"true\">&laquo;</span></a></li>" : "");
            for ($i = 0; $i < $max_page; $i++) {
                $go_page = encryptmsg("pageno=" . ($i + 1) . "&typenews=$typenews");
                echo "<li class=\"page-item\"" . (($i + 1 == $pageno) ? "active" : "") . "\"><a class=\"page-link\" href=\"?encryption=$go_page\">" . ($i + 1) . "</a></li>";
            }
            //echo ($max_page > 1 ? "<li class=\"page-item\"><a class=\"page-link\" href=\"?pageno=".$max_page."&typenews=".$typenews."\" title=\"หน้าสุดท้าย\"><span aria-hidden=\"true\">&raquo;</span></a></li>":"");
            echo ($max_page > 1 ? "<li class=\"page-item\"><a class=\"page-link\" href=\"?encryption=$golastpage\" title=\"หน้าสุดท้าย\"><span aria-hidden=\"true\">&raquo;</span></a></li>" : "");
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
<?php
include "include/footer.php";
?>