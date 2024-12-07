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

$headline = "แก้ไข";
$command = "UPDATE";
$id = 0;
$default_image = $baseHTTP . $baseURL . "images/default_conference.png";
$format_start_confer_date = "00-00-0000";
$format_end_confer_date = "00-00-0000";

if (isset($_GET["id"]) && $_GET["id"] != "" && $_GET["id"] != 0) {
    $id = $_GET["id"];
    $sql = "SELECT type_confer,topic_confer,picture_URL,start_confer_date,end_confer_date,detail,place FROM conference WHERE id=$id";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        //====== นำค่าเวลาจากใน db มาแปลงเป็น ว-ด-พศ.=======
        $tmp_date = $row["start_confer_date"];
        if ($tmp_date != null && $tmp_date != "0000-00-00") {
            $tmp_date_arr = explode("-", $tmp_date);
            $tmp_date_arr[0] += 543;
            $format_start_confer_date = date("d-m-Y", strtotime($tmp_date_arr[0] . "-" . $tmp_date_arr[1] . "-" . $tmp_date_arr[2]));
        }
        $tmp_date = $row["end_confer_date"];
        if ($tmp_date != null && $tmp_date != "0000-00-00") {
            $tmp_date_arr = explode("-", $tmp_date);
            $tmp_date_arr[0] += 543;
            $format_end_confer_date = date("d-m-Y", strtotime($tmp_date_arr[0] . "-" . $tmp_date_arr[1] . "-" . $tmp_date_arr[2]));
        }
    }
} else {
    $headline = "เพิ่มรายการ";
    $command = "ADD";
}

?>
<script src="https://cdn.ckeditor.com/4.18.0/full/ckeditor.js"></script>
<script>
    function doCancel() {
        window.location.href = "list_conference.php";
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

        var a = document.getElementById("topic_confer");
        var b = document.getElementById("type_confer");
        var c = document.getElementById("start_confer_date");
        var d = document.getElementById("end_confer_date");
        var e = document.getElementById("dsp_start_confer_date");
        var f = document.getElementById("dsp_end_confer_date");
        var h = document.getElementById("detail");
        var g = document.getElementById("place");

        if (a.value == "" || a.value == null) {
            showAlert.show("กรุณาใส่หัวข้องานประชุม", "DANGER", () => {
                a.focus();
            });
            return false;
        }

        if (b.value == "" || b.value == null) {
            showAlert.show("กรุณาเลือกประเภทงานประชุม", "DANGER", () => {
                b.focus();
            });
            return false;
        }

        if (e.value != "" && e.value != "0000-00-00") {
            const x = e.value.split("-");
            const start_date = new Date((x[2] - 543) + "-" + x[1] + "-" + x[0]);
            c.value = start_date.getFullYear() + "-" + (start_date.getMonth() + 1) + "-" + start_date.getDate();
        } else {
            c.value = "";
        }

        if (f.value != "" && e.value != "0000-00-00") {
            const y = f.value.split("-");
            const end_date = new Date((y[2] - 543) + "-" + y[1] + "-" + y[0]);
            d.value = end_date.getFullYear() + "-" + (end_date.getMonth() + 1) + "-" + end_date.getDate();
        } else {
            d.value = "";
        }

        const editor = CKEDITOR.instances.detail;
        var tmp_content = editor.getData();

        tmp_content = tmp_content.replace(/"/g, "\"").replace(/'/g, "\"");
        document.getElementById("detail").value = tmp_content;
        manage_conference.save(document.forms.formedit);

    }

    function valid_DATE(myObj) {

        const a = myObj.value;

        if (a != "00-00-0000" && a != "") {

            let b = a.replace(/\//g, "-").split("-");

            if (b.length == 3) {

                //======= ได้ค่า 3 array======
                if (parseInt(b[0]) == 0 || parseInt(b[1]) == 0) {
                    showAlert.show("รูปแบบวันที่ไม่ถูกต้อง", "DANGER", () => {
                        myObj.focus();
                    });
                    return false;
                } else {

                    if(parseInt(b[2])==0 || b[2].length==2) {
                        //======== พบว่ามีการคีย์พ.ศ.แค่ 2 หลักหรือไม่ได้คีย์มาเลย
                        const today = new Date();
                        const thisYear = today.getFullYear() + 543;
                        b[2]=thisYear;
                    } 
                    //======== Update ค่าบน input =========//
                    myObj.value = b[0]+"-"+b[1]+"-"+b[2];

                    //========= คำนวณค่าที่แท้จริงเพื่อใส่ลงใน variable สำหรับเก็บลง db
                    const c = (parseInt(b[2]) - 543) + "-" + b[1] + "-" + b[0];
                    const d = new Date((b[2] - 543) + "-" + b[1] + "-" + b[0]);
                    if (a != null && a != "") {
                        if (isNaN(d)) {
                            showAlert.show("รูปแบบวันที่ไม่ถูกต้อง", "DANGER", () => {
                                myObj.focus();
                            });
                            return false;
                        }
                    }

                    myObj.value = myObj.value.replace(/\//g, "-");
                    return true;
                }

            } else {
                showAlert.show("รูปแบบวันที่ไม่ถูกต้อง", "DANGER", () => {
                    myObj.focus();
                });
                return false;
            }
        }
        return true;
    }

    function prepare_preview() {

        var start_date = document.getElementById("start_confer_date").value;
        var end_date = document.getElementById("end_confer_date").value;

        if (start_date != null && start_date != "0000-00-00") start_date = convertToSmartDateShort(start_date);
        if (end_date != null && end_date != "0000-00-00") end_date = convertToSmartDateShort(end_date);

        const sendData = {
            "topic": document.getElementById('topic_confer').value,
            "picture_URL": document.getElementById('picture_URL').value,
            "content": CKEDITOR.instances.detail.getData(),
            "type_confer": document.getElementById("type_confer").value,
            "info1": start_date,
            "info2": end_date,
            "formName": "previewconference.php"
        };
        manage_conference.preview_HTML(sendData);
    }
    $(function() {

    });
</script>

<section id="editContent">
    <h3><?php echo $headline . " งานประชุม"?></h3>
    <form method="POST" name="formedit" id="formedit">
        <div class="d-table w-100 mt-3">
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">* หัวข้อการประชุม</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="หัวข้อข่าว" class="w-75" title="" maxlength="100" id="topic_confer" name="topic_confer" value='<?php echo ($command == "UPDATE" ? $row["topic_confer"] : "") ?>'></div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">ภาพประชาสัมพันธ์งานประชุม</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <a href="javascript:manage_conference.preview_Image('show',document.getElementById('previewImage').src);"><img src="<?php echo ($command == "UPDATE" ? $row["picture_URL"] : $default_image) ?>" style="max-height:200px;width:auto;max-width:500px" id="previewImage"></a><br>
                    <button class="btnEDIT" type="button" onclick="manage_conference.showAlbum('',1,'picture_URL','show');">ภาพจากอัลบัม</button>
                    <button class="btnEDIT" type="button" onclick="uploadPicture('block');">อัพโหลดภาพ</button>
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">* ประเภทงานประชุม</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <select id="type_confer" name="type_confer">
                        <option value="" <?php echo ($command == "UPDATE" && $row["type_confer"] == "" ? "selected" : "") ?>>กรุณาเลือก</option>
                        <option value="ภายในโรงพยาบาล" <?php echo ($command == "UPDATE" && $row["type_confer"] == "ภายในโรงพยาบาล" ? "selected" : "") ?>>ภายในโรงพยาบาล</option>
                        <option value="ภายนอกโรงพยาบาล" <?php echo ($command == "UPDATE" && $row["type_confer"] == "ภายนอกโรงพยาบาล" ? "selected" : "") ?>>ภายนอกโรงพยาบาล</option>
                    </select>
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">สถานที่จัดงาน</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <input type="text" style="width:50%" name="place" id="place" placeholder="ชื่อสถานที่จัดงาน" value="<?php echo ($command == "UPDATE" ? $row["place"] : ""); ?>">
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">วันที่เริ่มจัดงาน (วว-ดด-พ.ศ.)</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <input type="text" style="width:50%" name="dsp_start_confer_date" id="dsp_start_confer_date" placeholder="เช่น 02-03-2565 คือวันที่ 2 เดือนมีนาคม พ.ศ.2565" value="<?php echo ($command == "UPDATE" && $row["start_confer_date"] != null ? $format_start_confer_date : "00-00-0000"); ?>" onchange="return valid_DATE(this)">
                    ถ้าไม่มีให้ปล่อยไว้
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:200px">สิ้นสุดวันจัดงาน (วว-ดด-พ.ศ.)</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <input type="text" style="width:50%" name="dsp_end_confer_date" id="dsp_end_confer_date" placeholder="เช่น 02-03-2565 คือวันที่ 2 เดือนมีนาคม พ.ศ.2565" value="<?php echo ($command == "UPDATE" && $row["end_confer_date"] != null ? $format_end_confer_date : "00-00-0000"); ?>" onchange="return valid_DATE(this)">
                    ถ้าไม่มีให้ปล่อยไว้
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end align-top" style="width:200px">เนื้อหา</div>
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
        </div>
        <div class="d-table w-100 mt-3">
            <div class="ps-2 text-center d-table-cell">
                <?php
                if ($command == "UPDATE") {
                    echo "<button type=\"button\" class=\"btnDelete\" style=\"margin-right:2em\" onclick=\"javascript:manage_conference.deleteContent('conference',$id);\"><img src=\"../images/icon_delete.png\" class=\"icon_picture\"> ลบเนื้อหานี้</button>";
                }
                ?>
                <button type="button" class="btnOK" onclick="javascript:prepare_preview();"><img src="../images/icon_preview_white.png" class="icon_picture"> Preview</button>
                <button type="button" class="btnOK" onclick="javascript:validate_data();"><img src="../images/icon_save.png" class="icon_picture">บันทึก</button>
                <button type="button" class="btnCancel" onclick="javascript:doCancel();"><img src="../images/ic_close.png" class="icon_picture"> ยกเลิก</button>
            </div>
        </div>
        <input type="hidden" id="picture_URL" value="<?php echo ($command == "UPDATE" && $row["picture_URL"] != null ? $row["picture_URL"] : $default_image); ?>">
        <input type="hidden" id="start_confer_date" value="<?php echo ($command == "UPDATE" && $row["start_confer_date"] != null ? $row["start_confer_date"] : ""); ?>">
        <input type="hidden" id="end_confer_date" value="<?php echo ($command == "UPDATE" && $row["end_confer_date"] != null ? $row["end_confer_date"] : ""); ?>">
        <input type="hidden" id="username" value="<?php echo $_SESSION["username"] ?>">
        <input type="hidden" id="command" name="command" value="<?php echo $command; ?>">
        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
    </form>
</section>
<section id="showpicture">
    <!-- show picture -->
    <div class="bg-grey" id="showFullPicture" style="background-image:url(<?php echo $image_source ?>);">
    </div>
    <div>
        <button type="button" class="btnCancel" onclick="manage_conference.preview_Image('hide','');"><img src="../images/ic_close.png"> ปิดหน้าต่าง</button>
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