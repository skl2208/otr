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
$default_pin = "N";
$headline = "แก้ไข";
$command = "UPDATE";
if (isset($_GET["type"])) {
    $typenews = $_GET["type"];
} else {
    if (isset($_POST["type"])) {
        $typenews = $_GET["type"];
    }
}

if (isset($_POST["command"])) {
    if ($_POST["command"] == "UPDATE") {

        $id = $_POST["id"];
        $topic = $_POST["topic"];
        $detail = $_POST["detail"];
        $keyword = $_POST["keyword"];
        $picture_URL = $_POST["picture_URL"];
        $status = $_POST["status"];
        $default_status = $status;
        $default_pin = $_POST["pin"];
        $typenews = $_POST["type"];
        $username = $_SESSION["username"];

        $topic = str_replace("'", "\&quot;", $topic);
        $detail = str_replace("'", "\&quot;", $detail);
        $keyword = str_replace("'", "\&quot;", $keyword);

        // ============ ถ้ามีการปักหมุดเข้ามา ให้ลบหมุดหัวข้ออื่นในหมวดนี้ออกให้หมด ปักหมุดได้เพียง 1 หัวข้อ ========= //
        if ($default_pin == "Y") {
            $sql = "UPDATE news SET pin='N' WHERE typenews='$typenews'";
            $result0 = $conn->query($sql);
        }

        $sql = "UPDATE news SET typenews=?,topic=?,status=?,detail=?,keyword=?,picture_URL=?,pin=?,update_date=CURRENT_TIMESTAMP(),user_update=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssssi',$typenews,$topic,$status,$detail,$keyword,$picture_URL,$default_pin,$username,$id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            //successful update
            header("location: list_news.php?msg=อัพเดทข้อมูลเรียบร้อยแล้ว&type=" . $typenews);
            exit(0);
        } else {
        }
    }
    if ($_POST["command"] == "INSERT") {

        $topic = $_POST["topic"];
        $detail = $_POST["detail"];
        $keyword = $_POST["keyword"];
        $picture_URL = $_POST["picture_URL"];
        $status = $_POST["status"];

        $topic = str_replace("'", "\&quot;", $topic);
        $detail = str_replace("'", "\&quot;", $detail);
        $keyword = str_replace("'", "\&quot;", $keyword);
        $username = $_SESSION["username"];

        $sql = "INSERT INTO news (typenews,user_create,user_update,topic,detail,keyword,picture_URL,status) VALUES (?,?,'',?,?,?,?,?) ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssss',$typenews,$username,$topic,$detail,$keyword,$picture_URL,$status);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            //successful update
            header("location: list_news.php?msg=เพิ่มข้อมูลเรียบร้อยแล้ว&type=" . $typenews);
            exit(0);
        }
    }
} else {
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $command = "UPDATE";
        $headline = "แก้ไข";
        $sql = "SELECT id,typenews,topic,keyword,picture_URL,detail,status,pin FROM news WHERE id=" . $id;
        $result = $conn->query($sql);
        if ($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                //========== ตรวจสอบหา full path url ใน picture_URL
                //== ถ้าไม่มีให้เติม http://<web>/<path> ลงใน image_source
                $image_source = strval($row["picture_URL"]);
                $pos = strpos($image_source, "http");

                if ($pos === false) {
                    $image_source = $baseHTTP . $baseURL . $row["picture_URL"];
                }

                if ($row["status"] == "Y") {
                    $default_status = "Y";
                } else {
                    $default_status = "N";
                }

                $default_pin = $row["pin"];
            }
        }
    } else {
        $headline = "เพิ่ม";
        $command = "INSERT";
        $id = 0;
    }
}

?>
<!-- <script src="https://cdn.ckeditor.com/4.18.0/full/ckeditor.js"></script> -->
<script src="ckeditor/ckeditor.js"></script>
<script>
    function doCancel() {
        window.location.href = "list_news.php?type=<?php echo $typenews ?>";
    }

    function updateCheckBox(thisobj) {

        if (thisobj.checked) {
            document.getElementById("status").value = "Y";
        } else {
            document.getElementById("status").value = "N";
        }
    }

    function updateCheckBox2(thisobj) {

        if (thisobj.checked) {
            document.getElementById("pin").value = "Y";
        } else {
            document.getElementById("pin").value = "N";
        }
    }

    function validate_data() {

        var b = document.getElementById("topic").value;
        var d = document.getElementById("detail").value;
        var e = document.getElementById("status").value;
        var f = document.getElementById("showpin").checked;


        if (b == "" || b == null) {
            showAlert.show("กรุณาใส่หัวข้อข่าว", "DANGER");
            document.getElementById("topic").focus();
            return false;
        }
        if (f) {
            document.getElementById("pin").value = "Y";
        } else {
            document.getElementById("pin").value = "N";
        }
        return true;
    }

    function goPreviewHTML() {

        var editor = CKEDITOR.instances.detail;
        previewHTML(document.forms.formedit, editor.getData());
    }
    $(function(){
        const command = "<?php echo $command?>";

        if(command=="INSERT") {
            var init_typenews = document.getElementById("type");

            init_typenews.value = "<?php echo $typenews?>";
            init_typenews.disabled = true;
        }
    });
</script>

<section id="editContent">
    <h3><?php echo $headline . " " . $typenews ?></h3>
    <form method="POST" name="formedit" id="formedit" onsubmit="return validate_data();">
        <div class="d-table w-100 mt-3">
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">หัวข้อข่าว</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="หัวข้อข่าว" class="w-75" title="" maxlength="100" id="topic" name="topic" value='<?php echo ($command == "UPDATE" ? $row["topic"] : "") ?>'></div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">Keyword</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="คำค้นหา" class="w-75" title="" id="keyword" name="keyword" value='<?php echo ($command == "UPDATE" ? $row["keyword"] : "") ?>'></div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">ภาพข่าว</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <a href="javascript:window_ShowPicture('block',document.getElementById('previewImage').src);"><img src="<?php echo ($command == "UPDATE" ? $image_source : "../images/empty_image.png") ?>" style="max-height:200px;width:auto;max-width:500px" id="previewImage"></a>
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">ระบุ URL ของภาพหัวข้อข่าว</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="URL ของภาพ" class="w-75" title="" id="picture_URL" name="picture_URL" value="<?php echo ($command == "UPDATE" ? $image_source : "") ?>">
                    <button class="btnEDIT" type="button" onclick="window_ShowPicture('block',document.getElementById('picture_URL').value);">ดูภาพ</button><br>
                    <button class="btnEDIT" type="button" onclick="showAlbum('',1,'picture_URL','show');">ภาพจากอัลบัม</button>
                    <button class="btnEDIT" type="button" onclick="uploadPicture('block');">อัพโหลดภาพ</button>
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">ประเภท</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <select id="type" name="type">
                        <option value="Highlight" selected>Highlight</option>
                        <option value="Activity" <?php echo ($command == "UPDATE" && $row["typenews"] == "Activity" ? "selected" : "") ?>>Activities</option>
                        <option value="Upcoming Events" <?php echo ($command == "UPDATE" && $row["typenews"] == "Upcoming Events" ? "selected" : "") ?>>Upcoming Events</option>
                        <option value="Annoucement" <?php echo ($command == "UPDATE" && $row["typenews"] == "Annoucement" ? "selected" : "") ?>>Annoucement</option>
                    </select>
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">เนื้อหา</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <textarea rows="10" cols="80" title="" id="detail" name="detail" onchange="this.value=this.value.replace(/'/g,'&quot;');"><?php echo ($command == "UPDATE" ? $row["detail"] : "") ?></textarea><br>
                    <script>
                        CKEDITOR.replace('detail', {
                            contentsCss: 'css/index.css',
                            disableNativeSpellChecker: true,
                            toolbar: [{
                                    name: 'document',
                                    items: ['Source', '-', 'Undo', 'Redo']
                                },
                                {
                                    name: 'basicstyles',
                                    items: ['Bold', 'Italic', 'Strike', '-', 'RemoveFormat']
                                },
                                {
                                    name: 'paragraph',
                                    items: ['NumberedList', 'BulletedList', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
                                },
                                {
                                    name: 'links',
                                    items: ['Link', 'Unlink', 'Anchor']
                                },
                                {
                                    name: 'insert',
                                    items: ['Image', 'Table', 'HorizontalRule', 'Smiley']
                                },
                                {
                                    name: 'tools',
                                    items: ['Maximize', 'ShowBlocks', 'About']
                                },
                                '/',
                                {
                                    name: 'styles',
                                    items: ['Styles', 'Format', 'Font', 'FontSize']
                                },
                                {
                                    name: 'colors',
                                    items: ['TextColor', 'BGColor']
                                }
                            ]
                        });
                    </script>
                    <!-- <div class="d-inline-block small-preview-picture">Pic#1</div> <div class="d-inline-block small-preview-picture">Pic#2</div> <div class="d-inline-block small-preview-picture">Pic#3</div> <div class="d-inline-block small-preview-picture">Pic#4</div> <div class="d-inline-block small-preview-picture">Pic#5</div> -->
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">ปักหมุด</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="checkbox" title="" id="showpin" <?php echo ($default_pin == "Y") ? "checked" : "" ?> onclick="updateCheckBox2(this);"></div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">แสดงในหน้าเวป</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="checkbox" title="ถ้าไม่ระบุจะให้ค่าเริ่มต้นเป็นแสดง" class="ms-1 me-1" id="status1" name="status1" <?php echo ($default_status == "Y") ? "checked" : "" ?> onclick="updateCheckBox(this);">แสดง</div>
            </div>
        </div>
        <div class="d-table w-100 mt-3">
            <div class="ps-2 text-center d-table-cell">
                <!-- previewHTML(document.forms.formedit); -->
                <?php
                if ($command == "UPDATE") {
                    echo "<button type=\"button\" class=\"btnDelete\" style=\"margin-right:2em\" onclick=\"javascript:manage_news.deleteContent('news',$id);\"><img src=\"../images/icon_delete.png\" class=\"icon_picture\"> ลบเนื้อหานี้</button>";
                }
                ?>
                <button type="button" class="btnOK" onclick="javascript:goPreviewHTML();"><img src="../images/icon_preview_white.png" class="icon_picture"> Preview</button>
                <button type="submit" class="btnOK"><img src="../images/icon_save.png" class="icon_picture">บันทึก</button>
                <button type="button" class="btnCancel" onclick="javascript:doCancel();"><img src="../images/ic_close.png" class="icon_picture"> ยกเลิก</button>
            </div>
        </div>
        <input type="hidden" id="pin" name="pin" value="<?php echo $default_pin ?>">
        <input type="hidden" id="status" name="status" value="<?php echo $default_status; ?>">
        <input type="hidden" id="command" name="command" value="<?php echo $command; ?>">
        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
    </form>
</section>
<section id="showpicture">
    <!-- show picture -->
    <div class="bg-grey" id="showFullPicture" style="background-image:url(<?php echo $image_source ?>);">
    </div>
    <div>
        <button type="button" class="btnCancel" onclick="window_ShowPicture('close','');"><img src="../images/ic_close.png"> ปิดหน้าต่าง</button>
    </div>
</section>
<section id="albumpicture">
    <div>
        <span class="biggerFont">เลือกจากอัลบัม</span>
        <select name="catagory" id="catagory" onchange="showAlbum(this.value,1,'','show');">
            <option value="" selected>ทุกอัลบัม</option>
            <?php
            $sql = "SELECT id,catagory FROM pic_catagory ORDER BY catagory";
            echo $sql . "<br>";
            $result2 = $conn->query($sql);
            if ($result2) {
                if ($result2->num_rows > 0) {
                    while ($row2 = $result2->fetch_assoc()) {
                        echo "<option value=\"" . $row2["catagory"] . "\">" . $row2["catagory"] . "</option>";
                    }
                } else {
                    echo "fail";
                }
            } else {
                echo "fail";
            }
            ?>
        </select>
    </div>
    <div class="container-fluid ps-0 pe-0 ms-0 me-0" style="width:100%">
        <div class="row gx-0 gy-1 showListAlbumPicture" id="showListAlbumPicture">
            <!-- <div class="col-3 text-center bg-white p-1"><a href="javascript:void(0)" title="คำอธิบายรูป"><img src="../images/upload/activity/R1 รวม ใหม่.jpg" class="p-1"><div>กิจกรรมทั่วไป</div></a></div>
            <div class="col-3 text-center bg-white p-1"><a href="javascript:void(0)" title="คำอธิบายรูป"><img src="../images/upload/news/test-pic-annouce2.jpg" class="p-1"><div>ข่าวประชาสัมพันธ์</div></a></div> -->
        </div>
    </div>
    <section class="paging">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center" id="displayPaging">
            </ul>
        </nav>
    </section>
    <div class="text-center mt-4">
        <button type="button" class="btnCancel" onclick="showAlbum('','','','none')"><img src="../images/ic_close.png"> ยกเลิก</button>
    </div>
    <div class="preview">
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
<section id="uploadpicture">
    <div>
        <span class="biggerFont">อัพโหลดใหม่</span>
    </div>
    <div style="min-height:100px;overflow-y:auto;">
        <img src="../images/empty_image.png" style="width:300px;height:auto;border:2px solid gray;margin-bottom:5px" id="showPreviewPicture">
    </div>
    <div>
        <form id="form" method="post" action="javascript:upload_image(document.getElementById('form'));">
            <input type="file" name="inputFile" id="inputFile" placeholder="เลือกไฟล์">
            <input type="hidden" name="typeupload" id="typeupload" value="news">
            <input type="hidden" name="typenews" id="typenews" value="<?php echo $typenews ?>">
            <button class="btnOK"><img src="../images/icon_upload_white.png"> อัพโหลด</button>
        </form>
    </div>
    <div class="text-center mt-4">
        <button type="button" class="btnOK" onclick="saveUploadPicture('<?php echo $typenews ?>');"><img src="../images/icon_save.png"> บันทึกการเปลี่ยนแปลง</button>
        <button type="button" class="btnCancel" onclick="uploadPicture('none')"><img src="../images/ic_close.png"> ยกเลิก</button>
    </div>
</section>
<section id="showpicture">
    <!-- show picture -->
    <div class="bg-grey" id="showFullPicture" style="background-image:url(<?php echo $image_source ?>);">
    </div>
    <div>
        <button type="button" class="btnCancel" onclick="window_ShowPicture('close','');"><img src="../images/ic_close.png"> ปิดหน้าต่าง</button>
    </div>
</section>
<div class="spinner-border text-info" id="spinner"></div>
<div id="showAlert">
    <div>
        <div>
            <img src="../images/icon_ok.png">
        </div>
        <div></div>
        <div>
            <button onclick="javascript:showAlert.hide();" class="btnOK">ปิด</button>
        </div>
    </div>
</div>
<div id="showToast"></div>
<div id="showConfirm">
    <div>
        <div>
            <img src="../images/icon_ok.png">
        </div>
        <div></div>
        <div>
            <button onclick="showConfirm.confirm();" class="btnOK">ต้องการลบ</button> <button onclick="showConfirm.cancel();" class="btnCancel">ยกเลิก</button>
        </div>
    </div>
</div>
<?php
$conn->close();
include "inc/footer.php";
?>