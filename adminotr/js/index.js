var url_collection = {
    "vdo_list": "ws/listvdo_ws.php",
    "vdo_detail": "ws/listvdodetail_ws.php",
    "vdo_insertupdate": "ws/insertupdate_vdo_ws.php",
    "pic_catagory_detail": "ws/pic_catagory_detail_ws.php",
    "pic_catagory_insert": "ws/insertupdate_pic_catagory_ws.php",
    "insert_tmp_news": "ws/insert_tmp_news_preview_ws.php",
    "delete_tmp_news": "ws/delete_tmp_news_preview_ws.php",
    "officer_insertupdate": "ws/insertupdate_officer_ws.php",
    "resident_insertupdate": "ws/insertupdate_resident_ws.php",
    "download_insertupdate": "ws/insertupdate_download_ws.php",
    "resident_group_insertupdate": "ws/insertupdate_residentgroup.php",
    "research_insertupdate": "ws/insertupdate_research.php",
    "tmp_webpage": "ws/tmp_webpage_preview_ws.php",
    "webpage_list": "ws/list_webpage_ws.php",
    "webpage_detail": "ws/webpage_detail_ws.php",
    "webpage_insertupdate": "ws/webpage_insertupdate_ws.php",
    "menu_link_webpage": "ws/menu_link_webpage.php",
    "conference_list": "ws/conference_list.php",
    "conference_insertupdate": "ws/conference_insertupdate.php",
    "conference_tmp_insert" : "ws/tmp_conference_preview.php",
    "list_menu" : "ws/menu_list_ws.php",
    "insertupdate_department_menu" : "ws/menu_insertupdate.php"
};

var insertImage = (obj1, indexTagHTML) => {

    const startPosition = obj1.selectionStart;
    const endPosition = obj1.selectionEnd;
    const tagHTML = [
        '<img src="" title="">',
        '<img src="" title="">',
        '<img src="" title="">'
    ];

    if (indexTagHTML == 0) {
        obj1.setRangeText(tagHTML[indexTagHTML], startPosition, startPosition, 'select');
        obj1.focus();
    } else if (indexTagHTML == 1) {
        albumPicture('block');

    } else if (indexTagHTML == 2) {

    }
};
class Manage_Component {

    constructor(viewpage_for_addedit, target_id, shown_id, shown_pic_id, callbackURL) {
        this.addedit_page = viewpage_for_addedit + "?id=";
        this.target_id = target_id;
        this.shown_id = shown_id;
        this.shown_pic_id = shown_pic_id;
        this.callbackURL = callbackURL;
    }
    initupload() {
        $("#showPreviewPicture").attr("src", "../images/empty_image.png");
        document.forms.formuploadimage.reset();
        $("#uploadpicture").show();
        $("#main").hide();
    }
    show(id) {
        window.location.href = this.addedit_page + id;
    }
    add() {
        window.location.href = this.addedit_page;
    }
    preview_Image(command, url_Image) {
        if (command == "hide") {
            $("#showFullPicture").css("background-image", "url('')");
            document.getElementById("showpicture").style.display = "none";
            document.getElementById("editContent").style.display = "block";
        } else {
            if (url_Image != null && url_Image != "") {
                $("#showFullPicture").css("background-image", "url('" + url_Image + "')");
            }
            document.getElementById("showpicture").style.display = "block";
            document.getElementById("editContent").style.display = "none";
        }
    }

    showAlbum(catagory_name, pageno, idName, command) {

        $("#showListAlbumPicture").show();

        if (command == "show" || command == "on") {
            document.getElementById("albumpicture").style.display = "block";
            document.getElementById("editContent").style.display = "none";

            if (pageno == null) {
                pageno = 1;
            }
            var inData = {
                "pageno": pageno,
                "catagory": catagory_name,
                "id": idName,
                "command": command
            };
            const saved_inData = JSON.parse(localStorage.getItem("inData"));

            if (inData.id == undefined || inData.id == null || inData.id == "") {
                inData.id = saved_inData.id;
            }
            if (catagory_name == undefined || catagory_name == null) {
                catagory_name = "";
            } else {
                inData.catagory = catagory_name;
            }
            localStorage.setItem("inData", JSON.stringify(inData));

            if (catagory_name != "0") {

                localStorage.setItem("inData", JSON.stringify(inData));
                $("#spinner").show();

                $.post("listalbum_ws.php", inData, () => {
                }).done((ret_data) => {
                    const json_ret_data = JSON.parse(ret_data);
                    if (json_ret_data.total > 0) {
                        const listItem = json_ret_data.info;
                        $("#showListAlbumPicture").empty();
                        listItem.forEach(function (ret_list) {

                            var strtxt = "";

                            strtxt = "<div class=\"col-3 text-center bg-white p-1\"><a href=\"javascript:preview_Image('" + ret_list.image_url + "','show');\" title=\"" + ret_list.image_desc + "\">";
                            strtxt += "<img src=\"" + ret_list.image_url + "\" class=\"p-1\"><div>" + ret_list.catagory + "</div></a></div>";

                            $("#showListAlbumPicture").append(strtxt);
                        });
                        var max_page = Math.ceil(parseInt(json_ret_data.total) / parseInt(json_ret_data.rows_per_page));
                        //======= Blind Paging No =======//
                        var strtxt2 = "";
                        $("#displayPaging").empty();
                        for (let i = 0; i < max_page; i++) {
                            // if (i != 0) {
                            strtxt2 = "<li class=\"page-item " + ((i + 1 == json_ret_data.current_page) ? "active" : "") + "\">";
                            strtxt2 += "<a class=\"page-link\" href=\"javascript:manage_conference.showAlbum(document.getElementById('catagory').value," + (i + 1) + ",'" + inData.id + "','show')\">" + (i + 1) + "</a></li>";
                            $("#displayPaging").append(strtxt2);
                            //}
                        }
                        $("#spinner").hide();
                    } else {
                        $("#spinner").hide();
                        strtxt = "<div class=\"col-12 text-center mt-2 bg-white p-1\">ไม่พบข้อมูล</div>";
                        $("#showListAlbumPicture").html(strtxt);
                    }
                }).fail((ret_data) => {
                    $("#spinner").hide();
                    strtxt = "<div class=\"col-12 text-center mt-2 bg-white p-1\">ไม่พบข้อมูล</div>";
                    $("#showListAlbumPicture").html(strtxt);
                });
            }
        } else {
            document.getElementById("albumpicture").style.display = "none";
            document.getElementById("editContent").style.display = "block";
        }
    }
    view(media_URL) {
        window.open(media_URL, "_blank", 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no');
    }
    addAttach() {
        $("#main").hide();
        $("#uploadpicture").show();
    }
    updateValAttach() {

        const a = document.getElementById(this.target_id).value;

        if (a == "" || a == null || a == undefined) {
            $("#addBtn").show();
            $("#viewBtn").hide();
            $("#delBtn").hide();
        } else {
            $("#addBtn").hide();
            $("#viewBtn").show();
            $("#delBtn").show();
        }
    }

    deleteAttach() {
        let target_id = this.target_id;
        let shown_id = this.shown_id;

        document.getElementById(target_id).value = "";
        document.getElementById(shown_id).innerHTML = "";
        $("#viewBtn").hide();
        $("#delBtn").hide();
        $("#addBtn").show();
    }
    cancel_addAttach() {
        $("#showPreviewPicture").attr("src", "../images/empty_image.png");
        document.forms.formupload.reset();
        $("#main").show();
        $("#uploadpicture").hide();
    }
    save_addAttach() {
        $("#main").show();
        $("#uploadpicture").hide();
    }
    uploadAttach(formObj) {
        $("#spinner").show();

        var formData = new FormData(formObj);
        let target_id = this.target_id;
        let shown_id = this.shown_id;

        $.ajax({
            url: "ws/upload_file.php",
            type: "post",
            data: formData,
            processData: false, //Not to process data
            contentType: false, //Not to set contentType
            success: function (data) {
                $("#spinner").hide();
                const returnMsg = JSON.parse(data);
                if (returnMsg.status == "OK") {
                    showToast("Upload สำเร็จ");
                    document.getElementById(target_id).value = returnMsg.url;
                    document.getElementById(shown_id).innerHTML = returnMsg.url;
                    setTimeout(() => {
                        $("#viewBtn").show();
                        $("#delBtn").show();
                        $("#addBtn").hide();
                        $("#main").show();
                        $("#uploadpicture").hide();
                    }, 1000);
                } else {
                    showToast("Upload Fail!");
                }
            },
            fail: () => {
                $("#spinner").hide();
                showToast("Upload Fail!");
            }
        });
    }
    saveInformation() {

    }
    close(urlGoback) {
        window.location.href = urlGoback;
    }

    deleteContent(tableContent, id_Content) {

        showConfirm.show("ยืนยันการลบข้อมูลหน้านี้", () => {
            const request_data = {
                id: id_Content,
                tablename: tableContent
            };

            $.post("ws/delete_rowid_table_ws.php", request_data, () => {

            }).done(ret_data => {
                const ret_jdata = JSON.parse(ret_data);
                if (ret_jdata.result == "SUCCESS") {
                    showToast("ลบรายการนี้เรียบร้อยแล้ว");
                    setTimeout(() => {
                        this.close(this.callbackURL);
                    }, 1500);
                }
            }).fail(ret_data => {
                showAlert.show("ไม่สามารถลบข้อมูลนี้ได้", "WARN");
            });
        }, null);
    }
}
var manage_menu = {
    "save": (inputData) => {
        showSpinner.show();
        $.ajax({
            url: url_collection.menu_link_webpage,
            type: 'PUT',
            data: inputData,
            success: function (ret_result) {
                showSpinner.hide();
                const ret_json = JSON.parse(ret_result);
                if (ret_json.message.toUpperCase() == "OK") {
                    showToast("Update การเชื่อมโยงเมนูกับหน้าเวปเรียบร้อยแล้ว", "NORMAL");
                } else {

                    showToast("ไม่มีการ Update", "WARN");
                }
            },
            fail: function (ret_error) {
                showSpinner.hide();
                showAlert.show("เชื่อมโยงเมนูกับหน้าเพจไม่สำเร็จ\nกรุณาตรวจสอบข้อมูลอีกครั้ง", "DANGER");
            }
        });
    },
    "updateForm": (id_Of_Menu) => {
        const url = url_collection.menu_link_webpage + "?id=" + id_Of_Menu;
        $.get(url, () => {

        }).done(ret_data => {
            const ret_json = JSON.parse(ret_data);
            if (ret_json.message == "OK") {
                document.getElementById("kind_of_link").value = ret_json.info.is_external;

                if (ret_json.info.is_external == "Y") {
                    document.getElementById("external_URL").value = ret_json.info.link_url;
                    document.getElementById("external_URL").disabled = false;
                    document.getElementById("internal_URL").value = "";
                    document.getElementById("internal_URL").disabled = true;
                    document.getElementById("preview_Btn").style.display = "none";
                } else {

                    document.getElementById("external_URL").value = "";
                    document.getElementById("external_URL").disabled = true;
                    document.getElementById("internal_URL").value = ret_json.info.link_url;
                    document.getElementById("internal_URL").disabled = false;
                    document.getElementById("preview_Btn").style.display = "inline-block";
                }
            }
        }).fail(ret_error => {

        });

    }
}
var manage_webpage = {
    "list": (pageno, searchTxt, select_status) => {

        const inData = {
            "pageno": pageno,
            "searchTxt": searchTxt,
            "select_status": select_status
        }

        var strHTML = "";

        //========== ล้างหน้าจอ ===============
        $(".list-webpage").empty();
        //========== ล้างตัวบอกหน้า ============
        $("#displayPaging").empty();
        //========= Update ตัวแปรในฟอร์ม ======
        $("#inSearch").val(searchTxt);

        $("#spinner").show();

        $.post(url_collection.webpage_list, inData, () => {

        }).done((ret_data) => {
            $("#spinner").hide();

            const json_resultdata = JSON.parse(ret_data);

            if (json_resultdata.message === "OK" && json_resultdata.total_rec > 0) {

                $("#num_row").html(json_resultdata.total_rec);

                strHTML = "<div style=\"display:table-row\">";
                strHTML += "<div style=\"width:10%;\" class=\"ps-2 smallerFont head-row\">แก้ไข</div>";
                strHTML += "<div style=\"width:30%;\" class=\"ps-2 smallerFont head-row\">ชื่อเวปย่อย</div>";
                strHTML += "<div style=\"width:10%;\" class=\"ps-2 smallerFont head-row\">โดยผู้อัพเดท</div>";
                strHTML += "<div style=\"width:15%;\" class=\"ps-2 smallerFont head-row\">อัพเดทเมื่อ</div>";
                strHTML += "<div style=\"width:10%;\" class=\"ps-2 smallerFont head-row\">โดยผู้สร้าง</div>";
                strHTML += "<div style=\"width:15%;\" class=\"ps-2 smallerFont head-row\">สร้างเมื่อ</div>";
                strHTML += "<div style=\"width:10%;\" class=\"ps-2 smallerFont head-row\">สถานะ</div>";
                strHTML += "</div>";

                var i = 0;

                (json_resultdata.info).forEach((val1) => {

                    strHTML += "<div style=\"display:table-row\">";
                    strHTML += "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\"><button class=\"btnEDIT\" title=\"แก้ไขรายการนี้\" onclick=\"javascript:manage_webpage.editWebPage('" + val1.id + "');\">" + (++i) + "</button></div>";
                    strHTML += "<div style=\"width:30%;\" class=\"ps-2 border-bottom dp-data\">" + val1.webpage_name + "</div>";
                    strHTML += "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\">" + val1.user_update + "</div>";
                    strHTML += "<div style=\"width:15%;\" class=\"ps-2 border-bottom dp-data\">" + convertToSmartDate(val1.update_date) + "</div>";
                    strHTML += "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\">" + val1.user_create + "</div>";
                    strHTML += "<div style=\"width:15%;\" class=\"ps-2 border-bottom dp-data\">" + convertToSmartDate(val1.create_date) + "</div>";
                    strHTML += "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\">" + (val1.status == "Y" ? "<span class=\"text-success\">ใช้งาน</span>" : "<span class=\"text-danger\">ไม่ใช้งาน</span>") + "</div>";
                    strHTML += "</div>";
                });
                $(".list-webpage").append(strHTML);

                //======= Blind Paging No =======//
                var strtxt2 = "";
                const max_page = json_resultdata.total_page;

                if (max_page > 0) {
                    for (i = 0; i < max_page; i++) {

                        strtxt2 = "<li class=\"page-item " + ((i + 1 == json_resultdata.current_page) ? "active" : "") + "\">";
                        strtxt2 += "<a class=\"page-link\" href=\"javascript:manage_webpage.list(" + (i + 1) + ",'" + searchTxt + "','" + select_status + "')\">" + (i + 1) + "</a></li>";
                        $("#displayPaging").append(strtxt2);

                    }
                }

            } else {
                $("#num_row").html("0");
                strHTML = "<div class=\"mt-3 biggerFont\">ไม่มีข้อมูล</div>";
                $(".list-website").append(strHTML);
            }
        }).fail((ret_data) => {
            $("#spinner").hide();
            $("#num_row").html("0");
            strHTML = "<div class=\"mt-3 biggerFont\">ไม่มีข้อมูล</div>";
            $(".list-website").append(strHTML);
        });

    },
    "view": (id) => {
        const url = url_collection.webpage_detail + "?id=" + id;
        $.get(url, () => {
        }).done(ret => {
            const ret_json = JSON.parse(ret);
            if (ret_json.message == "OK") {
                document.getElementById("webpage_name").value = ret_json.info.webpage_name;
                document.getElementById("id").value = ret_json.info.id;
                document.getElementById("content").value = (ret_json.info.content == null ? "" : ret_json.info.content);
                document.getElementById("status").value = ret_json.info.status;

                //====== Update CKEditor ==========
                $("#spinner").show();
                setTimeout(() => {
                    CKEDITOR.instances["content"].setData(ret_json.info.content, {
                        callback: function () {
                            this.checkDirty(); // true
                        }
                    });
                    $("#spinner").hide();
                }, 1000);


                //====== Update checkbox ==========
                if (ret_json.info.status == "Y") {
                    document.getElementById("status1").checked = true;
                } else {
                    document.getElementById("status1").checked = false;
                }
            }
        }).fail(retError => {

        });
    },
    "addWebPage": () => {
        location.href = "addeditwebpage.php";

    },
    "editWebPage": (id) => {
        location.href = "addeditwebpage.php?id=" + id;
    },
    "deleteContent": (id) => {
        showConfirm.show("ยืนยันการลบข้อมูลหน้านี้", () => {
            const request_data = {
                id: id,
                tablename: 'webpage'
            };

            $.post("ws/delete_rowid_table_ws.php", request_data, () => {

            }).done(ret_data => {
                ret_jdata = JSON.parse(ret_data);
                if (ret_jdata.result == "SUCCESS") {
                    showToast("ลบรายการนี้เรียบร้อยแล้ว");
                    setTimeout(() => {
                        doCancel();
                    }, 2000);
                }
            }).fail(ret_data => {
                showAlert.show("ไม่สามารถลบข้อมูลนี้ได้");
            });
        });
    },
    "save": (objectInForm) => {
        const id = objectInForm.elements.id.value;
        const webpage_name = objectInForm.elements.webpage_name.value;
        const content = objectInForm.elements.content.value;
        const status = objectInForm.elements.status.value;
        const command = objectInForm.elements.command.value;
        const username = objectInForm.elements.username.value;
        const inData = {
            "id": id,
            "webpage_name": webpage_name,
            "content": content,
            "status": status,
            "username": username
        };

        if (command == "UPDATE") {
            $.ajax({
                url: url_collection.webpage_insertupdate,
                type: 'PUT',
                data: inData,
                success: function (resultdata) {
                    const json_resultdata = JSON.parse(resultdata);
                    if (json_resultdata.message == "OK") {
                        showToast("บันทึกข้อมูลเรียบร้อยแล้ว", "OK", () => {
                            setTimeout(() => {
                                window.location.href = "list_webpage.php";
                            }, 1500);
                        });
                    } else if (json_resultdata.message == "FAIL Update") {
                        showToast("ไม่มีการบันทึกข้อมูล อาจเนื่องจากไม่มีการเปลี่ยนแปลงข้อมูลที่อัพเดทเข้ามา", "WARN");
                    } else {
                        showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
                    }
                },
                fail: function () {
                    showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
                }
            });
        } else if (command == "INSERT") {

            $.post(url_collection.webpage_insertupdate, inData, () => {
            }).done(resultData => {

                const json_resultdata = JSON.parse(resultData);

                if (json_resultdata.message == "OK") {
                    showToast("บันทึกข้อมูลเรียบร้อยแล้ว", "OK", () => {
                        setTimeout(() => {
                            window.location.href = "list_webpage.php";
                        }, 1500);
                    });
                } else if (json_resultdata.message == "FAIL Insert") {
                    alert(resultData);
                    showToast("มีข้อผิดพลาดในการเพิ่มหน้าเวปย่อย กรุณาลองใหม่อีกครั้ง", "WARN");
                } else {
                    showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
                }
            }).fail(errorMsg => {
                alert(JSON.stringify(errorMsg));
                showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
            });
        }

    },
    "previewHTML": (contentHTML) => {
        contentHTML = contentHTML.replace(/'/g, '"');
        // 1. insert ข้อมูลลงใน db ชั่วคราว
        // 2. เรียกดู โดยระบุ id ที่ได้จากการ insert
        // 3. ลบข้อมูลออกหลังจากที่ปิดหน้าต่าง
        const inData = {
            "content": contentHTML
        };

        $.post(url_collection.tmp_webpage, inData, (ret_data) => {
        }).done((ret_data) => {
            const json_ret_data = JSON.parse(ret_data);
            window.open('../previewwebpage.php?id=' + json_ret_data.info.id, '_preview_page', 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no');
            //cleartmp(json_ret_data.info.id);
        });
    }
}
var manage_download = {
    "show": (id) => {
        window.location.href = "addeditdownload.php?id=" + id;
    },
    "view": (media_URL) => {
        window.open(media_URL, "_blank", 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no');

    },
    "save": (inForm) => {
        var id = inForm.elements.id.value;
        const link_name = inForm.elements.link_name.value;
        const link_url = inForm.elements.link_url.value;
        const description = inForm.elements.description.value;
        var seq = inForm.elements.seq.value;
        const status = inForm.elements.status.value;

        if (id == null || id == undefined || id == 0) { id = 0; }
        if (seq == null || seq == undefined || parseInt(seq) == 0 || seq == "") { seq = 999; }

        const inData = {
            "id": id,
            "link_name": link_name,
            "link_url": link_url,
            "description": description,
            "seq": seq,
            "status": status
        }
        if (id != 0) {
            //============ Update ============
            $("#spinner").show();
            $.ajax({
                url: url_collection.download_insertupdate,
                type: 'PUT',
                data: inData,
                success: function (resultdata) {
                    const json_resultdata = JSON.parse(resultdata);
                    if (json_resultdata.message == "OK") {
                        $("#spinner").hide();
                        showToast("บันทึกข้อมูลเรียบร้อยแล้ว", "NORMAL");
                        setTimeout(() => {
                            manage_download.close();
                        }, 1000);

                    } else if (json_resultdata.message == "FAIL") {
                        $("#spinner").hide();
                        showToast("ไม่มีการบันทึกข้อมูล อาจเนื่องจากไม่มีการเปลี่ยนแปลงข้อมูลที่อัพเดทเข้ามา", "WARN");
                    } else {
                        $("#spinner").hide();
                        showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
                    }
                },
                fail: function () {
                    $("#spinner").hide();
                    showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
                }
            });

        } else {
            //============ Insert ============
            $("#spinner").show();
            $.post(url_collection.download_insertupdate, inData, (resultdata) => {
            }).done((resultdata) => {
                const json_resultdata = JSON.parse(resultdata);
                if (json_resultdata.message == "OK") {
                    $("#spinner").hide();
                    showToast("บันทึกข้อมูลเรียบร้อยแล้ว");
                    setTimeout(() => {
                        manage_download.close();
                    }, 1000);
                } else {
                    $("#spinner").hide();
                    showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
                }
            }).fail((resultdata) => {
                $("#spinner").hide();
                showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
            });
        }

    },
    "add": () => {
        window.location.href = "addeditdownload.php";
    },
    "addAttach": () => {
        $("#main").hide();
        $("#uploadpicture").show();
    },
    "deleteAttach": () => {
        $("#link_url").val("");
        $("#showlink_url").empty().html("");
        $("#viewBtn").hide();
        $("#delBtn").hide();
        $("#addBtn").show();
    },
    "save_addAttach": (formObj) => {
        $("#spinner").show();

        var formData = new FormData(formObj);

        $.ajax({
            url: "ws/upload_file.php",
            type: "post",
            data: formData,
            processData: false, //Not to process data
            contentType: false, //Not to set contentType
            success: function (data) {
                $("#spinner").hide();
                const returnMsg = JSON.parse(data);
                if (returnMsg.status == "OK") {
                    showToast("Upload สำเร็จ ");
                    $("#link_url").val(returnMsg.url);
                    $("#showlink_url").html(returnMsg.url);
                    $("#viewBtn").show();
                    $("#delBtn").show();
                    $("#addBtn").hide();

                } else {
                    showToast("Upload Fail!");
                }
            },
            fail: () => {
                $("#spinner").hide();
                showToast("Upload Fail!");
            }
        });
        $("#main").show();
        $("#uploadpicture").hide();
    },
    "close": () => {
        window.location.href = "list_download.php";
    }
};
// ใช้ควบคู่กับ HTMLdoc id="editActivityPicture"
var editActivityPicture = {
    "id": "",
    "image_url": "",
    "image_desc": "",
    "typenews": "",
    "status1": "",
    "init": (inID) => {
        document.getElementById("editActivityPicture").style.display = "block";
        document.getElementById("albumpicture").style.display = "none";

        const inData = {
            "id": inID
        }
        const url = "listalbum_detail_ws.php";

        $.post(url, inData, () => {

        }).done((ret_data) => {

            const json_ret_data = JSON.parse(ret_data);

            if (json_ret_data.message == "OK") {
                //blind data
                this.id = json_ret_data.info.id;
                this.image_desc = json_ret_data.info.image_desc;
                this.image_url = json_ret_data.info.image_url;
                this.typenews = json_ret_data.info.catagory;
                this.status1 = json_ret_data.info.status;

                var catagoryID = document.getElementById("catagoryActivityPicture");
                var image_desc_input = document.getElementById("image_desc_Update");
                var id_input = document.getElementById("editActivityID");

                $("#showPreviewActitityPicture").attr("src", json_ret_data.info.image_url);
                $("#image_url_ActivityPicture").val(json_ret_data.info.image_url);
                image_desc_input.value = json_ret_data.info.image_desc;
                id_input.value = json_ret_data.info.id;

                if (json_ret_data.info.status == "Y") {
                    document.getElementById("status1").checked = true;
                } else {
                    document.getElementById("status1").checked = false;
                }
                updateSelectMenu('catagoryActivityPicture', json_ret_data.info.catagory);

                //document.getElementById(catagoryID).value = json_ret_data.info.catagory;
                //$("select").selectmenu("refresh", true);
            }

        }).fail(() => {

        });
    },
    "close": () => {
        document.getElementById("editActivityPicture").style.display = "none";
        document.getElementById("albumpicture").style.display = "block";
    },
    "save": () => { // บันทึกข้อมูลผ่าน Web Service

    }
}

var copyTextToClipboard = (inText) => {

    var tempTextarea = document.createElement("textarea");
    document.body.appendChild(tempTextarea);
    tempTextarea.value = inText; //save main text in it
    tempTextarea.select(); //select textarea contenrs
    document.execCommand("copy");
    document.body.removeChild(tempTextarea);
    showToast("Copy แล้ว");

}

var manage_pic_catagory = {
    "edit": (id) => {
        if (id == null || id == undefined) {
            //Insert
            $("#editContent h3").html("เพิ่มรายการ");
        } else {
            //Update
            $("#editContent h3").html("แก้ไขรายการ");
        }
        const inData = {
            "id": id
        }
        $.post(url_collection.pic_catagory_detail, inData, () => {

        }).done((ret_data) => {
            const json_ret_data = JSON.parse(ret_data);
            if (json_ret_data.message == "OK") {
                //blinding
                $("#catagory").val(json_ret_data.info.catagory);
                $("#catagory_desc").val(json_ret_data.info.catagory_desc);
                $("#id").val(json_ret_data.info.id);
                $("#status").val(json_ret_data.info.status);
            }
        }).fail((ret_data) => {

        });
        $("#editContent2").show();
        $("#main").hide();
    },
    "save": (inForm) => {

        const id = inForm["id"].value;
        const catagory = inForm["catagory"].value;
        const catagory_desc = inForm["catagory_desc"].value;
        const status = inForm["status"].value;
        const inData = {
            "id": id,
            "catagory": catagory,
            "catagory_desc": catagory_desc,
            "status": status
        }
        var msgTxt = {
            "text": "",
            "type": ""
        }
        if (id == null || id == undefined || id == "") {
            // ===========   Insert   ===========
            const inData_insert = {
                "catagory": catagory,
                "catagory_desc": catagory_desc,
                "status": status
            }

            $.post(url_collection.pic_catagory_insert, inData_insert, (resultdata) => {

            }).done((resultdata) => {
                const json_resultdata = JSON.parse(resultdata);
                if (json_resultdata.message == "OK") {
                    document.forms[0].reset();
                    document.getElementsByTagName("editContent2").display = "none";
                    $("#main").show();
                    msgTxt.text = "บันทึกข้อมูลเรียบร้อยแล้ว";
                    msgTxt.type = "";
                    window.localStorage.setItem("msgtxt", JSON.stringify(msgTxt));
                } else {
                    msgTxt.text = "บันทึกข้อมูลไม่สำเร็จ";
                    msgTxt.type = "DANGER";
                    window.localStorage.setItem("msgtxt", JSON.stringify(msgTxt));
                }
            }).fail((resultdata) => {
                msgTxt.text = "บันทึกข้อมูลไม่สำเร็จ";
                msgTxt.type = "DANGER";
                window.localStorage.setItem("msgtxt", JSON.stringify(msgTxt));
            });
        } else {
            // ===========   Update   ===========
            $.ajax({
                url: url_collection.pic_catagory_insert,
                type: 'PUT',
                data: inData,
                success: function (resultdata) {
                    const json_resultdata = JSON.parse(resultdata);
                    if (json_resultdata.message == "OK") {
                        document.forms[0].reset();
                        document.getElementsByTagName("editContent2").display = "none";
                        $("#main").show();
                        msgTxt.text = "บันทึกข้อมูลเรียบร้อยแล้ว";
                        msgTxt.type = "";
                        window.localStorage.setItem("msgtxt", JSON.stringify(msgTxt));
                    } else if (json_resultdata.message == "FAIL Update") {
                        msgTxt.text = "ไม่มีการบันทึกข้อมูล";
                        msgTxt.type = "WARN";
                        window.localStorage.setItem("msgtxt", JSON.stringify(msgTxt));
                    } else {
                        msgTxt.text = "บันทึกข้อมูลไม่สำเร็จ";
                        msgTxt.type = "DANGER";
                        window.localStorage.setItem("msgtxt", JSON.stringify(msgTxt));
                    }
                },
                fail: function () {
                    msgTxt.text = "บันทึกข้อมูลไม่สำเร็จ";
                    msgTxt.type = "DANGER";
                    window.localStorage.setItem("msgtxt", JSON.stringify(msgTxt));
                }
            });
        }
        document.getElementsByTagName("editContent2").display = "none";
        $("#main").show();
    },
    "cancel": () => {
        document.getElementsByTagName("editContent2").display = "none";
        $("#main").show();
    }
}
var manage_banner = new Manage_Component('addeditbanner.php', 'download_url', 'showPreviewPicture', '');
manage_banner = {
    "edit": (id) => {
        if (id == null || id == undefined) {
            //Insert
            $("#editContent h3").html("เพิ่มรายการ");
        } else {
            //Update
            $("#editContent h3").html("แก้ไขรายการ");
        }
        const inData = {
            "id": id
        }
        $.post(url_collection.pic_catagory_detail, inData, () => {

        }).done((ret_data) => {
            const json_ret_data = JSON.parse(ret_data);
            if (json_ret_data.message == "OK") {
                //blinding
                $("#catagory").val(json_ret_data.info.catagory);
                $("#catagory_desc").val(json_ret_data.info.catagory_desc);
                $("#id").val(json_ret_data.info.id);
                $("#status").val(json_ret_data.info.status);
            }
        }).fail((ret_data) => {

        });
        $("#editContent2").show();
        $("#main").hide();
    },
    "uploadBanner": () => {
        document.getElementById("uploadbanner").style.display = "block";
        document.getElementById("editContent").style.display = "none";
    },
    "upload_image": (formObj) => {

        $("#spinner").show();

        var formData = new FormData(formObj);

        $.ajax({
            url: "upload_image.php",
            type: "post",
            data: formData,
            processData: false, //Not to process data
            contentType: false, //Not to set contentType
            success: function (data) {
                $("#spinner").hide();
                const returnMsg = JSON.parse(data);
                
                if (returnMsg.status == "OK") {
                    showToast("Upload สำเร็จ ","NORMAL");
                    $("#showPreviewPicture").attr("src", returnMsg.url);
                    $("#showPreview").val(returnMsg.url);
                    $("#image_link").val(returnMsg.url);
                    $("#showFullPicture").css("background-image", "url('" + returnMsg.url + "')");
                    $(".hide-first").css("visibility", "visible");
                    document.getElementById("saveUploadBanner").style.display = "inline-block";
                } else {
                    showToast("Upload Fail (01)!","DANGER");
                    $(".hide-first").css("visibility", "hidden");
                }
            },
            fail: (errorMsg) => {
                $("#spinner").hide();
                showToast("Upload Fail (02)!","DANGER");
            }
        });
    },
    "closeUploadBanner": () => {
        document.getElementById("editContent").style.display = "block"
        document.getElementById("uploadbanner").style.display = "none";
    },
    "showAlbum": () => {
        document.getElementById("editContent").style.display = "none";
        document.getElementById("albumpicture").style.display = "block";
    },
    "closeAlbum": () => {
        document.getElementById("editContent").style.display = "block";
        document.getElementById("albumpicture").style.display = "none";
    },
    "preview_Image" : (command, url_Image)=> {
        if (command == "hide") {
            $("#showFullAlbumPicture").css("background-image", "url('')");
            document.getElementById("showpicture").style.display = "none";
            document.getElementById("albumpicture").style.display = "block";
        } else {
            if (url_Image != null && url_Image != "") {
                $("#showFullAlbumPicture").css("background-image", "url('" + url_Image + "')");
            }
            document.getElementById("showpicture").style.display = "block";
            document.getElementById("albumpicture").style.display = "none";
        }
    },
    "save": (inForm) => {

        const id = inForm["id"].value;
        const catagory = inForm["catagory"].value;
        const catagory_desc = inForm["catagory_desc"].value;
        const status = inForm["status"].value;
        const seq = inForm["seq"].value;
        const inData = {
            "id": id,
            "catagory": catagory,
            "catagory_desc": catagory_desc,
            "seq" : seq,
            "status": status
        }
        var msgTxt = {
            "text": "",
            "type": ""
        }
        if (id == null || id == undefined || id == "") {
            // ===========   Insert   ===========
            const inData_insert = {
                "catagory": catagory,
                "catagory_desc": catagory_desc,
                "seq" : seq,
                "status": status
            }

            $.post(url_collection.pic_catagory_insert, inData_insert, (resultdata) => {

            }).done((resultdata) => {
                const json_resultdata = JSON.parse(resultdata);
                if (json_resultdata.message == "OK") {
                    document.forms[0].reset();
                    document.getElementsByTagName("editContent2").display = "none";
                    $("#main").show();
                    msgTxt.text = "บันทึกข้อมูลเรียบร้อยแล้ว";
                    msgTxt.type = "";
                    window.localStorage.setItem("msgtxt", JSON.stringify(msgTxt));
                } else {
                    msgTxt.text = "บันทึกข้อมูลไม่สำเร็จ";
                    msgTxt.type = "DANGER";
                    window.localStorage.setItem("msgtxt", JSON.stringify(msgTxt));
                }
            }).fail((resultdata) => {
                msgTxt.text = "บันทึกข้อมูลไม่สำเร็จ";
                msgTxt.type = "DANGER";
                window.localStorage.setItem("msgtxt", JSON.stringify(msgTxt));
            });
        } else {
            // ===========   Update   ===========
            $.ajax({
                url: url_collection.pic_catagory_insert,
                type: 'PUT',
                data: inData,
                success: function (resultdata) {
                    const json_resultdata = JSON.parse(resultdata);
                    if (json_resultdata.message == "OK") {
                        document.forms[0].reset();
                        document.getElementsByTagName("editContent2").display = "none";
                        $("#main").show();
                        msgTxt.text = "บันทึกข้อมูลเรียบร้อยแล้ว";
                        msgTxt.type = "";
                        window.localStorage.setItem("msgtxt", JSON.stringify(msgTxt));
                    } else if (json_resultdata.message == "FAIL Update") {
                        msgTxt.text = "ไม่มีการบันทึกข้อมูล";
                        msgTxt.type = "WARN";
                        window.localStorage.setItem("msgtxt", JSON.stringify(msgTxt));
                    } else {
                        msgTxt.text = "บันทึกข้อมูลไม่สำเร็จ";
                        msgTxt.type = "DANGER";
                        window.localStorage.setItem("msgtxt", JSON.stringify(msgTxt));
                    }
                },
                fail: function () {
                    msgTxt.text = "บันทึกข้อมูลไม่สำเร็จ";
                    msgTxt.type = "DANGER";
                    window.localStorage.setItem("msgtxt", JSON.stringify(msgTxt));
                }
            });
        }
        document.getElementsByTagName("editContent2").display = "none";
        $("#main").show();
    },
    "cancel": () => {
        document.getElementsByTagName("editContent2").display = "none";
        $("#main").show();
    }
}
var manage_officer = {
    "new_attach_file": "",
    "init": () => {
        const command = document.getElementById("command").value;
        const current_picture = document.getElementById("picture_URL").value;

        document.getElementById("uploadPicture").style.display = "none";
        document.getElementById("uploadAttachFile").style.display = "none";
        document.getElementById("albumpicture").style.display = "none";

        if (command == "ADD" || (command == "EDIT" && current_picture.indexOf("a_person.png") > 0)) {
            document.getElementById("del_picture_btn").style.display = "none";
        } else {
            document.getElementById("del_picture_btn").style.display = "inline-block";
        }
    },
    "save": (inForm, ckeditor_Obj) => {
        var id = inForm.elements.id.value;
        const titlename = inForm.elements.titlename.value;
        const name = inForm.elements.name.value;
        const surname = inForm.elements.surname.value;
        const position = inForm.elements.position.value;
        const controlunit = inForm.elements.controlunit.value;
        const download_URL = inForm.elements.download_URL.value;
        const picture_URL = inForm.elements.picture_URL.value;
        const seq = inForm.elements.seq.value;
        const status1 = inForm.elements.status1.value;

        if (ckeditor_Obj != null && ckeditor_Obj != undefined) {
            var graduation = ckeditor_Obj.getData();
        }

        if (id == null || id == undefined || id == 0) { id = 0; }
        if (seq == null || seq == undefined || parseInt(seq) == 0) { seq = 999; }
        if (name == null || name == "") {
            showAlert.show("กรุณาระบุชื่อให้ถูกต้อง", "DANGER", () => {
                document.getElementById("name").focus();
            });
            return false;
        }

        if (surname == null || surname == "") {
            showAlert.show("กรุณาระบุนามสกุลให้ถูกต้อง", "DANGER", () => {
                document.getElementById("surname").focus();
            });
            return false;
        }

        const inData = {
            "id": id,
            "titlename": titlename,
            "name": name,
            "surname": surname,
            "position": position,
            "controlunit": controlunit,
            "graduation": graduation,
            "download_URL": download_URL,
            "picture_URL": picture_URL,
            "seq": seq,
            "status": status1
        }

        if (id != 0) {
            $.ajax({
                url: url_collection.officer_insertupdate,
                type: 'PUT',
                data: inData,
                success: function (resultdata) {
                    const json_resultdata = JSON.parse(resultdata);
                    if (json_resultdata.message == "OK") {
                        showToast("บันทึกข้อมูลเรียบร้อยแล้ว");
                        setTimeout(() => {
                            manage_officer.cancel();
                        }, 1000);
                    } else if (json_resultdata.message == "FAIL Update") {
                        showToast("ไม่มีการบันทึกข้อมูล อาจเนื่องจากไม่มีการเปลี่ยนแปลงข้อมูลที่อัพเดทเข้ามา", "WARN");
                    } else {
                        showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
                    }
                },
                fail: function () {
                    showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
                }
            });

        } else {

            $.post(url_collection.officer_insertupdate, inData, (resultdata) => {

            }).done((resultdata) => {
                const json_resultdata = JSON.parse(resultdata);
                if (json_resultdata.message == "OK") {
                    showToast("บันทึกข้อมูลเรียบร้อยแล้ว");
                    setTimeout(() => {
                        manage_officer.cancel();
                    }, 1000);
                } else {
                    showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
                }
            }).fail((resultdata) => {
                showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
            });
        }
    },
    "view": (media_URL) => {
        window.open(media_URL, "_blank", 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no');
    },
    "deleteAttach": () => {
        $("#download_URL").val("");
        $("#showdownload_URL").empty().html("");
        $("#viewBtn").hide();
        $("#delBtn").hide();
        $("#addBtn").show();
    },
    "addAttach": () => {
        $("#main").hide();
        $("#uploadAttachFile").show();
        $("#save_attach_btn").hide();
    },
    "upload_AttachFile": (formObj) => {

        var formData = new FormData(formObj);

        $("#spinner").show();
        $.ajax({
            url: "ws/upload_attachfile.php",
            type: "post",
            data: formData,
            processData: false, //Not to process data
            contentType: false, //Not to set contentType
            success: function (data) {
                $("#spinner").hide();
                const returnMsg = JSON.parse(data);
                if (returnMsg.status == "OK") {
                    $("#save_attach_btn").show();
                    manage_officer.new_attach_file = returnMsg.url;
                    showToast("Upload สำเร็จ ");
                } else {
                    showToast("Upload Fail!", "DANGER");
                }
            },
            fail: (errorMsg) => {
                $("#spinner").hide();
                showToast("Upload Fail!!", "DANGER");
            }
        });
    },
    "save_addAttach": (command) => {
        if (command != 'cancel') {
            //======= Save attach file ==========
            $("#download_URL").val(manage_officer.new_attach_file);
            $("#showdownload_URL").html(manage_officer.new_attach_file);
            $("#viewBtn").show();
            $("#delBtn").show();
            $("#addBtn").hide();
        }
        document.getElementById("inputFileAttach").value = "";
        document.getElementById("uploadAttachFile").style.display = "none";
        document.getElementById("main").style.display = "block";
    },
    "cancel_addAttach": () => {
        $("#main").show();
        $("#uploadAttachFile").hide();
    },
    "cancel": () => {
        window.location.href = "list_officer.php";
    },
    "uploadPicture": () => {
        $("#main").hide();
        $("#uploadPicture").show();
        document.getElementById("save_upload_picture_btn").style.display = "none";
    },
    "uploadFromAlbum": (catagory_name, pageno) => {

        $("#main").hide();
        $("#albumpicture").show();

        if (catagory_name == null || catagory_name == undefined) catagory_name = "";
        if (pageno == null || pageno == undefined || pageno == 0) pageno = 1;

        $("#showListAlbumPicture").show();

        var inData = {
            "pageno": pageno,
            "catagory": catagory_name,
        };

        const saved_inData = JSON.parse(localStorage.getItem("inData"));

        if (catagory_name == undefined || catagory_name == null) {
            catagory_name = "";
        } else {
            inData.catagory = catagory_name;
        }

        localStorage.setItem("inData", JSON.stringify(inData));

        if (catagory_name != "0") {

            localStorage.setItem("inData", JSON.stringify(inData));

            $("#spinner").show();

            $.post("listalbum_ws.php", inData, () => {
            }).done((ret_data) => {
                const json_ret_data = JSON.parse(ret_data);

                if (json_ret_data.total > 0) {
                    const listItem = json_ret_data.info;

                    $("#showListAlbumPicture").empty();
                    listItem.forEach(function (ret_list) {

                        var strtxt = "";

                        strtxt = "<div class=\"col-3 text-center bg-white p-1\"><a href=\"javascript:manage_officer.preview_Image('" + ret_list.image_url + "','show');\" title=\"" + ret_list.image_desc + "\">";
                        strtxt += "<img src=\"" + ret_list.image_url + "\" class=\"p-1\"><div>" + ret_list.catagory + "</div></a></div>";

                        $("#showListAlbumPicture").append(strtxt);
                    });

                    var max_page = Math.ceil(parseInt(json_ret_data.total) / parseInt(json_ret_data.rows_per_page));

                    //======= Blind Paging No =======//
                    var strtxt2 = "";
                    $("#displayPaging").empty();
                    for (i = 0; i < max_page; i++) {
                        // if (i != 0) {
                        strtxt2 = "<li class=\"page-item " + ((i + 1 == json_ret_data.current_page) ? "active" : "") + "\">";
                        strtxt2 += "<a class=\"page-link\" href=\"javascript:manage_officer.uploadFromAlbum(document.getElementById('catagory').value," + (i + 1) + ")\">" + (i + 1) + "</a></li>";
                        $("#displayPaging").append(strtxt2);
                        //}

                    }

                    $("#spinner").hide();
                } else {

                    strtxt = "<div class=\"col-12 text-center mt-2 bg-white p-1\">ไม่พบข้อมูล</div>";
                    $("#showListAlbumPicture").html(strtxt);
                    $("#spinner").hide();
                }


            }).fail((ret_data) => {
                $("#spinner").hide();
                strtxt = "<div class=\"col-12 text-center mt-2 bg-white p-1\">ไม่พบข้อมูล</div>";
                $("#showListAlbumPicture").html(strtxt);
            });
        }


    },
    "preview_Image": (inURL, command) => {
        inURL = inURL.replace(/ /g, "%20");

        if (command == "show") {
            $("#showListAlbumPicture").hide();
            $("#albumpicture .preview").css("display", "block");
            $("#albumpicture .preview div:first-child").css("background-image", "url('" + inURL + "')");
            $("#changePictureFromAlbum").val(inURL);
        } else {
            $("#showListAlbumPicture").show();
            $("#albumpicture .preview").css("display", "none");
            $("#albumpicture .preview div:first-child").css("background-image", "url()");
        }

    },
    "saveFromAlbum": (inURL, des_obj) => {
        if (inURL != 'none') {

            $("#officer_picture").attr("src", inURL); // แสดงภาพในช่อง preview
            $("#picture_URL").val(inURL);

        }
        $("#albumpicture .preview").css("display", "none");
        $("#albumpicture .preview div:first-child").css("background-image", "url()");
        $("#main").show();
        $("#albumpicture").hide();

    },
    "cancel_Album": () => {
        $("#main").show();
        $("#albumpicture").hide();
    },
    "saveUploadPicture": (typeOfPicture) => {

        if (typeOfPicture != 'cancel') {

            const picture_source = $("#showPreviewPicture").attr("src");
            const inData = {
                "typenews": typeOfPicture,
                "image_url": picture_source
            };

            $.post("save_pictodb_ws.php", inData, () => {

            }).done((ret) => {
                const ret_json = JSON.parse(ret);
                if (ret_json.message == "OK") {
                    $("#showPreviewImage").attr("src", picture_source); // แสดงภาพในช่อง preview
                    $("#picture_URL").val(picture_source);
                    $("#officer_picture").attr("src", picture_source); // เปลี่ยนค่า src ให้เป็นตัวใหม่
                    document.getElementById("showPreviewPicture").src = "../images/empty_image.png";
                    document.getElementById("inputFile").value = "";
                    document.getElementById("uploadPicture").style.display = "none";
                    document.getElementById("main").style.display = "block";
                    document.getElementById("del_picture_btn").style.display = "inline-block";
                } else {
                    showAlert.show("ไม่สามารถ Upload ไฟล์ได้\n" + ret_json.message, "DANGER");
                }

            });


        } else {
            document.getElementById("showPreviewPicture").src = "../images/empty_image.png";
            document.getElementById("inputFile").value = "";
            document.getElementById("uploadPicture").style.display = "none";
            document.getElementById("main").style.display = "block";
        }


    },
    "deleteUploadPicture": () => {
        showConfirm.show("ต้องการลบภาพใช่หรือไม่", () => {
            document.getElementById("del_picture_btn").style.display = "none";
            document.getElementById("officer_picture").src = "../images/static_image/a_person.png";
            document.getElementById("picture_URL").value = "";
        }, () => {

        })
    }
}
var manage_vdo = {
    "view": (media_URL) => {
        window.open(media_URL, "_blank", 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no');
    },
    "save": (obj1) => {
        const src_clip = obj1["src_clip"].value;
        const vdo_url = obj1["vdo_url"].value;
        const vdo_desc = obj1["vdo_desc"].value;
        const status1 = obj1["status1"].value;
        const catagory = obj1["catagory"].value;
        const attach_file_url = obj1["attach_file_url"].value;
        const id = obj1["id"].value;
        const pin = obj1["pin"].value;

        var is_youtube = (src_clip == "Youtube" ? "Y" : "N");

        if (id == null || id == undefined || id == "") {
            command = "ADD";
        } else {
            command = "UPDATE";
        }

        var inData = {
            "id": id,
            "src_clip": src_clip,
            "is_youtube": is_youtube,
            "status": status1,
            "vdo_url": vdo_url,
            "vdo_desc": vdo_desc,
            "catagory": catagory,
            "attach_file_url": attach_file_url,
            "pin": pin,
            "command": command
        }

        if (command == "ADD") {

            //ทำการแปลง Code ที่มาจาก Youtube ให้เป็น Code Embed
            // เช่น https://www.youtube.com/watch?v=xPyOclcsYJk แปลงเป็น
            // https://www.youtube.com/embed/xPyOclcsYJk

            if (inData.is_youtube == "Y") {
                if ((inData.vdo_url).indexOf("embed") < 0) {
                    inData.vdo_url = (inData.vdo_url).replace('watch?v=', 'embed/');
                }
            }

            //==============================================
            $.post(url_collection.vdo_insertupdate, inData, () => {

            }).done((resultdata) => {
                const json_resultdata = JSON.parse(resultdata);
                if (json_resultdata.message == "OK") {
                    showToast("บันทึกลงในคลังภาพเรียบร้อยแล้ว");
                    setTimeout(() => {
                        window.location.href = "list_vdo.php";
                    }, 1000);
                } else {
                    showAlert.show("ไม่สามารถบันทึก VDO ได้", "DANGER");
                }
            }).fail(() => {
                showAlert.show("ไม่สามารถบันทึก VDO ได้", "DANGER");
            });
        } else if (command == "UPDATE") {
            $.ajax({
                url: url_collection.vdo_insertupdate,
                type: 'PUT',
                data: inData,
                success: function (resultdata) {

                    const json_resultdata = JSON.parse(resultdata);
                    if (json_resultdata.message == "OK") {
                        showToast("บันทึกลงในคลังภาพเรียบร้อยแล้ว");
                        setTimeout(() => {
                            window.location.href = "list_vdo.php";
                        }, 1000);
                    } else if (json_resultdata.message == "NOTHING UPDATE") {
                        showToast("ไม่มีการเปลี่ยนแปลง");
                    } else {
                        showToast("บันทึกไม่สำเร็จ :" + json_resultdata.message);
                    }
                },
                fail: function () {
                    showToast("บันทึกไม่สำเร็จ");
                }
            });
        }
    },
    "delete": (id) => {
        const request_data = {
            id: id,
            tablename: "vdo"
        };

        $.post("ws/delete_rowid_table_ws.php", request_data, () => {

        }).done(ret_data => {
            ret_jdata = JSON.parse(ret_data);
            if (ret_jdata.result == "SUCCESS") {
                showToast("ลบรายการนี้เรียบร้อยแล้ว");
                setTimeout(() => {
                    doCancel();
                }, 2000);
            }
        }).fail(ret_data => {
            showAlert.show("ไม่สามารถลบข้อมูลนี้ได้");
        });
    },
    "deleteAttach": () => {
        $("#attach_file_url").val("");
        $("#showattach_file_url").html("----- NO DOCUMENT -----");
        $("#viewBtn").hide();
        $("#delBtn").hide();
        $("#addBtn").show();
    },
    "addAttach": () => {
        $("#main").hide();
        $("#uploadpicture").show();
    },
    "save_addAttach": (formObj) => {
        $("#spinner").show();

        var formData = new FormData(formObj);

        $.ajax({
            url: "ws/upload_file.php",
            type: "post",
            data: formData,
            processData: false, //Not to process data
            contentType: false, //Not to set contentType
            success: function (data) {
                $("#spinner").hide();
                const returnMsg = JSON.parse(data);
                if (returnMsg.status == "OK") {
                    showToast("Upload สำเร็จ ");
                    $("#attach_file_url").val(returnMsg.url);
                    $("#showattach_file_url").html(returnMsg.url);
                    $("#viewBtn").show();
                    $("#delBtn").show();
                    $("#addBtn").hide();

                } else {
                    showToast("Upload Fail!");
                }
            },
            fail: () => {
                $("#spinner").hide();
                showToast("Upload Fail!");
            }
        });
        $("#main").show();
        $("#uploadpicture").hide();
    },
    "cancel_addAttach": () => {
        $("#main").show();
        document.getElementById("formupload").reset();
        $("#uploadpicture").hide();
    }
}
var manage_resident = {
    "url_image": "",
    "initupload": () => {
        $("#showPreviewPicture").attr("src", "../images/empty_image.png");
        document.forms.formuploadimage.reset();
        manage_resident.url_image = "";
        $("#uploadpicture").show();
        $("#main").hide();
    },
    "uploadImage": (myObj) => {
        $("#spinner").show();
        var formData = new FormData(myObj);

        $.ajax({
            url: "upload_image.php",
            type: "post",
            data: formData,
            processData: false, //Not to process data
            contentType: false, //Not to set contentType
            success: function (data) {
                $("#spinner").hide();

                const returnMsg = JSON.parse(data);
                if (returnMsg.status == "OK") {
                    showToast("Upload สำเร็จ ");
                    manage_resident.url_image = returnMsg.url;
                    $("#showPreviewPicture").attr("src", returnMsg.url);
                    $("#picture_URL").val(returnMsg.url);
                } else {
                    showToast("Upload Fail!", "DANGER");
                }
            },
            fail: () => {
                $("#spinner").hide();
                showToast("Upload Fail!", "DANGER");
            }
        });
    },
    "saveImage": () => {
        $("#uploadpicture").hide();
        $("#main").show();
        //====== แสดงภาพที่ upload ได้ใน form ============//
        $("#picture_URL_preview").attr("src", manage_resident.url_image).attr("title", manage_resident.url_image);
        $("#picture_URL").val(manage_resident.url_image);
    },
    "saveInformation": (formObj) => {
        const typesave = formObj.elements.command.value;
        const id = formObj.elements.id.value;
        const titlename = formObj.elements.titlename.value;
        const name = formObj.elements.name.value;
        const surname = formObj.elements.surname.value;
        const position = formObj.elements.position.value;
        const start_year = formObj.elements.start_year.value;
        const picture_URL = formObj.elements.picture_URL.value;
        const status1 = formObj.elements.status1.value;
        const inData = {
            "id": "",
            "titlename": titlename,
            "name": name,
            "surname": surname,
            "position": position,
            "start_year": start_year,
            "picture_URL": picture_URL,
            "status1": status1
        }

        if (id != null && id != undefined && id != 0) {
            inData.id = id;
        }
        if (typesave == "EDIT") {

            $.ajax({
                url: url_collection.resident_insertupdate,
                type: 'PUT',
                data: inData,
                success: function (resultdata) {
                    const json_resultdata = JSON.parse(resultdata);
                    if (json_resultdata.message == "OK") {
                        showToast("บันทึกข้อมูลเรียบร้อยแล้ว");
                        setTimeout(() => {
                            window.location.href = "list_resident.php";
                        }, 2000);
                    } else if (json_resultdata.message == "FAIL Update") {
                        showToast("ไม่มีการบันทึกข้อมูล อาจเนื่องจากไม่มีการเปลี่ยนแปลงข้อมูลที่อัพเดทเข้ามา", "WARN");
                    } else {
                        showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
                    }
                },
                fail: function () {
                    showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
                }
            });
        } else if (typesave == "ADD") {

            $.post(url_collection.resident_insertupdate, inData, () => {

            }).done((ret_data) => {
                const json_ret_data = JSON.parse(ret_data);
                if (json_ret_data.message == "OK") {
                    showToast("บันทึกข้อมูลเรียบร้อยแล้ว");
                    setTimeout(() => {
                        window.location.href = "list_resident.php";
                    }, 2000);
                } else {
                    showToast("บันทึกไม่สำเร็จ", "DANGER");
                }

            }).fail(() => {
                showToast("บันทึกไม่สำเร็จ", "DANGER");
            });
        }
    },
    "cancel": () => {
        $("#uploadpicture").hide();
        $("#main").show();
    }
}
var manage_residentgroup = {
    "url_image": "",
    "initupload": () => {
        $("#showPreviewPicture").attr("src", "../images/empty_image.png");
        document.forms.formuploadimage.reset();
        manage_resident.url_image = "";
        $("#uploadpicture").show();
        $("#main").hide();
    },
    "addAttach": () => {
        $("#main").hide();
        $("#uploadpicture").show();
    },
    "deleteAttach": () => {
        $("#download_url").val("");
        $("#showdownload_url").empty().html("----- NO DOCUMENT -----");
        $("#viewBtn").hide();
        $("#delBtn").hide();
        $("#addBtn").show();
    },
    "save_addAttach": (formObj) => {
        $("#spinner").show();

        var formData = new FormData(formObj);

        $.ajax({
            url: "ws/upload_file.php",
            type: "post",
            data: formData,
            processData: false, //Not to process data
            contentType: false, //Not to set contentType
            success: function (data) {
                $("#spinner").hide();
                const returnMsg = JSON.parse(data);
                if (returnMsg.status == "OK") {
                    showToast("Upload สำเร็จ ");
                    $("#download_url").val(returnMsg.url);
                    $("#showdownload_url").html(returnMsg.url);
                    $("#viewBtn").show();
                    $("#delBtn").show();
                    $("#addBtn").hide();

                } else {
                    showToast("Upload Fail!");
                }
            },
            fail: () => {
                $("#spinner").hide();
                showToast("Upload Fail!");
            }
        });
        $("#main").show();
        $("#uploadpicture").hide();
    },
    "saveInformation": (formObj) => {

        const id = formObj.elements.id.value;
        const group_name = formObj.elements.group_name.value;
        const download_url = formObj.elements.download_url.value;
        const status1 = formObj.elements.status1.value;
        const typesave = formObj.elements.command.value;
        const inData = {
            "id": id,
            "group_name": group_name,
            "download_url": download_url,
            "status1": status1
        }

        if (id != null && id != undefined && id != 0) {
            inData.id = id;
        }
        if (typesave == "EDIT") {

            $.ajax({
                url: url_collection.resident_group_insertupdate,
                type: 'PUT',
                data: inData,
                success: function (resultdata) {
                    const json_resultdata = JSON.parse(resultdata);
                    if (json_resultdata.message == "OK") {
                        showToast("บันทึกข้อมูลเรียบร้อยแล้ว");
                        setTimeout(() => {
                            window.location.href = "list_resident_group.php";
                        }, 2000);
                    } else if (json_resultdata.message == "FAIL Update") {
                        showToast("ไม่มีการบันทึกข้อมูล อาจเนื่องจากไม่มีการเปลี่ยนแปลงข้อมูลที่อัพเดทเข้ามา", "WARN");
                    } else {
                        showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
                    }
                },
                fail: function () {
                    showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
                }
            });
        } else if (typesave == "ADD") {

            $.post(url_collection.resident_group_insertupdate, inData, () => {

            }).done((ret_data) => {
                const json_ret_data = JSON.parse(ret_data);
                if (json_ret_data.message == "OK") {
                    showToast("บันทึกข้อมูลเรียบร้อยแล้ว");
                    setTimeout(() => {
                        window.location.href = "list_resident_group.php";
                    }, 2000);
                } else {
                    showToast("บันทึกไม่สำเร็จ", "DANGER");
                }

            }).fail(() => {
                showToast("บันทึกไม่สำเร็จ", "DANGER");
            });
        }
    },
    "view": (media_URL) => {
        window.open(media_URL, "_blank", 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no');

    },
    "cancel": () => {
        $("#uploadpicture").hide();
        $("#main").show();
    }
}
var manage_res = new Manage_Component('addeditresearch.php', 'download_url', 'showdownload_url', '');

//======== สำหรับงานวิจัย ========//
manage_res.saveInformation = (formObj) => {
    const id = formObj.elements.id.value;
    const topic = formObj.elements.topic.value;
    const name = formObj.elements.name.value;
    const advisor = formObj.elements.advisor.value;
    const content = formObj.elements.content.value;
    const group_name = formObj.elements.group_name.value;
    const download_url = formObj.elements.download_url.value;
    const status1 = formObj.elements.status.value;
    const typesave = formObj.elements.command.value;
    const inData = {
        "id": id,
        "topic": topic,
        "name": name,
        "advisor": advisor,
        "content": content,
        "group_name": group_name,
        "download_url": download_url,
        "status1": status1
    }
    if (id != null && id != undefined && id != 0) {
        inData.id = id;
    }
    if (typesave == "UPDATE") {

        $.ajax({
            url: url_collection.research_insertupdate,
            type: 'PUT',
            data: inData,
            success: function (resultdata) {
                const json_resultdata = JSON.parse(resultdata);
                if (json_resultdata.message == "OK") {
                    showToast("บันทึกข้อมูลเรียบร้อยแล้ว");
                    setTimeout(() => {
                        window.location.href = "list_research.php";
                    }, 1000);
                } else if (json_resultdata.message == "FAIL Update") {
                    showToast("ไม่มีการบันทึกข้อมูล อาจเนื่องจากไม่มีการเปลี่ยนแปลงข้อมูลที่อัพเดทเข้ามา", "WARN");
                } else {
                    showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
                }
            },
            fail: function () {
                showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
            }
        });
    } else if (typesave == "INSERT") {

        $.post(url_collection.research_insertupdate, inData, () => {

        }).done((ret_data) => {
            const json_ret_data = JSON.parse(ret_data);
            if (json_ret_data.message == "OK") {
                showToast("บันทึกข้อมูลเรียบร้อยแล้ว");
                setTimeout(() => {
                    window.location.href = "list_research.php";
                }, 2000);
            } else {
                showToast("บันทึกไม่สำเร็จ", "DANGER");
            }

        }).fail(() => {
            showToast("บันทึกไม่สำเร็จ", "DANGER");
        });
    }
}
var manage_news = new Manage_Component('addeditnews.php', null, null, null, 'list_news.php');

class Manage_Conference extends Manage_Component {

    list_conference(pageno, searchTxt, type_confer) {
        const inData = {
            "pageno": pageno,
            "searchTxt": searchTxt,
            "type_confer": type_confer
        }

        var strHTML = "";

        //========== ล้างหน้าจอ ===============
        $(".list-conference").empty();
        //========== ล้างตัวบอกหน้า ============
        $("#displayPaging").empty();

        $("#spinner").show();

        $.post(url_collection.conference_list, inData, () => {

        }).done((ret_data) => {

            $("#spinner").hide();

            const json_resultdata = JSON.parse(ret_data);
            
            if (json_resultdata.message === "OK" && json_resultdata.total_rec > 0) {

                $("#displayTotalRec").html("จำนวนรายการข้อมูล " + json_resultdata.total_rec + " row(s)");

                strHTML = "<div style=\"display:table-row\">";
                strHTML += "<div style=\"width:10%;\" class=\"ps-2 smallerFont head-row\">แก้ไข</div>";
                strHTML += "<div style=\"width:15%;\" class=\"ps-2 smallerFont head-row\">งานประชุม</div>";
                strHTML += "<div style=\"width:45%;\" class=\"ps-2 smallerFont head-row\">หัวข้อการประชุม</div>";
                strHTML += "<div style=\"width:15%;\" class=\"ps-2 smallerFont head-row\">ประเภท</div>";
                strHTML += "<div style=\"width:15%;\" class=\"ps-2 smallerFont head-row\">วันที่จัดงาน</div>";
                strHTML += "</div>";
                var i = 0;

                (json_resultdata.info).forEach((val1) => {

                    strHTML += "<div style=\"display:table-row\">";
                    strHTML += "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\"><button class=\"btnEDIT\" title=\"แก้ไข\" onclick=\"javascript:manage_conference.show('" + val1.id + "');\">" + (++i) + "</button></div>";
                    strHTML += "<div style=\"width:15%;\" class=\"ps-2 border-bottom dp-data\"><img src=\"" + val1.picture_URL + "\"></div>";
                    strHTML += "<div style=\"width:45%;\" class=\"ps-2 border-bottom dp-data\">" + val1.topic_confer + "</div>";
                    strHTML += "<div style=\"width:15%;\" class=\"ps-2 border-bottom dp-data\">" + val1.type_confer + "</div>";
                    strHTML += "<div style=\"width:15%;\" class=\"ps-2 border-bottom dp-data\">" + (val1.start_confer_date == null || val1.start_confer_date=="0000-00-00" ? "-" : convertToSmartDateShort(val1.start_confer_date)) + "</div>";
                    strHTML += "</div>";
                });
                $(".list-conference").append(strHTML);

                //======= Blind Paging No =======//
                var strtxt2 = "";
                const max_page = json_resultdata.total_page;

                if (max_page > 0) {
                    for (i = 0; i < max_page; i++) {

                        strtxt2 = "<li class=\"page-item " + ((i + 1 == json_resultdata.current_page) ? "active" : "") + "\">";
                        strtxt2 += "<a class=\"page-link\" href=\"javascript:manage_conference.list_conference(" + (i + 1) + ",'" + searchTxt + "','" + type_confer + "')\">" + (i + 1) + "</a></li>";
                        $("#displayPaging").append(strtxt2);

                    }
                }

            } else {
                $("#displayTotalRec").html("ไม่พบข้อมูล");
                strHTML = "<div class=\"mt-3 biggerFont\">ไม่มีข้อมูล</div>";
                $(".list-vdo").append(strHTML);
            }
        }).fail((ret_data) => {
            $("#spinner").hide();
            $("#displayTotalRec").html("ไม่พบข้อมูล");
            strHTML = "<div class=\"mt-3 biggerFont\">ไม่มีข้อมูล</div>";
            $(".list-vdo").append(strHTML);
        });
    }
    save(objectInForm) {
        const id = objectInForm.elements.id.value;
        const topic_confer = objectInForm.elements.topic_confer.value;
        const type_confer = objectInForm.elements.type_confer.value;
        const picture_URL = objectInForm.elements.picture_URL.value;
        const detail = objectInForm.elements.detail.value;
        const start_confer_date = objectInForm.elements.start_confer_date.value;
        const end_confer_date = objectInForm.elements.end_confer_date.value;
        const username = objectInForm.elements.username.value;
        const command = objectInForm.elements.command.value;
        const place = objectInForm.elements.place.value;
        const inData = {
            "id": id,
            "picture_URL": picture_URL,
            "topic_confer": topic_confer,
            "type_confer": type_confer,
            "detail": detail,
            "start_confer_date": start_confer_date,
            "end_confer_date": end_confer_date,
            "username": username,
            "place" : place
        };

        if (command == "UPDATE") {
            $.ajax({
                url: url_collection.conference_insertupdate,
                type: 'PUT',
                data: inData,
                success: function (resultdata) {
                    const json_resultdata = JSON.parse(resultdata);
                    if (json_resultdata.message == "OK") {
                        showToast("บันทึกข้อมูลเรียบร้อยแล้ว", "OK", () => {
                            setTimeout(() => {
                                window.location.href = "list_conference.php";
                            }, 1500);
                        });
                    } else if (json_resultdata.message == "FAIL Update") {
                        showToast("ไม่มีการบันทึกข้อมูล อาจเนื่องจากไม่มีการเปลี่ยนแปลงข้อมูลที่อัพเดทเข้ามา", "WARN");
                    } else {
                        showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
                    }
                },
                fail: function () {
                    showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
                }
            });
        } else if (command == "ADD") {

            $.post(url_collection.conference_insertupdate, inData, () => {
            }).done(resultData => {
                alert(resultData);
                const json_resultdata = JSON.parse(resultData);

                if (json_resultdata.message == "OK") {
                    showToast("บันทึกข้อมูลเรียบร้อยแล้ว", "OK", () => {
                        setTimeout(() => {
                            window.location.href = "list_conference.php";
                        }, 1500);
                    });
                } else if (json_resultdata.message == "FAIL Insert") {
                    showToast("มีข้อผิดพลาดในการเพิ่มหน้าเวปย่อย กรุณาลองใหม่อีกครั้ง", "WARN");
                } else {
                    showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
                }
            }).fail(errorMsg => {
                alert(JSON.stringify(errorMsg));
                showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
            });
        }
    }
    preview_HTML(inData) {
        // formName = ชื่อแบบฟอร์ม .php เพื่อใช้ Preview
        inData.content = inData.content.replace(/'/g, '"');

        $.post(url_collection.conference_tmp_insert, inData, (ret_data) => {
        }).done((ret_data) => {
            const json_ret_data = JSON.parse(ret_data);
            window.open('../'+inData.formName+'?id=' + json_ret_data.info.id, '_preview_page', 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no');
            //cleartmp(json_ret_data.info.id);
        });
    }
}

var manage_conference = new Manage_Conference('addeditconference.php', null, null, null, 'list_conference.php');
class Manage_department_menu extends Manage_Component {
    list(pageno) {
        const inData = {
            "pageno": pageno,
            "parent_id" : 2
        };
        var strHTML = "";
        //========== ล้างหน้าจอ ===============
        $(".list-department-menu").empty();
        //========== ล้างตัวบอกหน้า ============
        $("#displayPaging").empty();
        $("#spinner").show();
        $.post(url_collection.list_menu, inData, () => {
        }).done((ret_data) => {
            $("#spinner").hide();

            const json_resultdata = JSON.parse(ret_data);
            
            strHTML ="<div style=\"display:table-row\">";
            strHTML += "<div style=\"width:10%;\" class=\"ps-2 smallerFont head-row\">แก้ไข</div>";
            strHTML += "<div style=\"width:10%;\" class=\"ps-2 smallerFont head-row\">ลำดับแสดงผล</div>";
            strHTML += "<div style=\"width:40%;\" class=\"ps-2 smallerFont head-row\">ชื่อเมนู</div>";
            strHTML += "<div style=\"width:10%;\" class=\"ps-2 smallerFont head-row\">สถานะ</div>";
            strHTML += "</div>";

            if (json_resultdata.message === "OK" && json_resultdata.total_rec > 0) {

                $("#num_row").html(json_resultdata.total_rec);

                var i = 0;

                (json_resultdata.info).forEach((val1) => {
                    strHTML += "<div style=\"display:table-row\">";
                    strHTML += "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\"><button class=\"btnEDIT\" title=\"แก้ไขรายการนี้\" onclick=\"javascript:manage_department_menu.edit(" + val1.id + ");\">" + (++i) + "</button></div>";
                    strHTML += "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\">" + val1.seq + "</div>";
                    strHTML += "<div style=\"width:40%;\" class=\"ps-2 border-bottom dp-data\">" + val1.menu_name + "</div>";
                    strHTML += "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\">" + val1.status + "</div>";
                    strHTML += "</div>";
                });
                $(".list-department-menu").append(strHTML);
                //======= Blind Paging No =======//
                var strtxt2 = "";
                const max_page = json_resultdata.total_page;
               
                if (max_page > 0) {
                    for (let i = 0; i < max_page; i++) {
                        strtxt2 = "<li class=\"page-item " + ((i + 1 == json_resultdata.current_page) ? "active" : "") + "\">";
                        strtxt2 += "<a class=\"page-link\" href=\"javascript:manage_department_menu.list(" + (i + 1) + ")\">" + (i + 1) + "</a></li>";
                        $("#displayPaging").append(strtxt2);
                    }
                }

            } else {
                $("#num_row").html("0");
                strHTML = "<div class=\"mt-3 biggerFont\">ไม่มีข้อมูล</div>";
                $(".list-department-menu").append(strHTML);
            }
        }).fail((ret_data) => {
            $("#spinner").hide();
            $("#num_row").html("0");
            strHTML = "<div class=\"mt-3 biggerFont\">ไม่มีข้อมูล</div>";
            $(".list-department-menu").append(strHTML);
        });
    }
    edit(id) {
        location.href = this.addedit_page + id;
    }
    add(parent_id) {
        window.location.href = this.addedit_page + "&parent_id="+parent_id;
    }
    save(formName) {
        const id = formName.elements.id.value;
        const menu_name = formName.elements.menu_name.value;
        const status = formName.elements.status.value;
        const seq = formName.elements.seq.value;
        const parent_id = formName.elements.parent_id.value;

        if(id!=0 && id!=undefined && id!=null) {
            //============ Update ==============
            var inputData = {
                "id" : id,
                "menu_name" : menu_name,
                "status" : status,
                "seq" : seq
            };
            
            $.ajax({
                url : url_collection.insertupdate_department_menu ,
                data : inputData,
                method : "PUT",
                success : (ret_txt)=> {
                    const ret_result = JSON.parse(ret_txt);

                    if (ret_result.message == "OK") {
                        showToast("บันทึกข้อมูลเรียบร้อยแล้ว");
                        setTimeout(() => {
                            this.cancel();
                        }, 1000);
                    } else if (ret_result.message == "FAIL Update") {
                        showToast("ไม่มีการบันทึกข้อมูล อาจเนื่องจากไม่มีการเปลี่ยนแปลงข้อมูลที่อัพเดทเข้ามา", "WARN");
                    } else {
                        showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
                    }
                },
                fail: (ret_result)=> {
                    showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
                }
            });
        } else {
            //============ Insert ==============
            var inputData = {
                "parent_id" : parent_id,
                "menu_name" : menu_name,
                "status" : status,
                "seq" : seq
            };
            $.ajax({
                url : url_collection.insertupdate_department_menu ,
                data : inputData,
                method : "POST",
                success : (ret_txt)=> {

                    const ret_result = JSON.parse(ret_txt);

                    if (ret_result.message == "OK") {
                        showToast("บันทึกข้อมูลเรียบร้อยแล้ว");
                        setTimeout(() => {
                            this.cancel();
                        }, 1000);
                    } else if (ret_result.message == "FAIL Update") {
                        showToast("ไม่มีการบันทึกข้อมูล อาจเนื่องจากไม่มีการเปลี่ยนแปลงข้อมูลที่อัพเดทเข้ามา", "WARN");
                    } else {
                        showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
                    }
                },
                fail: (ret_result)=> {
                    alert(ret_result);
                    showToast("บันทึกข้อมูลไม่สำเร็จ", "DANGER");
                }
            });
        }
    }
    cancel() {
        location.href = this.callbackURL;
    }
}
class Manage_excellence_menu extends Manage_department_menu {
    list(pageno) {
        const inData = {
            "pageno": pageno,
            "parent_id" : 5
        };
        var strHTML = "";
        //========== ล้างหน้าจอ ===============
        $(".list-excellence-center-menu").empty();
        //========== ล้างตัวบอกหน้า ============
        $("#displayPaging").empty();
        $("#spinner").show();
        $.post(url_collection.list_menu, inData, () => {
        }).done((ret_data) => {
            $("#spinner").hide();

            const json_resultdata = JSON.parse(ret_data);
            
            strHTML ="<div style=\"display:table-row\">";
            strHTML += "<div style=\"width:10%;\" class=\"ps-2 smallerFont head-row\">แก้ไข</div>";
            strHTML += "<div style=\"width:10%;\" class=\"ps-2 smallerFont head-row\">ลำดับแสดงผล</div>";
            strHTML += "<div style=\"width:40%;\" class=\"ps-2 smallerFont head-row\">ชื่อเมนู</div>";
            strHTML += "<div style=\"width:10%;\" class=\"ps-2 smallerFont head-row\">สถานะ</div>";
            strHTML += "</div>";

            if (json_resultdata.message === "OK" && json_resultdata.total_rec > 0) {

                $("#num_row").html(json_resultdata.total_rec);

                var i = 0;

                (json_resultdata.info).forEach((val1) => {
                    strHTML += "<div style=\"display:table-row\">";
                    strHTML += "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\"><button class=\"btnEDIT\" title=\"แก้ไขรายการนี้\" onclick=\"javascript:manage_excellence_menu.edit(" + val1.id + ");\">" + (++i) + "</button></div>";
                    strHTML += "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\">" + val1.seq + "</div>";
                    strHTML += "<div style=\"width:40%;\" class=\"ps-2 border-bottom dp-data\">" + val1.menu_name + "</div>";
                    strHTML += "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\">" + val1.status + "</div>";
                    strHTML += "</div>";
                });
                $(".list-excellence-center-menu").append(strHTML);
                //======= Blind Paging No =======//
                var strtxt2 = "";
                const max_page = json_resultdata.total_page;
               
                if (max_page > 0) {
                    for (let i = 0; i < max_page; i++) {
                        strtxt2 = "<li class=\"page-item " + ((i + 1 == json_resultdata.current_page) ? "active" : "") + "\">";
                        strtxt2 += "<a class=\"page-link\" href=\"javascript:manage_excellence_menu.list(" + (i + 1) + ")\">" + (i + 1) + "</a></li>";
                        $("#displayPaging").append(strtxt2);
                    }
                }

            } else {
                $("#num_row").html("0");
                strHTML = "<div class=\"mt-3 biggerFont\">ไม่มีข้อมูล</div>";
                $(".list-excellence-center-menu").append(strHTML);
            }
        }).fail((ret_data) => {
            $("#spinner").hide();
            $("#num_row").html("0");
            strHTML = "<div class=\"mt-3 biggerFont\">ไม่มีข้อมูล</div>";
            $(".list-excellence-center-menu").append(strHTML);
        });
    }
}
class Manage_education_menu extends Manage_department_menu {
    list(pageno) {
        const inData = {
            "pageno": pageno,
            "parent_id" : 3
        };
        var strHTML = "";
        //========== ล้างหน้าจอ ===============
        $(".list-education-menu").empty();
        //========== ล้างตัวบอกหน้า ============
        $("#displayPaging").empty();
        $("#spinner").show();
        $.post(url_collection.list_menu, inData, () => {
        }).done((ret_data) => {
            $("#spinner").hide();

            const json_resultdata = JSON.parse(ret_data);
            
            strHTML ="<div style=\"display:table-row\">";
            strHTML += "<div style=\"width:10%;\" class=\"ps-2 smallerFont head-row\">แก้ไข</div>";
            strHTML += "<div style=\"width:10%;\" class=\"ps-2 smallerFont head-row\">ลำดับแสดงผล</div>";
            strHTML += "<div style=\"width:40%;\" class=\"ps-2 smallerFont head-row\">ชื่อเมนู</div>";
            strHTML += "<div style=\"width:10%;\" class=\"ps-2 smallerFont head-row\">สถานะ</div>";
            strHTML += "</div>";

            if (json_resultdata.message === "OK" && json_resultdata.total_rec > 0) {

                $("#num_row").html(json_resultdata.total_rec);

                var i = 0;

                (json_resultdata.info).forEach((val1) => {
                    strHTML += "<div style=\"display:table-row\">";
                    strHTML += "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\"><button class=\"btnEDIT\" title=\"แก้ไขรายการนี้\" onclick=\"javascript:manage_education_menu.edit(" + val1.id + ");\">" + (++i) + "</button></div>";
                    strHTML += "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\">" + val1.seq + "</div>";
                    strHTML += "<div style=\"width:40%;\" class=\"ps-2 border-bottom dp-data\">" + val1.menu_name + "</div>";
                    strHTML += "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\">" + val1.status + "</div>";
                    strHTML += "</div>";
                });
                $(".list-education-menu").append(strHTML);
                //======= Blind Paging No =======//
                var strtxt2 = "";
                const max_page = json_resultdata.total_page;
               
                if (max_page > 0) {
                    for (let i = 0; i < max_page; i++) {
                        strtxt2 = "<li class=\"page-item " + ((i + 1 == json_resultdata.current_page) ? "active" : "") + "\">";
                        strtxt2 += "<a class=\"page-link\" href=\"javascript:manage_education_menu.list(" + (i + 1) + ")\">" + (i + 1) + "</a></li>";
                        $("#displayPaging").append(strtxt2);
                    }
                }

            } else {
                $("#num_row").html("0");
                strHTML = "<div class=\"mt-3 biggerFont\">ไม่มีข้อมูล</div>";
                $(".list-education-menu").append(strHTML);
            }
        }).fail((ret_data) => {
            $("#spinner").hide();
            $("#num_row").html("0");
            strHTML = "<div class=\"mt-3 biggerFont\">ไม่มีข้อมูล</div>";
            $(".list-education-menu").append(strHTML);
        });
    }
}
var manage_department_menu = new Manage_department_menu('addeditdepartmentmenu.php',null,null,null,'list_department_menu.php');
var manage_excellence_menu = new Manage_excellence_menu('addeditexcellencemenu.php',null,null,null,'list_excellence_center_menu.php');
var manage_education_menu = new Manage_education_menu('addediteducationmenu.php',null,null,null,'list_education_menu.php');

function updateSelectMenu(id, valueCompare) {
    var i = document.getElementById(id);
    Array.from(i.options).forEach((val1) => {
        if (val1.value == valueCompare) {
            val1.selected = true;
        }
    });
}
function updateCheckBox(id, valueCompare) {
    alert("getin");
    // alert(id);
    // alert(valueCompare);
    // var i = document.getElementById(id);
    // if(valueCompare=="Y") {
    //     i.checked = true;
    // } else {
    //     i.checked = false;
    // }
}
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));

}
function cleartmp(id) {

    const url = url_collection.delete_tmp_news;
    const inData = {
        "id": id
    }
    $.post(url, inData, (ret_data) => {

    }).done((ret_data) => {

    });

}
function previewHTML(formObj, content) {

    var topic = formObj.elements.topic.value;
    const picture_URL = formObj.elements.picture_URL.value;
    var detail = formObj.elements.detail.value;

    topic = topic.replace(/'/g, '"');
    detail = content.replace(/'/g, '"');
    // 1. insert ข้อมูลลงใน db ชั่วคราว
    // 2. เรียกดู โดยระบุ id ที่ได้จากการ insert
    // 3. ลบข้อมูลออกหลังจากที่ปิดหน้าต่าง
    const inData = {
        "topic": topic,
        "picture_URL": picture_URL,
        "detail": content
    };

    $.post(url_collection.insert_tmp_news, inData, (ret_data) => {
    }).done((ret_data) => {

        const json_ret_data = JSON.parse(ret_data);
        window.open('../previewnewsdetail.php?id=' + json_ret_data.info.id, '_preview_page', 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no');
        //cleartmp(json_ret_data.info.id);
    });
}
function editLinkBanner(id) {
    window.location.href = "addeditlink.php?id=" + id;
}
function addLinkBanner() {
    window.location.href = "addeditlink.php";
}
function editDepartment(id) {
    window.location.href = "addeditdepartment.php?id=" + id;
}
function addNews(typenews) {
    window.location.href = "addeditnews.php?type=" + typenews;
}
function addUser() {
    window.location.href = "addedituser.php";
}
function editUser(id) {
    window.location.href = "addedituser.php?id=" + id;
}
function editNews(id, typenews) {
    window.location.href = "addeditnews.php?id=" + id + "&type=" + typenews;
}
function addBanner() {
    window.location.href = "addeditbanner.php";
}
function editBanner(id) {
    window.location.href = "addeditbanner.php?id=" + id;
}
function addDepartment() {
    window.location.href = "addeditdepartment.php";
}
function addVDOGroup() {
    window.location.href = "addeditvdogroup.php";
}
function editVDOGroup(id) {
    window.location.href = "addeditvdogroup.php?id=" + id;
}
function showToast(msg, typemsg, callbackFn) {

    const img = {
        "NORMAL": "<img src='../images/icon_ok.png'> ",
        "WARN": "<img src='../images/icon_warn.png'> ",
        "DANGER": "<img src='../images/icon_danger.png'> "
    }
    if (typemsg == null || typemsg == undefined) {
        typemsg = "NORMAL";
    }

    if (typemsg == "NORMAL") {
        $("#showToast").css("background-color", "var(--toast-bg)").css("color", "var(--toast-color)").css("border-color", "var(--toast-bg)");
        msg = img.NORMAL + msg;
    } else if (typemsg == "DANGER") {
        $("#showToast").css("background-color", "var(--toast-danger-bg)").css("color", "black").css("border-color", "var(--toast-danger-bg)");
        msg = img.DANGER + msg;
    } else if (typemsg == "WARN") {
        $("#showToast").css("background-color", "var(--toast-warn-bg)").css("border-color", "var(--toast-warn-bg)");
        msg = img.WARN + msg;
    }

    $("#showToast").html(msg);
    $("#showToast").fadeIn(300).delay(2000).fadeOut(300);
    if (callbackFn != undefined && callbackFn != null) {
        callbackFn();
    }
}
function edit_content_Insert_Image(myObj) {

    const openTag = "<img src=''>";
    var allText = myObj.value;
    const id = $(myObj).attr("id");

    var getid = document.activeElement.getAttribute("id");

    if (document.getSelection) {    // all browsers, except IE before version 9
        mark_text = document.getSelection();
    }
    else {
        if (document.selection) {   // Internet Explorer before version 9
            var textRange = document.selection.createRange();
            mark_text = (textRange.text);
        }
    }
    if (getid == id) {
        var getPositionCursor = $(myObj).prop("selectionStart");
        selectedText = openTag + mark_text.toString();

        allText = allText.substring(0, getPositionCursor) + allText.substring(getPositionCursor).replace(mark_text, selectedText);
        myObj.value = allText;
    }

    window.getSelection().empty();

}
function select_paragraph(myObj, tagHTML) {

    const closeTagHTML = tagHTML.slice(0, 1) + "/" + tagHTML.slice(1);
    var allText = myObj.value;
    var selectedText = '';
    var mark_text;
    const id = $(myObj).attr("id");

    var getid = document.activeElement.getAttribute("id");

    if (document.getSelection) {    // all browsers, except IE before version 9
        mark_text = document.getSelection();
    }
    else {
        if (document.selection) {   // Internet Explorer before version 9
            var textRange = document.selection.createRange();
            mark_text = (textRange.text);
        }
    }
    if (getid == id) {
        var getPositionCursor = $(myObj).prop("selectionStart");
        selectedText = tagHTML + mark_text.toString() + closeTagHTML;

        allText = allText.substring(0, getPositionCursor) + allText.substring(getPositionCursor).replace(mark_text, selectedText);
        myObj.value = allText;
    }

    //ทำการแทรกคำนี้ลงใน textarea
    window.getSelection().empty();

}
function updatePictureInFrame(event) {
    var tmppath = URL.createObjectURL(event.target.files[0]);
    $("#showPreviewPicture").attr("src", tmppath);
    document.getElementById("uploadFileName").value = tmppath;
}
function upPicture(event) {

    var fileName = document.getElementById("uploadFileName").value;

    if (fileName == "" || fileName == null || fileName == undefined) {
        alert("กรุณาเลือกไฟล์ที่ปุ่ม Choose File");
    } else {
        alert("2:" + fileName);

        $.ajax({
            url: "uploadfile.php",
            type: "POST",
            data: fileName,
            processData: false, //Not to process data
            contentType: false, //Not to set contentType
            success: function (data) {
                alert("done");
            }
        });
    }


}
function checkToast() {

    var msgIn = getParameterByName("msg");

    if (msgIn != "") {
        showToast(msgIn);
        // window.history.pushState({}, document.title, "/");
    }
}
function preview_Image(inURL, command) {

    inURL = inURL.replace(/ /g, "%20");

    if (command == "show") {
        $("#showListAlbumPicture").hide();
        $("#albumpicture .preview").css("display", "block");
        $("#albumpicture .preview div:first-child").css("background-image", "url('" + inURL + "')");
        $("#changePictureFromAlbum").val(inURL);
    } else {
        $("#showListAlbumPicture").show();
        $("#albumpicture .preview").css("display", "none");
        $("#albumpicture .preview div:first-child").css("background-image", "url()");
    }
}
function change_Picture_FromAlbum(inURL, des_obj) {

    const get_obj = JSON.parse(localStorage.getItem("inData"));
    const get_obj_id = get_obj.id;
    $("#previewImage").attr("src", inURL); // แสดงภาพในช่อง preview
    document.getElementById(get_obj_id).value = inURL; // เปลี่ยนค่าในช่องเติม
    //des_obj.value = inURL;
    $("#albumpicture .preview").css("display", "none"); // เอาหน้าต่าง album ออก
    $("#albumpicture").hide();
    document.getElementById("showpicture").style.display = "none";
    document.getElementById("editContent").style.display = "block";

    localStorage.removeItem("inData");
    //albumPicture('none');

}
function close_window(id) {
    document.getElementById(id).style.display = "none";
}
function window_ShowPicture(on_off, inURL) {
    if (on_off == "none" || on_off == "close" || on_off == "off") {
        $("#showFullPicture").css("background-image", "url('')");
        document.getElementById("showpicture").style.display = "none";
        document.getElementById("editContent").style.display = "block";
    } else {
        if (inURL != null && inURL != "") {
            $("#showFullPicture").css("background-image", "url('" + inURL + "')");
        }
        document.getElementById("showpicture").style.display = "block";
        document.getElementById("editContent").style.display = "none";
    }
}
function saveUploadPicture(typenews) {

    const picture_source = $("#showPreviewPicture").attr("src");
    // เรียก Web Service สำหรับบันทึกภาพที่ได้จากการ upload 
    const inData = {
        "typenews": typenews,
        "image_url": picture_source
    };

    $.post("save_pictodb_ws.php", inData, () => {

    }).done(() => {

    })

    $("#previewImage").attr("src", picture_source); // แสดงภาพในช่อง preview
    $("#picture_URL").val(picture_source); // เปลี่ยนค่า src ให้เป็นตัวใหม่
    $("#uploadpicture").hide();
    $("#editContent").show();

}
function saveUploadActivityPicture(typenews, image_desc) {

    const picture_source = $("#showPreviewPicture").attr("src");
    // เรียก Web Service สำหรับบันทึกภาพที่ได้จากการ upload 
    const inData = {
        "typenews": typenews,
        "image_url": picture_source,
        "image_desc": image_desc
    };

    $.post("save_pictodb_ws.php", inData, () => {
    }).done(() => {
        showToast("บันทึกลงในคลังภาพเรียบร้อยแล้ว");
        setTimeout(() => {
            window.location.href = "manage_pic_activity.php";
        }, 3000);
    });

    //window.location.href = "manage_pic_activity.php";

}
function saveUpdateActivityPicture(id, typenews, image_desc, status) {

    const picture_source = $("#showPreviewPicture").attr("src");
    if (status == "on") {
        status = "Y";
    }
    if (status == "off") {
        status = "N";
    }
    if (image_desc == null || image_desc == undefined) image_desc = "";

    // เรียก Web Service สำหรับบันทึกภาพที่ได้จากการ upload 
    const inData = {
        "id": id,
        "typenews": typenews,
        "image_desc": image_desc,
        "status": status
    };

    $.ajax({
        url: "save_pictodb_ws.php",
        type: 'PUT',
        data: inData,
        success: function (resultdata) {

            const json_resultdata = JSON.parse(resultdata);
            if (json_resultdata.message == "OK") {
                showToast("บันทึกลงในคลังภาพเรียบร้อยแล้ว");
                setTimeout(() => {
                    editActivityPicture.close();
                }, 3000);
            } else if (json_resultdata.message == "NOTHING UPDATE") {
                showToast("ไม่มีการเปลี่ยนแปลง");
            } else {
                showToast("บันทึกไม่สำเร็จ :" + json_resultdata.message);
            }

        },
        fail: function () {
            showToast("บันทึกไม่สำเร็จ");
        }
    });
}
function uploadPicture(on_off) {
    if (on_off == "none" || on_off == "close" || on_off == "off") {
        on_off == "none";
        document.getElementById("editContent").style.display = "block";
    } else {
        on_off == "block";
        document.getElementById("editContent").style.display = "none";
    }
    document.getElementById("uploadpicture").style.display = on_off;
}
function saveUploadBanner() {
    $("#showPreview").attr("src", $("#showPreviewPicture").attr("src"));
    $("#image_link").val($("#showPreviewPicture").attr("src"));
    $("#uploadbanner").hide();
    $("#editContent").show();
}
function uploadBanner(on_off) {
    if (on_off == "none" || on_off == "close" || on_off == "off") {
        on_off == "none";
        document.getElementById("editContent").style.display = "block";
    } else {
        on_off == "block";
        document.getElementById("editContent").style.display = "none";
    }
    document.getElementById("uploadbanner").style.display = on_off;
}
function albumPicture(on_off, dest_Obj) {

    if (on_off == "none" || on_off == "close" || on_off == "off" || on_off == "hide") {

        document.getElementById("albumpicture").style.display = "none";
        document.getElementById("editContent").style.display = "block";
        localStorage.removeItem("inData");

    } else {
        document.getElementById("albumpicture").style.display = "block";
        document.getElementById("editContent").style.display = "none";
        showAlbum();
    }

}

function showAlbum(catagory_name, pageno, idName, command) {

    $("#showListAlbumPicture").show();

    if (command == "show" || command == "on") {
        document.getElementById("albumpicture").style.display = "block";
        document.getElementById("editContent").style.display = "none";

        if (pageno == null) {
            pageno = 1;
        }
        var inData = {
            "pageno": pageno,
            "catagory": catagory_name,
            "id": idName,
            "command": command
        };
        const saved_inData = JSON.parse(localStorage.getItem("inData"));

        if (inData.id == undefined || inData.id == null || inData.id == "") {
            inData.id = saved_inData.id;
        }
        if (catagory_name == undefined || catagory_name == null) {
            catagory_name = "";
        } else {
            inData.catagory = catagory_name;
        }
        localStorage.setItem("inData", JSON.stringify(inData));

        if (catagory_name != "0") {

            localStorage.setItem("inData", JSON.stringify(inData));

            $("#spinner").show();

            $.post("listalbum_ws.php", inData, () => {
            }).done((ret_data) => {
                const json_ret_data = JSON.parse(ret_data);

                if (json_ret_data.total > 0) {
                    const listItem = json_ret_data.info;

                    $("#showListAlbumPicture").empty();
                    listItem.forEach(function (ret_list) {

                        var strtxt = "";

                        strtxt = "<div class=\"col-3 text-center bg-white p-1\"><a href=\"javascript:preview_Image('" + ret_list.image_url + "','show');\" title=\"" + ret_list.image_desc + "\">";
                        strtxt += "<img src=\"" + ret_list.image_url + "\" class=\"p-1\"><div>" + ret_list.catagory + "</div></a></div>";

                        $("#showListAlbumPicture").append(strtxt);
                    });

                    var max_page = Math.ceil(parseInt(json_ret_data.total) / parseInt(json_ret_data.rows_per_page));

                    //======= Blind Paging No =======//
                    var strtxt2 = "";
                    $("#displayPaging").empty();
                    for (i = 0; i < max_page; i++) {
                        // if (i != 0) {
                        strtxt2 = "<li class=\"page-item " + ((i + 1 == json_ret_data.current_page) ? "active" : "") + "\">";
                        strtxt2 += "<a class=\"page-link\" href=\"javascript:showAlbum(document.getElementById('catagory').value," + (i + 1) + ",'" + inData.id + "','show')\">" + (i + 1) + "</a></li>";
                        $("#displayPaging").append(strtxt2);
                        //}

                    }

                    $("#spinner").hide();
                } else {

                    strtxt = "<div class=\"col-12 text-center mt-2 bg-white p-1\">ไม่พบข้อมูล</div>";
                    $("#showListAlbumPicture").html(strtxt);
                    $("#spinner").hide();
                }


            }).fail((ret_data) => {
                $("#spinner").hide();
                strtxt = "<div class=\"col-12 text-center mt-2 bg-white p-1\">ไม่พบข้อมูล</div>";
                $("#showListAlbumPicture").html(strtxt);
            });
        }
    } else {
        document.getElementById("albumpicture").style.display = "none";
        document.getElementById("editContent").style.display = "block";
    }



}
function listActivityAlbum(catagory_name, pageno) {

    $("#showListAlbumPicture").show();

    if (pageno == null || catagory_name == undefined) {
        pageno = 1;
    }
    if (catagory_name == null || catagory_name == undefined) {
        catagory_name = "";
    }

    var inData = {
        "pageno": pageno,
        "catagory": catagory_name
    };

    $("#spinner").show();

    $.post("ws/listalbum_ws.php", inData, () => {
    }).done((ret_data) => {
        const json_ret_data = JSON.parse(ret_data);
        if (json_ret_data.total > 0) {
            const listItem = json_ret_data.info;

            $("#showListAlbumPicture").empty();
            listItem.forEach(function (ret_list) {

                var strtxt = "";

                strtxt = "<div class=\"col-3 text-center bg-white p-1\"><a href=\"javascript:editActivityPicture.init(" + ret_list.id + ");\" title=\"" + ret_list.image_desc + "\">";
                strtxt += "<img src=\"" + ret_list.image_url + "\" class=\"p-1\"><div>" + ret_list.catagory + "</div></a></div>";

                $("#showListAlbumPicture").append(strtxt);
            });

            const max_page = json_ret_data.total_page;

            //======= Blind Paging No =======//
            var strtxt2 = "";
            $("#displayPaging").empty();
            for (i = 0; i < max_page; i++) {
                // if (i != 0) {
                strtxt2 = "<li class=\"page-item " + ((i + 1 == json_ret_data.current_page) ? "active" : "") + "\">";
                strtxt2 += "<a class=\"page-link\" href=\"javascript:listActivityAlbum('" + json_ret_data.catagory + "'," + (i + 1) + ")\">" + (i + 1) + "</a></li>";
                $("#displayPaging").append(strtxt2);
                //}

            }

            $("#spinner").hide();
        } else {

            strtxt = "<div class=\"col-12 text-center mt-2 bg-white p-1\">ไม่พบข้อมูล</div>";
            $("#showListAlbumPicture").html(strtxt);
            $("#spinner").hide();
        }


    }).fail((ret_data) => {
        $("#spinner").hide();
        strtxt = "<div class=\"col-12 text-center mt-2 bg-white p-1\">ไม่พบข้อมูล</div>";
        $("#showListAlbumPicture").html(strtxt);
    });


}
function showActivityAlbum(catagory_name, pageno, idName, command) {

    $("#showListAlbumPicture").show();

    if (command == "show" || command == "on") {
        if (pageno == null) {
            pageno = 1;
        }
        var inData = {
            "pageno": pageno,
            "catagory": catagory_name,
            "id": idName,
            "command": command
        };
        const saved_inData = JSON.parse(localStorage.getItem("inData"));

        if (inData.id == undefined || inData.id == null || inData.id == "") {
            inData.id = saved_inData.id;
        }
        if (catagory_name == undefined || catagory_name == null) {
            catagory_name = "";
        } else {
            inData.catagory = catagory_name;
        }
        localStorage.setItem("inData", JSON.stringify(inData));

        if (catagory_name != "0") {

            localStorage.setItem("inData", JSON.stringify(inData));

            $("#spinner").show();

            $.post("listalbum_ws.php", inData, () => {
            }).done((ret_data) => {
                const json_ret_data = JSON.parse(ret_data);

                if (json_ret_data.total > 0) {
                    const listItem = json_ret_data.info;

                    $("#showListAlbumPicture").empty();
                    listItem.forEach(function (ret_list) {

                        var strtxt = "";

                        strtxt = "<div class=\"col-3 text-center bg-white p-1\"><a href=\"javascript:editActivityPicture.init(" + ret_list.id + ");\" title=\"" + ret_list.image_desc + "\">";
                        strtxt += "<img src=\"" + ret_list.image_url + "\" class=\"p-1\"><div>" + ret_list.catagory + "</div></a></div>";

                        $("#showListAlbumPicture").append(strtxt);
                    });

                    var max_page = Math.ceil(parseInt(json_ret_data.total) / parseInt(json_ret_data.rows_per_page));

                    //======= Blind Paging No =======//
                    var strtxt2 = "";
                    $("#displayPaging").empty();
                    for (i = 0; i < max_page; i++) {
                        // if (i != 0) {
                        strtxt2 = "<li class=\"page-item " + ((i + 1 == json_ret_data.current_page) ? "active" : "") + "\">";
                        strtxt2 += "<a class=\"page-link\" href=\"javascript:showActivityAlbum(document.getElementById('catagory').value," + (i + 1) + ",'" + inData.id + "','show')\">" + (i + 1) + "</a></li>";
                        $("#displayPaging").append(strtxt2);
                        //}

                    }

                    $("#spinner").hide();
                } else {

                    strtxt = "<div class=\"col-12 text-center mt-2 bg-white p-1\">ไม่พบข้อมูล</div>";
                    $("#showListAlbumPicture").html(strtxt);
                    $("#spinner").hide();
                }


            }).fail((ret_data) => {
                $("#spinner").hide();
                strtxt = "<div class=\"col-12 text-center mt-2 bg-white p-1\">ไม่พบข้อมูล</div>";
                $("#showListAlbumPicture").html(strtxt);
            });
        }
    }
}
function upload_image(myObj) {

    var formData = new FormData(myObj);

    $("#spinner").show();
    $.ajax({
        url: "upload_image.php",
        type: "post",
        data: formData,
        processData: false, //Not to process data
        contentType: false, //Not to set contentType
        success: function (data) {
            $("#spinner").hide();
            const returnMsg = JSON.parse(data);
            if (returnMsg.status == "OK") {
                showToast("Upload สำเร็จ ");
                $("#showPreviewPicture").attr("src", returnMsg.url);
                $("#showPreview").val(returnMsg.url);
                $("#picture_URL").val(returnMsg.url);
                $("#showFullPicture").css("background-image", "url('" + returnMsg.url + "')");
                $(".hide-first").css("visibility", "visible");
                $("#save_upload_picture_btn").show();
            } else {
                showToast("Upload Fail!");
                $(".hide-first").css("visibility", "hidden");
            }
        },
        fail: () => {
            $("#spinner").hide();
            showToast("Upload Fail!");
        }
    });
}

function activeSection(id) {
    $(".alter-section").css("display", "none");
    $(".nav-header div div").removeClass("active"); // remove all active
    const t = document.activeElement;

    $(t).parent().addClass("active");
    document.getElementById(id).style.display = "block";
}

function updateVDO(obj1) {
    const src_clip = obj1["src_clip"].value;
    const vdo_url = obj1["vdo_url"].value;
    const vdo_desc = obj1["vdo_desc"].value;
    const status1 = obj1["status1"].value;
    const catagory = obj1["catagory"].value;
    const attach_file_url = obj1["attach_file_url"].value;
    const id = obj1["id"].value;

    var is_youtube = (src_clip == "Youtube" ? "Y" : "N");

    if (id == null || id == undefined || id == "") {
        command = "ADD";
    } else {
        command = "UPDATE";
    }

    var inData = {
        "id": id,
        "src_clip": src_clip,
        "is_youtube": is_youtube,
        "status": status1,
        "vdo_url": vdo_url,
        "vdo_desc": vdo_desc,
        "catagory": catagory,
        "attach_file_url": attach_file_url,
        "command": command
    }

    if (command == "ADD") {

        //ทำการแปลง Code ที่มาจาก Youtube ให้เป็น Code Embed
        // เช่น https://www.youtube.com/watch?v=xPyOclcsYJk แปลงเป็น
        // https://www.youtube.com/embed/xPyOclcsYJk

        if (inData.is_youtube == "Y") {
            if ((inData.vdo_url).indexOf("embed") < 0) {
                inData.vdo_url = (inData.vdo_url).replace('watch?v=', 'embed/');
            }
        }

        //==============================================
        $.post(url_collection.vdo_insertupdate, inData, () => {

        }).done((resultdata) => {
            const json_resultdata = JSON.parse(resultdata);
            if (json_resultdata.message == "OK") {
                showToast("บันทึกลงในคลังภาพเรียบร้อยแล้ว");
                setTimeout(() => {
                    window.location.href = "list_vdo.php";
                }, 1000);
            } else {
                showAlert.show("ไม่สามารถบันทึก VDO ได้", "DANGER");
            }
        }).fail(() => {
            showAlert.show("ไม่สามารถบันทึก VDO ได้", "DANGER");
        });
    } else if (command == "UPDATE") {
        $.ajax({
            url: url_collection.vdo_insertupdate,
            type: 'PUT',
            data: inData,
            success: function (resultdata) {

                const json_resultdata = JSON.parse(resultdata);
                if (json_resultdata.message == "OK") {
                    showToast("บันทึกลงในคลังภาพเรียบร้อยแล้ว");
                    setTimeout(() => {
                        window.location.href = "list_vdo.php";
                    }, 1000);
                } else if (json_resultdata.message == "NOTHING UPDATE") {
                    showToast("ไม่มีการเปลี่ยนแปลง");
                } else {
                    showToast("บันทึกไม่สำเร็จ :" + json_resultdata.message);
                }
            },
            fail: function () {
                showToast("บันทึกไม่สำเร็จ");
            }
        });
    }
}
function addeditvdo(id) {

    if (id == null || id == undefined) {
        id = "";
    }
    window.location.href = "addeditvdo.php?id=" + id;

}

function blind_list_vdo(pageno, searchTxt, select_status, catagory) {

    const inData = {
        "pageno": pageno,
        "searchTxt": searchTxt,
        "select_status": select_status,
        "catagory": catagory
    }

    var strHTML = "";

    //========== ล้างหน้าจอ ===============
    $(".list-vdo").empty();
    //========== ล้างตัวบอกหน้า ============
    $("#displayPaging").empty();
    //========= Update ตัวแปรในฟอร์ม ======
    $("#searchTxt").val(searchTxt);

    $("#spinner").show();

    $.post(url_collection.vdo_list, inData, () => {

    }).done((ret_data) => {
        $("#spinner").hide();
        const json_resultdata = JSON.parse(ret_data);

        if (json_resultdata.message === "OK" && json_resultdata.total_rec > 0) {

            $("#num_row").html(json_resultdata.total_rec);

            strHTML = "<div style=\"display:table-row\">";
            strHTML += "<div style=\"width:10%;\" class=\"ps-2 smallerFont head-row\">แก้ไข</div>";
            strHTML += "<div style=\"width:40%;\" class=\"ps-2 smallerFont head-row\">VDO</div>";
            strHTML += "<div style=\"width:5%;\" class=\"ps-2 smallerFont head-row\">หมุด</div>";
            strHTML += "<div style=\"width:10%;\" class=\"ps-2 smallerFont head-row\">กลุ่มเนื้อหา</div>";
            strHTML += "<div style=\"width:25%;\" class=\"ps-2 smallerFont head-row\">คำอธิบาย</div>";
            strHTML += "<div style=\"width:10%;\" class=\"ps-2 smallerFont head-row\">สถานะ</div>";
            strHTML += "</div>";
            var i = 0;

            (json_resultdata.info).forEach((val1) => {

                strHTML += "<div style=\"display:table-row\">";
                strHTML += "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\"><button class=\"btnEDIT\" title=\"แก้ไขรายการนี้\" onclick=\"javascript:addeditvdo('" + val1.id + "');\">" + (++i) + "</button></div>";
                if (val1.src_clip == "Youtube") {
                    strHTML += "<div style=\"width:40%;\" class=\"ps-2 border-bottom dp-data\"><iframe class=\"myiframe\" src=\"" + val1.vdo_url + "\" frameborder=\"0\" allow=\"accelerometer;clipboard-write; encrypted-media; gyroscope;\"></iframe></div>";
                } else if (val1.src_clip == "Upload") {
                    strHTML += "<div style=\"width:40%;\" class=\"ps-2 border-bottom dp-data\">";
                    strHTML += "<video controls class=\"video-js myiframe\">";
                    strHTML += "<source src=\"" + val1.vdo_url + "\" type=\"video/mp4\" muted>";
                    strHTML += "<source src=\"" + val1.vdo_url + "\" type=\"video/webm\" muted>";
                    strHTML += "</video></div>";
                } else if (val1.src_clip == "Googledrive") {
                    strHTML += "<div style=\"width:40%;background-image:url('../images/logo_MED_std.jpg');background-position: center center;background-size:contain;background-repeat:no-repeat\" class=\"ps-2 border-bottom dp-data\"><div style=\"min-height:150px\"><a href=\"" + val1.vdo_url + "\" target=\"_blank\">รับชม Clip</a></div></div>";
                } else if (val1.src_clip == "Noclip") {
                    strHTML += "<div style=\"width:40%;background-image:url('../images/icon_no_vdo.png');background-position: center center;background-size:contain;background-repeat:no-repeat\" class=\"ps-2 border-bottom dp-data\"><div style=\"min-height:150px\"></div></div>";
                }
                strHTML += "<div style=\"width:5%;\" class=\"ps-2 border-bottom dp-data\">" + val1.pin + "</div>";
                strHTML += "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\">" + (val1.catagory == "null" || val1.catagory == null ? "" : val1.catagory) + "</div>";
                strHTML += "<div style=\"width:30%;\" class=\"ps-2 border-bottom dp-data\">" + val1.vdo_desc + "</div>";
                strHTML += "<div style=\"width:10%;\" class=\"ps-2 border-bottom dp-data\">" + (val1.status == "Y" ? "<span class=\"text-success\">แสดง</span>" : "<span class=\"text-danger\">ไม่แสดง</span>") + "</div>";
                strHTML += "</div>";
            });
            $(".list-vdo").append(strHTML);

            //======= Blind Paging No =======//
            var strtxt2 = "";
            const max_page = json_resultdata.total_page;

            if (max_page > 0) {
                for (i = 0; i < max_page; i++) {

                    strtxt2 = "<li class=\"page-item " + ((i + 1 == json_resultdata.current_page) ? "active" : "") + "\">";
                    strtxt2 += "<a class=\"page-link\" href=\"javascript:blind_list_vdo(" + (i + 1) + ",'" + searchTxt + "','" + select_status + "','" + catagory + "')\">" + (i + 1) + "</a></li>";
                    $("#displayPaging").append(strtxt2);

                }
            }

        } else {
            $("#num_row").html("0");
            strHTML = "<div class=\"mt-3 biggerFont\">ไม่มีข้อมูล</div>";
            $(".list-vdo").append(strHTML);
        }
    }).fail((ret_data) => {
        $("#spinner").hide();
        $("#num_row").html("0");
        strHTML = "<div class=\"mt-3 biggerFont\">ไม่มีข้อมูล</div>";
        $(".list-vdo").append(strHTML);
    });

}
function blind_vdo_detail(id) {
    const inData = {
        "id": id
    }
    $.post(url_collection.vdo_detail, inData, () => {

    }).done((ret_data) => {

        var json_ret_data = JSON.parse(ret_data);

        if (json_ret_data.message == "OK") {
            $("#id").val(id);
            $("#vdo_url").val(json_ret_data.info.vdo_url);
            $("#vdo_desc").val(json_ret_data.info.vdo_desc);
            $("#status1").val(json_ret_data.info.status);
            $("#attach_file_url").val(json_ret_data.info.attach_file_url);
            $("#showattach_file_url").html(json_ret_data.info.attach_file_url);

            // Blind Select Menu
            updateSelectMenu('src_clip', json_ret_data.info.src_clip);

            // Blind ค่าแสดงหรือไม่
            if (json_ret_data.info.status == "Y") {
                document.getElementById("status2").checked = true;
            } else {
                document.getElementById("status2").checked = false;
            }
        }

    }).fail(() => {

    });
}
function convertToSmartDate(inputDate) {
    const shortMonth = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.']
    const inDate = inputDate.replace(" ", "T");
    const rel_Date = new Date(inDate);
    return rel_Date.getDate() + " " + shortMonth[rel_Date.getMonth()] + (parseInt(rel_Date.getFullYear()) + 543) + " " + ("00" + rel_Date.getHours()).slice(-2) + ":" + ("00" + rel_Date.getMinutes()).slice(-2);
}

function convertToSmartDateShort(inputDate) {
    const shortMonth = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.']
    const inDate = inputDate.replace(" ", "T");
    const rel_Date = new Date(inDate);
    return rel_Date.getDate() + " " + shortMonth[rel_Date.getMonth()] + (parseInt(rel_Date.getFullYear()) + 543);
}

$(function () {
    var body = document.body,
        html = document.documentElement;

    var height_child_window = Math.max(body.scrollHeight, body.offsetHeight,
        html.clientHeight, html.scrollHeight, html.offsetHeight);
    var content_iframe = document.getElementsByClassName("content");

    var mywidth = content_iframe[0].clientWidth;

    $(".column-left").css("height", (height_child_window - 45) + "px");
    $(".content").css("height", (height_child_window - 45) + "px");

})