<?php
include "include/header.php";
include "include/config.php";

$lasthit = 0;
$typenews = "";
$id = $_GET["id"];

if(is_numeric($id) && $id > 0) {
    $sql = "SELECT topic,typenews,detail,picture_URL,create_date,update_date,user_create,user_update,hit FROM news WHERE id=? and status='Y'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $result = $stmt->get_result();
    $title_og = "";
    $image_og = "";
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $typenews = $row["typenews"];
        $lasthit = $row["hit"];
        $title_og = $row["topic"];
        $image_og = $row["picture_URL"];
    
        // เก็บ Log ใช้เพื่อวิเคราะห์จำนวน View ผิดปกติ
        $sql1 = "INSERT INTO visitor_log (news_id,typenews) VALUES (?,?)";
        $stmt = $conn->prepare($sql1);
        $stmt->bind_param('is',$id,$row["typenews"]);
        $stmt->execute();
        $result1 = $stmt->get_result();
    
        // สิ้นสุด
        $lasthit++;
    
        $sql = "UPDATE news SET hit=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii',$lasthit,$id);
        $stmt->execute();
        $result0 = $stmt->get_result();
    }
} 
?>
<section class="rootnavigator mx-lg-5 mx-md-1 mx-0 p-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col"><a href="index.php">หน้าแรก</a>/<a href="showall.php?typenews=<?php echo $typenews ?>"><?php echo $typenews ?></a>/รายละเอียดข่าว</div>
        </div>
    </div>
</section>
<section class="detailSection">
    <div class="container-fluid">
        <div class="row mx-lg-5 mx-md-1 mx-sm-0 template-4 shadow-sm">
            <?php
            if (is_numeric($id) && $result->num_rows > 0) {
                echo "<div class=\"col-12 mt-lg-2 mt-md-2\"><h2 class=\"topic-header\">{$row["topic"]}</h2></div>";
                echo "<div class=\"col-8 mt-2 mb-2 smallerFont border-bottom\">วันที่ created ".convertDBToThai($row["create_date"])." วันที่ edited " . convertDBToThai($row["update_date"]) . " | เข้าชมแล้ว " . $lasthit . " | " . $row["user_create"] . "</div>";
                echo "<div class=\"col-4 mt-2 mb-2 smallerFont border-bottom text-lg-end text-md-end text-start\"><img src=\"images/icon_share.png\" style=\"height:25px;width:auto\"> <a href=\"https://www.facebook.com/sharer/sharer.php?app_id=2826546344155379&u=".$baseHTTP.$baseURL."shownewsdetail.php?id=" . $id . "&typenews=" . $typenews . "\" target=\"_blank\"><img src=\"images/fb_logo_color.png\" style=\"height:25px;width:auto;\"></a></div>";
                echo "<div class=\"col-12 mb-3 template-4-headpicture\"><img src=\"" . $row["picture_URL"] . "\"></div>";
                echo "<div class=\"col-12 mb-3 pb-3\">" . $row["detail"] . "</div>";
            } else {
                echo "<div class=\"col-12 mt-lg-2 mt-md-2\" style=\"height:400px;position:relative\"><div class=\"center-content-info\"><h2 class=\"topic-header\">ไม่พบเนื้อหาหรือข้อความ</h2><br><img src=\"images/icon_character1.png\" style=\"height:300px;width:auto\"></div></div>";
            }
            ?>
        </div>
    </div>
</section>

<?php
include "include/footer.php";
?>