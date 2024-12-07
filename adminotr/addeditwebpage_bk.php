<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";

$default_status = "Y";
$command = "UPDATE";

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $command = "UPDATE";
    $headline = "แก้ไข";
} else {
    $command = "INSERT";
    $headline = "เพิ่ม";
}
?>
<script src="ckeditor/ckeditor.js"></script>
<script>






    $(function() {
        const id = "<?php echo $id ?>";
        if (id != "" && id != null && id != undefined) manage_webpage.view(id);
    });
</script>
<section id="editContent">
    <h3><?php echo $headline . " หน้าเวปเพจย่อย" ?></h3>
    <form method="POST" name="formedit" id="formedit">
        <div class="d-table w-100 mt-3">
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:150px">ชื่อหน้าเวปเพจ</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="text" placeholder="หัวข้อข่าว" class="w-75" title="" maxlength="100" id="webpage_name" name="webpage_name"></div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:150px">เนื้อหา</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <textarea rows="10" cols="80" title="" id="content" name="content" onchange="this.value=this.value.replace(/'/g,'&quot;');"></textarea><br>
                    <script>
                        CKEDITOR.editorConfig = function( config ) {
                            config.font_names = 'Prompt;TS-sarabun;' + config.font_names;
                        };
                        CKEDITOR.replace('content', {
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
                                    items: ['NumberedList', 'BulletedList', 'Indent', '-', 'Blockquote','-','JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
                                },
                                {
                                    name: 'links',
                                    items: ['Link', 'Unlink', 'Anchor']
                                },
                                {
                                    name: 'insert',
                                    items: ['Image','Table','HorizontalRule','Smiley']
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
                                { name: 'colors', items: [ 'TextColor', 'BGColor' ] }
                            ]
                        });
                    </script>
                    <!-- <div class="d-inline-block small-preview-picture">Pic#1</div> <div class="d-inline-block small-preview-picture">Pic#2</div> <div class="d-inline-block small-preview-picture">Pic#3</div> <div class="d-inline-block small-preview-picture">Pic#4</div> <div class="d-inline-block small-preview-picture">Pic#5</div> -->
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:150px">แสดงในหน้าเวป</div>
                <div class="ps-2 smallerFont w-100 column-input"><input type="checkbox" title="ถ้าไม่ระบุจะให้ค่าเริ่มต้นเป็นแสดง" class="ms-1 me-1" id="status1" name="status1" checked onclick="manage_webpage.updateCheckBox(this);">แสดง</div>
            </div>
        </div>
        <div class="d-table w-100 mt-3">
            <div class="ps-2 text-center d-table-cell">
                <!-- previewHTML(document.forms.formedit); -->
                <?php
                if ($command == "UPDATE") {
                    echo "<button type=\"button\" class=\"btnDelete\" style=\"margin-right:2em\" onclick=\"javascript:manage_webpage:manage_webpage.deleteContent('$id');\"><img src=\"../images/icon_delete.png\" class=\"icon_picture\"> ลบเนื้อหานี้</button>";
                }
                ?>
                <button type="button" class="btnOK" onclick="manage_webpage.goPreviewHTML();"><img src="../images/icon_preview_white.png" class="icon_picture"> Preview</button>
                <button type="button" class="btnOK" onclick="manage_webpage.validate_data();"><img src="../images/icon_save.png" class="icon_picture">บันทึก</button>
                <button type="button" class="btnCancel" onclick="manage_webpage.doCancel();"><img src="../images/ic_close.png" class="icon_picture"> ยกเลิก</button>
            </div>
        </div>
        <input type="hidden" id="status" name="status" value="Y">
        <input type="hidden" id="username" name="username" value="<?php echo $_SESSION['username']?>">
        <input type="hidden" id="command" name="command" value="<?php echo $command; ?>">
        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
    </form>
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