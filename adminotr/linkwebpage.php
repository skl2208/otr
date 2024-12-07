<?php
session_start();

include "inc/header.php";
include "inc/config.php";
include "../include/checkadmin.php";

$sql = "SELECT id,parent_id,menu_name,level,seq,link_url,IF(is_item!='Y','MENU','LINK URL') AS menutype  FROM menu WHERE parent_id in (3,5) order by parent_id,level,seq";
$result = $conn->query($sql);
?>
<script>
    function doCancel() {
        window.open("index.php", "_top");
    }

    function updateDataInForm(formObj) {
        alert("change");
    }

    function validate_data(objForm) {

        const check_kind_of_link = objForm.elements.kind_of_link;
        const check_internal_URL = objForm.elements.internal_URL;
        const check_external_URL = objForm.elements.external_URL;
        const menu_id = objForm.elements.menu_name;

        if(check_kind_of_link.value=="") {
            showAlert.show("กรุณาระบุชนิดของการเชื่อมโยงกับหน้าเพจ","WARN",()=>{
                check_kind_of_link.focus();
            });
            return false
        } else {
            if(check_kind_of_link.value=="N") {
                if(check_internal_URL.value=="") {
                    showAlert.show("กรุณาระบุเวปเพจจากเมนู","WARN",()=>{
                        check_internal_URL.focus();
                    });
                    return false
                }
            } else if (check_kind_of_link.value=="Y") {
                if(check_external_URL.value=="") {
                    showAlert.show("กรุณาใส่ URL ของเพจภายนอก","WARN",()=>{
                        check_external_URL.focus();
                    });
                    return false
                }
            }
        }
        
        if(check_kind_of_link.value=="N") {
            check_external_URL.value = "";
        } else {
            check_internal_URL.value = "";
        }
        const inputData = {
            menu_id : menu_id.value,
            webpage_id : check_internal_URL.value,
            external_URL : check_external_URL.value,
            is_external : check_kind_of_link.value
        };

        manage_menu.save(inputData);
    }
    function preview_HTML(id) {
        if(id!="" && id!=0) {
            window.open('../contentpage.php?id=' + id, '_preview_page', 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no');
        } else {
            showAlert.show("ไม่มีข้อมูลการสร้างเพจนี้","WARN");
        }
    }
    function update_Kind_of_link(value) {
        switch (value) {
            case "N":
                document.getElementById("external_URL").disabled = true;
                document.getElementById("internal_URL").disabled = false;
                document.getElementById("preview_Btn").style.display = "inline-block";
                break;
            case "Y":
                document.getElementById("internal_URL").disabled = true;
                document.getElementById("preview_Btn").style.display = "none";
                document.getElementById("external_URL").disabled = false;
                break;
            default:
                document.getElementById("internal_URL").disabled = false;
                document.getElementById("external_URL").disabled = false;
                document.getElementById("preview_Btn").style.display="inline-block";
                break;
        }
    }
    
    $(function() {
        //======= initialize variable in Form =======//
        const menu_name_select = document.getElementById("menu_name");

        menu_name_select.focus();
        menu_name_select.addEventListener('click', manage_menu.updateForm(menu_name_select.value));
    });
</script>
<section id="editContent">
    <h3>เชื่อมโยงเพจย่อยกับเมนู</h3>
    <form name="formedit" id="formedit">
        <div class="d-table w-100 mt-3">
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:300px">เลือกเมนูที่ต้องการเชื่อมกับหน้าเวปย่อย</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <select name="menu_name" id="menu_name" onchange="manage_menu.updateForm(this.value);">
                        <?php
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                if ($row["menutype"] == "MENU") {
                                    echo "<optgroup label=\"" . $row["menu_name"] . "\"></optgroup>";
                                } else {
                                    echo "<option value=" . $row["id"] . ">" . $row["menu_name"] . "</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:300px">เชื่อมโยงกับหน้าเพจแบบใด</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <select name="kind_of_link" id="kind_of_link" data-role="none" onchange="update_Kind_of_link(this.value);">
                        <option value="">กรุณาระบุ</option>
                        <option value="N">เวปเพจที่สร้างโดย CMS</option>
                        <option value="Y">เวปเพจของภายนอก</option>
                    </select>
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:300px">เชื่อมโยงกับหน้าเพจภายใน</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <select name="internal_URL" id="internal_URL">
                        <option value="">กรุณาเลือก</option>
                        <?php
                        $sql = "SELECT webpage_name,id FROM webpage WHERE status='Y' ORDER BY webpage_name";
                        $result1 = $conn->query($sql);
                        if ($result1 && $result1->num_rows > 0) {
                            while ($row1 = $result1->fetch_assoc()) {
                                echo "<option value=\"" . $row1["id"] . "\">" . $row1["webpage_name"] . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <button type="button" class="btnOK" id="preview_Btn" onclick="preview_HTML(document.getElementById('internal_URL').value);">Preview</button>
                </div>
            </div>
            <div style="display:table-row">
                <div class="ps-2 smallerFont head-row text-end" style="width:300px">เชื่อมโยงกับหน้าเพจภายนอก (URL)</div>
                <div class="ps-2 smallerFont w-100 column-input">
                    <input type="text" style="width:90%" maxlength="100" name="external_URL" id="external_URL" placeholder="ระบุ URL เช่น https://example.com/webpage.html">
                </div>
            </div>
        </div>
        <div class="d-table w-100 mt-3">
            <div class="ps-2 text-center d-table-cell">
                <button type="button" class="btnOK" onclick="validate_data(document.forms.formedit);"><img src="../images/icon_save.png">บันทึก</button> <button type="button" class="btnCancel" onclick="doCancel();"><img src="../images/ic_close.png"> ยกเลิก</button>
            </div>
        </div>
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