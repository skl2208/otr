<?php
include "include/config.php";
include "include/header.php";

$id = $_GET["id"];
$sql = "SELECT detail FROM tmp_news WHERE id=$id";
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
<section class="detailSection">
    <div class="container-fluid">
        <div class="row mx-lg-5 mx-md-1 mx-sm-0 template-4 shadow-sm">
            <?php
            if ($result->num_rows > 0) {
                echo "<div class=\"col-12 mt-lg-5 mt-md-5\"></div>";
                echo "<div class=\"col-12 mb-3 pb-3\">" . $row["detail"] . "</div>";
            } else {
                echo "<div class=\"col-12 mt-lg-2 mt-md-2\" style=\"height:400px\"><div class=\"center-content\"><h2 class=\"topic-header\">ไม่พบรายละเอียดเนื้อหาเวปเพจ</h2></div></div>";
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