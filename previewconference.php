<?php
include "include/config.php";
include "include/header.php";

$id = $_GET["id"];
$sql = "SELECT topic,detail,picture_URL,info1,info2,info3,info4 FROM tmp_news WHERE id=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

?>
<section class="rootnavigator mx-lg-5 mx-md-1 mx-0 p-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col"><a href="index.php">หน้าแรก</a>/หน้าตัวอย่าง</div>
        </div>
    </div>
</section>
<section class="conferenceSection">
    <div class="container-fluid">
        <div class="row mx-lg-5 mx-md-1 mx-sm-0 template-4 shadow-sm">
            <?php
            if ($result->num_rows > 0) {
                echo "<div class=\"col-12 mt-lg-2 mt-md-2\"><h2 class=\"topic-header\">{$row["topic"]}</h2></div>";
                echo "<div class=\"col-12 mb-3\">ประเภทงาน ".$row["info3"]."</div>";
                echo "<div class=\"col-12 mb-3\">จัดขึ้นเมื่อ ".($row["info1"]!="0000-00-00" && $row["info1"]!=null && $row["info1"]!="NaN undefinedNaN"?$row["info1"]:"-")." ".($row["info2"]!="0000-00-00" && $row["info2"]!=null && $row["info2"]!="NaN undefinedNaN"?" ถึงวันที่ ".$row["info2"]:"")."</div>";
                echo "<div class=\"col-12 mb-3\">สถานที่จัดงาน " . ($row["info4"] != null ? $row["info4"] : "-") . "</div>";
                echo "<div class=\"col-12 mb-3\"><img src=\"" . $row["picture_URL"] . "\"></div>";
                echo "<div class=\"col-12 mb-3 pb-3\">" . $row["detail"] . "</div>";
            } else {
                echo "<div class=\"col-12 mt-lg-2 mt-md-2\" style=\"height:400px\"><div class=\"center-content\"><h2 class=\"topic-header\">ไม่พบรายละเอียดข่าวหรือประกาศ</h2></div></div>";
            }
            ?>
        </div>
    </div>
</section>
<?php
include "include/footer.php";
$conn->close();
?>
<script>
    $(document).ready(() => {
        $(window).on('beforeunload', (event) => {
            cleartmp(<?php echo $id ?>);
        });
    });
</script>