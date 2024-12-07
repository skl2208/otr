<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";

if (isset($_SERVER['HTTPS'])) {
    if ($_SERVER['HTTPS'] == 'off') {
        $baseHTTP = "http://";
    } else {
        $baseHTTP = "https://";
    }
}
$image_source = "";
$default_status = "Y";

if (isset($_POST["command"])) {
    if ($_POST["command"] == "UPDATE") {

        $id = $_POST["id"];
        $topic = $_POST["topic"];
        $keyword = $_POST["keyword"];
        $image_link = $_POST["image_link"];
        $url = $_POST["url"];
        $seq = $_POST["seq"];
        $status = $_POST["status"];
        $default_status = $status;

        $sql = "UPDATE carousel SET topic=?,status=?,keyword=?,image_link=?,url=?,seq=?,create_date=CURRENT_TIMESTAMP() WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssii',$topic,$status,$keyword,$image_link,$url,$seq,$id);
        $stmt->execute();

        if ($stmt->affected_rows >0) {
            //successful update
            header("location: list_banner.php?msg=อัพเดทข้อมูลเรียบร้อยแล้ว");
            exit(0);
        } else {
        }
    }
    if ($_POST["command"] == "INSERT") {

        $topic = $_POST["topic"];
        $detail = $_POST["detail"];
        $keyword = $_POST["keyword"];
        $image_link = $_POST["image_link"];
        $url = $_POST["url"];
        $seq = $_POST["seq"];
        $status = $_POST["status"];

        $sql = "INSERT INTO carousel (topic,status,keyword,image_link,url,seq) VALUES (?,?,?,?,?,?) ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssi',$topic,$status,$keyword,$image_link,$url,$seq);
        $stmt->execute();
        if ($stmt->affected_rows>0) {
            //successful update
            header("location: list_banner.php?msg=เพิ่มข้อมูลเรียบร้อยแล้ว");
            exit(0);
        } else {
        }
    }
} else {
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $command = "UPDATE";
        $headline = "แก้ไข";
        $sql = "SELECT id,topic,keyword,image_link,url,create_date,status,seq FROM carousel WHERE id=" . $id;
        $result = $conn->query($sql);
        if ($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                //========== ตรวจสอบหา full path url ใน picture_URL
                //== ถ้าไม่มีให้เติม http://<web>/<path> ลงใน image_source
                $image_source = strval($row["image_link"]);
                $pos = strpos($image_source, "http");

                if ($pos === false) {
                    $image_source = $baseHTTP . $baseURL . $row["image_link"];
                }

                if ($row["status"] == "Y") {
                    $default_status = "Y";
                } else {
                    $default_status = "N";
                }
            } else {
            }
        } else {
        }
    } else {
        $headline = "เพิ่ม";
        $image_source = "../images/default_carousel.png";
        $command = "INSERT";
        $id = 0;
    }
}

?>
<script>
    function doCancel() {
        window.location.href = "list_banner.php";
    }

    function updateCheckBox(thisobj) {

        if (thisobj.checked) {
            document.getElementById("status").value = "Y";
        } else {
            document.getElementById("status").value = "N";
        }
    }

    function validate_data() {

        var b = document.getElementById("topic").value;
        var e = document.getElementById("status").value;

        // if (b == "" || b == null) {
        //     alert("กรุณาใส่หัวข้อข่าว");
        //     document.getElementById("topic").focus();
        //     return false;
        // }
        return true;
    }
</script>
<section id="editContent">
    <h3><?php echo $headline ?> Banner</h3>
    <form method="POST" name="formedit" onsubmit="return validate_data();">
        <div class="d-table w-100 mt-3">
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">ภาพ 1600:400 หรือสัดส่วน 4:1 </div>
                <div class="ps-2 smallerFont w-100 column-input"><img src="<?php echo $image_source ?>" style="height:200px;width:800px" id="showPreview"><br>
                    <button class="btnEDIT" type="button" onclick="manage_banner.showAlbum();">เลือกภาพจากอัลบัม</button>
                    <button class="btnEDIT" type="button" onclick="manage_banner.uploadBanner();">อัพโหลดภาพใหม่</button>
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">หัวข้อ</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="หัวข้อ" class="w-75" title="" id="topic" name="topic" value="<?php echo ($command == "UPDATE" ? $row["topic"] : "") ?>"></div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">Keyword</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="Keyword สำหรับให้ค้นหา ให้คั่นด้วย ," class="w-75" title="" id="keyword" name="keyword" value="<?php echo ($command == "UPDATE" ? $row["keyword"] : "") ?>"></div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">URL ( http://www...)</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="URL ที่จะลิงค์ไป เช่น http://www.bhumibolhospital.rtaf.mi.th/" class="w-75" title="" id="url" name="url" value="<?php echo ($command == "UPDATE" ? $row["url"] : "") ?>"></div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">ลำดับในการแสดง</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="number" title="ถ้าไม่ระบุจะให้ค่าเริ่มต้นเป็น 999" class="ms-1 me-1" id="seq" name="seq" value="<?php echo ($command == "UPDATE" ? $row["seq"] : "999")?>"></div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">แสดงในหน้าเวป</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="checkbox" title="ถ้าไม่ระบุจะให้ค่าเริ่มต้นเป็นแสดง" class="ms-1 me-1" id="status1" name="status1" <?php echo ($default_status == "Y") ? "checked" : "" ?> onclick="updateCheckBox(this);">แสดง</div>
            </div>
        </div>
        <div class="d-table w-100 mt-3">
            <div class="ps-2 text-center d-table-cell">
                <button class="btnOK"><img src="../images/icon_save.png">บันทึก</button> <button type="button" class="btnCancel" onclick="doCancel();"><img src="../images/ic_close.png"> ยกเลิก</button>
            </div>
        </div>
        <input type="hidden" id="image_link" name="image_link" value="<?php echo $image_source ?>">
        <input type="hidden" id="status" name="status" value="<?php echo $default_status; ?>">
        <input type="hidden" id="command" name="command" value="<?php echo $command; ?>">
        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
    </form>
</section>
<section id="albumpicture">
    <div>
        <span class="biggerFont">เลือกภาพจากคลังภาพ</span>
    </div>
    <?php
    $sql = "SELECT id,image_link FROM carousel ORDER BY create_date DESC";
    $result = $conn->query($sql);
    ?>
    <div class="container-fluid ps-0 pe-0 ms-0 me-0" style="width:100%">
        <div class="row gx-0 gy-1 showListAlbumBanner" id="showListAlbumPicture">
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if (strpos($row["image_link"], "http")) {
                        $bannerURL = $baseHTTP.$baseURL.$row["image_link"];
                    } else {
                        $bannerURL = $row["image_link"];
                    }
                    echo "<div class=\"col-6 text-center bg-white p-1\"><a href=\"javascript:manage_banner.preview_Image('show','".$bannerURL."')\"><img src=\"" . $bannerURL . "\" class=\"p-1\"></a></div>";
                }
            }
            ?>
        </div>
    </div>
    <section class="paging">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center" id="displayPaging">
            </ul>
        </nav>
    </section>
    <div class="text-center mt-4">
        <button type="button" class="btnCancel" onclick="manage_banner.closeAlbum()"><img src="../images/ic_close.png"> ยกเลิก</button>
    </div>
    <div class="preview" id="showpicture">
        <!-- show picture -->
        <div class="bg-grey" id="showFullAlbumPicture">
        </div>
        <div>
            <input type="hidden" name="changePictureFromAlbum" id="changePictureFromAlbum" value="">
            <button type="button" class="btnOK" onclick="change_Picture_FromAlbum(document.getElementById('changePictureFromAlbum').value);"><img src="../images/icon_save.png"> ยืนยันการเปลี่ยนภาพ</button>
            <button type="button" class="btnCancel" onclick="javascript:albumPicture('none','');"><img src="../images/ic_close.png"> ยกเลิก</button>
        </div>
    </div>
</section>
<section id="uploadbanner">
    <div>
        <span class="biggerFont">อัพโหลดใหม่</span>
    </div>
    <div style="min-height:100px;overflow-y:auto;">
        <img src="../images/empty_image.png" style="width:800px;height:200px;border:2px solid gray;margin-bottom:5px" id="showPreviewPicture"><br>
        <p>ใช้ภาพที่มีขนาดสัดส่วน ความกว้าง : ความสูง เท่ากับ 4:1 เช่น 1600:400 , 800:200</p>
        <p>
        <ul>
            <li>กดปุ่ม Choose File เพื่อเลือกไฟล์จากเครื่อง</li>
            <li>กดปุ่ม อัพโหลด เพื่อโหลดรูปขึ้นไปยังระบบ</li>
        </ul>
        </p>
    </div>
    <div>
        <form id="form1" name="form1" method="post" action="javascript:manage_banner.upload_image(document.getElementById('form1'));">
            <input type="file" name="inputFile" id="inputFile" placeholder="เลือกไฟล์">
            <input type="hidden" name="typeupload" id="typeupload" value="carousel">
            <button class="btnOK" type="submit"><img src="../images/icon_upload_white.png"> อัพโหลด</button>
        </form>
    </div>
    <div class="text-center mt-4">
        <button type="button" class="btnOK" id="saveUploadBanner" style="display:none" onclick="saveUploadBanner();"><img src="../images/icon_save.png"> บันทึกการเปลี่ยนแปลง</button>
        <button type="button" class="btnCancel" onclick="manage_banner.closeUploadBanner();"><img src="../images/ic_close.png"> ออกจากหน้าต่างนี้</button>
    </div>
</section>
<div class="spinner-border text-info" id="spinner"></div>
<div id="showToast"></div>
<?php
$conn->close();
include "inc/footer.php";
?>