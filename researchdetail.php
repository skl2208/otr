<?php
include "include/header.php";
include "include/config.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];
} else {
    $id = 0;
}
$sql = "SELECT * FROM research WHERE status='Y' AND id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i',$id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if ($result->num_rows > 0) {
    $topic_head = $row["topic"];
} else {
    $topic_head = "...";
}
?>
<section class="rootnavigator mx-lg-5 mx-md-1 mx-0 p-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <a href="index.php">หน้าแรก</a>/<a href="research_list.php">งานวิจัย</a>/
                    <?php echo(strlen($topic_head) > 150 ? trim(substr($topic_head,0,150))."..." : trim($topic_head)) ?>
                </a>
            </div>
        </div>
    </div>
</section>
<section class="residentSection">
    <div class="container-fluid">
        <div class="row mx-lg-5 mx-md-1 mx-sm-0 template-2 pb-3 shadow-sm">

            <?php
            $sql = "SELECT * FROM research WHERE status='Y' AND id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i',$id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "<div class=\"col-12 mt-lg-3 mt-md-2\"><h2 class=\"topic-header\">".$row["group_name"]."</h2></div>";
                echo "<div class=\"col-12 mt-2 mb-2 topic-header\"><h3>" . $row["topic"] . "</h3></div>";
                echo "<div class=\"col-12 mt-2 mb-2\"><span class=\"smallerFont\">" . $row["name"] . "</span></div><hr>";
                echo "<div class=\"col-12 mt-2 mb-2\">" . $row["content"] . "</div>";
                if ($row["download_url"] != "") {
                    echo "<div class=\"col-12 mt-2 mb-2\"><a href=\"" . $row["download_url"] . "\" download>เอกสารงานวิจัย</a></div>";
                }

            } else {
                echo "<div class=\"col-12 mt-lg-2 mt-md-2\" style=\"height:400px;position:relative\"><div class=\"center-content-info\"><h2 class=\"topic-header\">ไม่พบเนื้อหาหรือข้อความ</h2><br><img src=\"images/icon_character1.png\" style=\"height:300px;width:auto\"></div></div>";
            }
            ?>
        </div>
    </div>
</section>
<?php include "include/footer.php" ?>