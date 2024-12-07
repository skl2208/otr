<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";
?>
<h3>ภาพกิจกรรม/ข่าว</h3>
<section class="nav-header">
    <div class="d-block">
        <div class="d-inline-block p-2 border-info active"><a href="javascript:activeSection('uploadpicture')" id="choice_1"><img src="../images/icon_upload_white.png" class="icon-picture">อัพโหลดภาพ</a></div>
        <div class="d-inline-block p-2 border-info"><a href="javascript:activeSection('albumpicture')" id="choice_2"><img src="../images/icon_gear_white.png" class="icon-picture">จัดการภาพในคลัง</a></div>
    </div>
</section>
<section id="uploadpicture" class="alter-section position-relative mt-3">
    <div style="min-height:100px;overflow-y:auto;">
        <img src="../images/empty_image.png" style="width:300px;height:auto;border:2px solid gray;margin-bottom:5px" id="showPreviewPicture">
    </div>
    <div>
        <form id="form" method="post" action="javascript:upload_image(document.getElementById('form'));">
            <input type="file" name="inputFile" id="inputFile" placeholder="เลือกไฟล์">
            <input type="hidden" name="typeupload" id="typeupload" value="activity">
            <button class="btnOK"><img src="../images/icon_upload_white.png" class="icon-picture"> อัพโหลด</button><br>
            <input type="text" name="image_desc" id="image_desc" value="" class="hide-first" placeholder="คำอธิบายภาพ" style="width:300px"><br>
            <select name="typenews" id="typenews" class="mt-1 hide-first">
                <optgroup label="กรุณาเลือกหมวดคลังภาพที่ต้องการจัดเก็บ">
                <?php
                $sql = "SELECT catagory FROM pic_catagory ORDER BY catagory";
                $result_tmp = $conn->query($sql);
                if ($result_tmp && $result_tmp->num_rows > 0) {
                    while ($row_select = $result_tmp->fetch_assoc()) {
                        $default_selected = "";
                        if($row_select["catagory"]=="กิจกรรมทั่วไป") {
                            $default_selected = "selected";
                        }
                        echo "<option value=\"" . $row_select["catagory"] . "\" $default_selected>" . $row_select["catagory"] . "</option>";
                    }
                }
                ?>
                </optgroup>
            </select>
        </form>
    </div>
    <div class="text-center mt-4">
        <button type="button" class="btnOK hide-first" onclick="saveUploadActivityPicture($('#typenews').val(),$('#image_desc').val());">
            <img src="../images/icon_save.png" class="icon-picture"> บันทึก
        </button>
    </div>
</section>
<section id="albumpicture" class="alter-section position-relative mt-3">
    <div>
        <span class="biggerFont">เลือกจากอัลบัม</span>
        <select name="catagory" id="catagory" onchange="listActivityAlbum(this.value,1,'','show');">
            <option value="" selected>ทุกอัลบัม</option>
            <?php
            $sql = "SELECT id,catagory FROM pic_catagory ORDER BY catagory";
            echo $sql . "<br>";
            $result2 = $conn->query($sql);
            $option_typenews = array();
            if ($result2) {
                if ($result2->num_rows > 0) {
                    while ($row2 = $result2->fetch_assoc()) {
                        $option_typenews[] = $row2["catagory"];
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
<section id="editActivityPicture" class="alter-section position-relative mt-3">
    <div class="d-table w-100 mt-3">
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">ภาพ</div>
            <div class="ps-2 smallerFont w-100 column-input"><img src="../images/empty_image.png" style="height:200px;width:auto" id="showPreviewActitityPicture"></div>
        </div>
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">URL</div>
            <div class="ps-2 smallerFont w-100 column-input"><input type="hidden" name="image_url_ActivityPicture" id="image_url_ActivityPicture"><button onclick="copyTextToClipboard($('#image_url_ActivityPicture').val());" class="btnEDIT" title="คัดลอกที่อยู่ของภาพ เก็บบน Clipboard ชั่วคราว">Copy URL to clipboard</button></div>
        </div>        
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">หมวดภาพ</div>
            <div class="ps-2 smallerFont w-100 column-input">
                <select name="catagoryActivityPicture" id="catagoryActivityPicture">
                    <option value="" selected>กรุณาเลือก</option>
                    <?php
                    foreach ($option_typenews as $showOption) {
                        echo "<option value=\"$showOption\" >{$showOption}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">คำบรรยายภาพ</div>
            <div class="ps-2 smallerFont w-100 column-input"><input type="text" title="" class="ms-1 me-1" id="image_desc_Update" name="image_desc_Update"></div>
        </div>        
        <div style="display:table-row">
            <div class="ps-2 smallerFont head-row text-end" style="width:200px">แสดงในหน้าเวป</div>
            <div class="ps-2 smallerFont w-100 column-input">
                <input type="checkbox" title="" class="ms-1 me-1" id="status1" name="status1" onclick="updateCheckBox(this);">แสดง
                <input type="hidden" name="editActivityID" id="editActivityID">
            </div>
        </div>
    </div>
    <div class="d-table w-100 mt-3">
        <div class="ps-2 text-center d-table-cell">
            <button class="btnOK" onclick="saveUpdateActivityPicture($('#editActivityID').val(),$('#catagoryActivityPicture').val(),$('#image_desc_Update').val(),$('#status1').val());"><img src="../images/icon_save.png">บันทึก</button>
            <button type="button" class="btnCancel" onclick="doCancel();"><img src="../images/ic_close.png" class="icon-picture"> ปิดหน้าต่าง</button>
        </div>
    </div>
</section>
<div class="spinner-border text-info" id="spinner"></div>
<div id="showToast"></div>
<script>
    function doCancel() {
        editActivityPicture.close();
    }

    function updateCheckBox(thisobj) {

        if (thisobj.checked) {
            thisobj.value = "Y";
        } else {
            thisobj.value = "N";
        }
    }
    $(function() {
        document.getElementById("editActivityPicture").style.display = "none";
        document.getElementById("albumpicture").style.display = "none";
        document.getElementById("uploadpicture").style.display = "block";
        listActivityAlbum('', 1, '', 'show');
    });
</script>
<?php
$conn->close();
?>