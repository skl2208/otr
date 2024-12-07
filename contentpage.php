<?php
include "include/header.php";
include "include/config.php";
//======== รับค่า id ของ webpage หรือ menu id ของ menu อย่างใดอย่างหนึ่ง ============//
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "SELECT content AS detail,menu_name FROM webpage LEFT JOIN menu on webpage.id=menu.link_url WHERE webpage.id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
} else if (isset($_GET["menuitem_id"])) {
    $menuitem_id = $_GET["menuitem_id"];
    $sql = "SELECT menu_name,content AS detail,link_url,is_external from menu LEFT JOIN webpage on menu.link_url = webpage.id where menu.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i',$menuitem_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
}
?>
<script>
    function goURL(url) {
        window.location.href = url;
    }
</script>
<section class="rootnavigator mx-lg-5 mx-md-1 mx-0 p-3">
    <div class="container-fluid">
        <div class="row">
            <?php
            if($result && $result->num_rows > 0) {
                echo "<div class=\"col\"><a href=\"index.php\">หน้าแรก</a>".($row["menu_name"] == NULL ? "/" : "/" . $row["menu_name"])."</div>";
            } 
            ?>
            
        </div>
    </div>
</section>
<section class="detailSection">
    <div class="container-fluid">
        <div class="row mx-lg-5 mx-md-1 mx-sm-0 template-4 shadow-sm">
            <?php
            if ($result->num_rows > 0) {
                //echo "<div class=\"col-12 mt-lg-5 mt-md-5\"></div>";
                if ($row["is_external"] == 'Y') {
                    //========= Link ภายนอก CMS ==========
                    if ($row["link_url"] == null || $row["link_url"] == "") {
                        echo "<div class=\"col-12 mt-lg-2 mt-md-2\" style=\"height:400px;position:relative\"><div class=\"center-content-info\"><h2 class=\"topic-header\">ไม่พบเนื้อหา</h2><br><img src=\"images/icon_character1.png\" style=\"height:300px;width:auto\"></div></div>";
                    } else {
                        echo "<div class=\"col-12 mt-lg-2 mt-md-2\" style=\"height:400px;position:relative\"><div class=\"center-content-info\"><h2 class=\"topic-header\">กำลังไปหน้าเวป...</h2><script language=\"javascript\">goURL('" . $row["link_url"] . "');</script></div></div>";
                    }
                } else {
                    //========= Link ภายใน CMS ==========
                    if ($row["detail"] == null || $row["detail"] == "") {
                        echo "<div class=\"col-12 mt-lg-2 mt-md-2\" style=\"height:400px;position:relative\"><div class=\"center-content-info\"><h2 class=\"topic-header\">ไม่พบเนื้อหา</h2><br><img src=\"images/icon_character1.png\" style=\"height:300px;width:auto\"></div></div>";
                    } else {
                        echo "<div class=\"col-12 mt-3 pt-3 mb-3 pb-3\">" . $row["detail"] . "</div>";
                    }
                }
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
<script>
    $(document).ready(() => {
        $(window).on('beforeunload', (event) => {
            cleartmp(<?php echo $id ?>);
        });
    });
</script>