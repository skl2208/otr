<?php
include "include/header.php";
include "include/config.php";


$id = 0;
if(isset($_GET["id"])){
    $id=$_GET["id"];
}
$sql = "SELECT topic_confer,detail,place,picture_URL,type_confer,start_confer_date,end_confer_date FROM conference WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if ($result->num_rows > 0) {
    $topic_head = $row["topic_confer"];
} else {
    $topic_head = "...";
}
?>
<section class="rootnavigator mx-lg-5 mx-md-1 mx-0 p-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col"><a href="index.php">หน้าแรก</a>/<a href="listconference.php">งานประชุม</a>/<?php echo $topic_head ?></div>
        </div>
    </div>
</section>
<section class="conferenceSection">
    <div class="container-fluid">
        <div class="row mx-lg-5 mx-md-1 mx-sm-0 template-4 shadow-sm">
            <?php
            if ($result->num_rows > 0) {
                echo "<div class=\"col-12 mt-lg-2 mt-md-2 order-first\"><h5 class=\"topic-header\">วันที่จัดงาน " . ($row["start_confer_date"] != "0000-00-00" && $row["start_confer_date"] != null ? convertToThaiDateShort($row["start_confer_date"]) : "-") . ($row["end_confer_date"]!=null && $row["end_confer_date"]!="0000-00-00"?" ถึงวันที่ ".convertToThaiDateShort($row["end_confer_date"]):"") ."</h5></div>";
                echo "<div class=\"col-lg-7 col-md-7 col-12 mb-3 pb-3 order-lg-2 order-md-2 order-3\">";
                echo "<div style=\"margin-bottom:1em\"><img src=\"" . $row["picture_URL"] . "\"></div>";
                echo "<div>" . $row["detail"] . "</div>";
                echo "</div>";
                echo "<div class=\"col-lg-5 col-md-5 col-12 mb-3 pb-3 order-lg-3 order-md-3 order-2\">";
                echo "<div class=\"topic-header\"><h5 class=\"topic-header\">หัวข้อการประชุม " . $row["topic_confer"] . "</h5></div>";
                echo "<div>สถานที่จัดงาน " . ($row["place"] != null ? $row["place"] : "-") . "</div>";
                echo "</div>";
            } else {
                echo "<div class=\"col-12 mt-lg-2 mt-md-2\" style=\"height:400px\"><div class=\"center-content\"><h2 class=\"topic-header\">ไม่พบรายละเอียดงานประชุม</h2></div></div>";
            }
            ?>
        </div>
    </div>
</section>
<?php
include "include/footer.php";
?>
<script>
    $(document).ready(() => {
        $(window).on('beforeunload', (event) => {
            cleartmp(<?php echo $id ?>);
        });
    });
</script>