<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";

$id = 0;
$command = "";
$headline = "";
$download_URL = "";
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $command = "EDIT";

    //Blind data
    $sql = "SELECT * FROM officer WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // found data
        $headline = "แก้ไข";
        $row = $result->fetch_assoc();
        if ($row["download_URL"] != "") {
            $download_URL = $row["download_URL"];
            $pos = strpos($download_URL, "http");
            if ($pos === false) {
                $download_URL = $baseHTTP . $baseURL . $row["download_URL"];
            }
        }

        //======== Convert relative path To absolute path =========
        if ($row["picture_URL"] != null && $row["picture_URL"] != "") {

            $fullpath = strval($row["picture_URL"]);
            $pos = strpos($fullpath, "http");

            if ($pos === false) {
                $fullpath = $baseHTTP . $baseURL . $row["picture_URL"];
                $fullpath = str_replace("../", "", $fullpath);
            }
        } else {
            $fullpath = $baseHTTP . $baseURL . "images/static_image/a_person.png";
        }
    }
} else {
    $command = "ADD";
    $headline = "เพิ่ม";
    $fullpath = $baseHTTP . $baseURL . "images/static_image/a_person.png";
}

?>
<script>
    function doCancel() {
        window.location.href = "list_officer.php";
    }

    function updateCheckBox(thisobj) {

        if (thisobj.checked) {
            document.getElementById("status1").value = "Y";
        } else {
            document.getElementById("status1").value = "N";
        }
    }

    $(function() {
        const dl = document.getElementById("download_URL").value;
        if (dl != "") {
            $("#viewBtn").show();
            $("#delBtn").show();
            $("#addBtn").hide();
        } else {
            $("#viewBtn").hide();
            $("#delBtn").hide();
            $("#addBtn").show();
        }

        manage_officer.init();
    });
</script>
<script src="https://cdn.ckeditor.com/4.18.0/full/ckeditor.js"></script>
<section id="main">
    <h3><?php echo $headline ?> เจ้าหน้าที่ประจำสาขา</h3>
    <form method="POST" name="formedit" id="formedit">
        <div class="d-table w-100 mt-3">
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">ยศ/คำนำหน้า</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <select name="titlename" id="titlename">
                        <?php
                        $sql = "SELECT titlename from titlename ORDER BY seq";
                        $result2 = $conn->query($sql);
                        ?>
                        <option value="" selected>กรุณาใส่คำนำหน้า</option>
                        <?php
                        if ($result2 && $result2->num_rows > 0) {
                            while ($row2 = $result2->fetch_assoc()) {
                                echo "<option value=\"{$row2["titlename"]}\"" . (($row2["titlename"] == $row["titlename"] && $command == "EDIT") ? "selected" : "") . ">{$row2["titlename"]}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">รูปภาพ</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <img src="<?php echo $fullpath ?>" style="height:150px;background-color:white" id="officer_picture"><br>
                    <button class="btnDelete" type="button" onclick="manage_officer.deleteUploadPicture();" id="del_picture_btn"><img src="../images/icon_delete.png">ลบภาพ</button>
                    <button class="btnEDIT" type="button" onclick="manage_officer.uploadFromAlbum();" id="get_album_picture_btn"><img src="../images/icon_album.png">ภาพจากอัลบัม</button>
                    <button class="btnEDIT" type="button" onclick="manage_officer.uploadPicture();//uploadPicture('block');" id="get_upload_picture_btn"><img src="../images/icon_upload_white.png">อัพโหลดภาพ</button>
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">* ชื่อ</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="ชื่อ" class="w-75" id="name" name="name" value="<?php echo (($command == "EDIT") ? $row["name"] : '') ?>"></div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">* นามสกุล</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="นามสกุล" class="w-75" id="surname" name="surname" value="<?php echo (($command == "EDIT") ? $row["surname"] : '') ?>"></div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">ตำแหน่ง</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <input type="text" placeholder="ตำแหน่ง" title="ระบุว่าเป็น อาจารย์แพทย์ หรืออาจารย์พิเศษ" class="w-75" id="position" name="position" value="<?php echo (($command == "EDIT") ? $row["position"] : '') ?>">
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">สังกัด</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <select name="controlunit" id="controlunit">
                        <option value="0">กรุณาเลือกสังกัดสาขาวิชา</option>
                        <?php
                        $sql = "SELECT id,nameth FROM department ORDER BY nameth";
                        $result0 = $conn->query($sql);
                        if ($result0 && $result0->num_rows > 0) {
                            while ($row0 = $result0->fetch_assoc()) {
                                echo "<option value=\"" . $row0["nameth"] . "\"" . ($command == "EDIT" && $row["controlunit"] == $row0["nameth"] ? "selected" : "") . ">" . $row0["nameth"] . "</option>";
                            }
                            
                            echo "<option value = \"ผู้ตรวจการพยาบาล\"".($command == "EDIT" && $row["controlunit"] == "ผู้ตรวจการพยาบาล" ? "selected" : "").">ผู้ตรวจการพยาบาล</option>";
                            echo "<option value = \"พยาบาลหัวหน้าหน่วยงาน\"".($command == "EDIT" && $row["controlunit"] == "พยาบาลหัวหน้าหน่วยงาน" ? "selected" : "").">พยาบาลหัวหน้าหน่วยงาน</option>";
                            echo "<option value = \"หน่วยตรวจพิเศษอายุรกรรม\"".($command == "EDIT" && $row["controlunit"] == "หน่วยตรวจพิเศษอายุรกรรม" ? "selected" : "") .">หน่วยตรวจพิเศษอายุรกรรม</option>";
                            echo "<option value = \"บุคลากรประจำกองอายุรกรรม\"".($command == "EDIT" && $row["controlunit"] == "บุคลากรประจำกองอายุรกรรม" ? "selected" : "").">บุคลากรประจำกองอายุรกรรม</option>";
                        
                        } ?>
                    </select>
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px;vertical-align:top">ว.ว.</div>
                <!--<div class="ps-2 smallerFont w-100 column-input"><textarea id="graduation" name="graduation" placeholder="เขียนเรียงกันไปคั่นด้วย ," class="w-75" rows=5><?php echo (($command == "EDIT") ? $row["graduation"] : '') ?></textarea></div>-->
                <div class="ps-2 smallerFont w-100 column-input">
                    <textarea id="graduation" name="graduation" class="w-75" rows=5><?php echo (($command == "EDIT") ? $row["graduation"] : '') ?></textarea>
                    <script>
                        CKEDITOR.replace('graduation', {
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
                                    items: ['NumberedList', 'BulletedList', 'Indent', '-', 'Blockquote', '-','JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
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
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">ลำดับการแสดงผล</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="ลำดับการแสดงผล" class="w-75" id="seq" name="seq" value="<?php echo (($command == "EDIT") ? $row["seq"] : '') ?>"></div>
            </div>

            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">เอกสารเพิ่มเติม</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <span id="showdownload_URL"><?php echo (($command == "EDIT") ? $row["download_URL"] : '') ?></span>
                    <span>
                        <button id="viewBtn" class="btnEDIT" onclick="manage_officer.view($('#download_URL').val());" type="button">ดู</button>
                        <button id="delBtn" class="btnDelete" onclick="showConfirm.show('ยืนยันต้องการลบข้อมูลนี้',manage_officer.deleteAttach,null);" type="button">ลบออก</button>
                        <button id="addBtn" class="btnEDIT" onclick="manage_officer.addAttach();" type="button">เพิ่ม</button>
                    </span>
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">สถานะ</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="checkbox" title="ถ้าไม่ระบุจะให้ค่าเริ่มต้นเป็นแสดง" class="ms-1 me-1" onclick="updateCheckBox(this);" <?php echo (($command == "EDIT") && $row["status"] == 'Y' ? 'checked' : '') ?>>ประจำการ</div>
            </div>
        </div>
        <div class="d-table w-100 mt-3">
            <div class="ps-2 text-center d-table-cell">
                <button class="btnOK" type="button" onclick="manage_officer.save(document.forms.formedit,CKEDITOR.instances.graduation);"><img src="../images/icon_save.png">บันทึก</button> <button type="button" class="btnCancel" onclick="doCancel();"><img src="../images/ic_close.png"> ยกเลิก</button>
            </div>
        </div>
        <input type="hidden" id="picture_URL" name="picture_URL" value="<?php echo ($command == "EDIT") ? $fullpath : '' ?>">
        <input type="hidden" id="download_URL" name="download_URL" value="<?php echo ($command == "EDIT") ? $download_URL : '' ?>">
        <input type="hidden" id="status1" name="status1" value="<?php echo ($command == "EDIT") ? $row["status"] : '' ?>">
        <input type="hidden" id="command" name="command" value="<?php echo $command ?>">
        <input type="hidden" id="id" name="id" value="<?php echo ($command == "EDIT") ? $row["id"] : '' ?>">
    </form>
</section>
<section id="uploadPicture">
    <div>
        <span class="biggerFont">อัพโหลดใหม่</span>
    </div>
    <div style="min-height:100px;overflow-y:auto;">
        <img src="../images/empty_image.png" style="width:300px;height:auto;border:2px solid gray;margin-bottom:5px" id="showPreviewPicture">
    </div>
    <div>
        <form id="form" method="post" action="javascript:upload_image(document.getElementById('form'));">
            <input type="file" name="inputFile" id="inputFile" placeholder="เลือกไฟล์">
            <input type="hidden" name="typeupload" id="typeupload" value="officer">
            <button class="btnOK"><img src="../images/icon_upload_white.png"> อัพโหลด</button>
        </form>
    </div>
    <div class="text-center mt-4">
        <button type="button" class="btnOK" onclick="manage_officer.saveUploadPicture('officer');" id="save_upload_picture_btn"><img src="../images/icon_save.png"> บันทึกการเปลี่ยนแปลง</button>
        <button type="button" class="btnCancel" onclick="manage_officer.saveUploadPicture('cancel');"><img src="../images/ic_close.png"> ยกเลิก</button>
    </div>
</section>
<section id="uploadAttachFile">
    <div>
        <span class="biggerFont">อัพโหลดเอกสารแนบ</span>
    </div>
    <div>
        <form id="formuploadattach" method="post" action="javascript:manage_officer.upload_AttachFile(document.getElementById('formuploadattach'));">
            <input type="file" name="inputFileAttach" id="inputFileAttach" placeholder="เลือกไฟล์">
            <input type="hidden" name="typeupload" id="typeupload" value="attach">
            <button class="btnOK"><img src="../images/icon_upload_white.png"> อัพโหลด</button>
        </form>
    </div>
    <div class="text-center mt-4">
        <button type="button" class="btnOK" onclick="manage_officer.save_addAttach('save');" id="save_attach_btn"><img src="../images/icon_save.png"> บันทึกการเปลี่ยนแปลง</button>
        <button type="button" class="btnCancel" onclick="manage_officer.save_addAttach('cancel');"><img src="../images/ic_close.png"> ยกเลิก</button>
    </div>
</section>
<section id="albumpicture">
    <div>
        <span class="biggerFont">เลือกจากอัลบัม</span>
        <select name="catagory" id="catagory" onchange="manage_officer.uploadFromAlbum(this.value,1)">
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
        <button type="button" class="btnCancel" onclick="manage_officer.cancel_Album();"><img src="../images/ic_close.png"> ยกเลิก</button>
    </div>
    <div class="preview">
        <!-- show picture -->
        <div class="bg-grey" id="showFullAlbumPicture">
        </div>
        <div>
            <input type="hidden" name="changePictureFromAlbum" id="changePictureFromAlbum" value="">
            <button type="button" class="btnOK" onclick="manage_officer.saveFromAlbum(document.getElementById('changePictureFromAlbum').value);"><img src="../images/icon_save.png">ยืนยันการเปลี่ยนภาพ</button>
            <button type="button" class="btnCancel" onclick="manage_officer.saveFromAlbum('none','');"><img src="../images/ic_close.png">ยกเลิก</button>
        </div>
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
<div id="showToast"></div>
<?php
$conn->close();
include "inc/footer.php";
?>