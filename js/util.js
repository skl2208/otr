// JavaScript Document
var fontSizeStatus = "0";
var webInfo = {
    webTheme: 1
};
var showSpinner = {
    "show": () => {
        $("#spinner").show();
    },
    "hide": () => {
        $("#spinner").hide();
    }
};
var showAlert = {
    "callbackfn": () => { },
    "show": (msg, typemsg, callbackfn) => {
        if (msg != "" && msg != null && msg != undefined) {
            if (typemsg == null || typemsg == undefined) {
                typemsg = "";
            }

            if (typemsg.toUpperCase() == "DANGER") {
                img_src = "images/icon_danger.png";
                $("#showAlert>div").css("background-color", "var(--toast-danger-bg)").css("color", "black").css("border-color", "var(--toast-danger-bg)");
            } else if (typemsg.toUpperCase() == "WARN") {
                img_src = "images/icon_warn.png";
                $("#showAlert>div").css("background-color", "var(--toast-warn-bg)").css("border-color", "var(--toast-warn-bg)");
            } else {
                img_src = "images/icon_ok.png";
            }

            document.querySelector("#showAlert img").src = img_src;
            document.getElementById("showAlert").style.display = "block";
            document.querySelector("#showAlert>div div:nth-child(2)").innerHTML = msg;

            if (callbackfn != null && callbackfn != undefined) {
                this.callbackfn = callbackfn;
            }
        }

    },
    "hide": () => {
        document.querySelector("#showAlert>div div:nth-child(2)").innerHTML = "";
        document.getElementById("showAlert").style.display = "none";
        this.callbackfn();
    }
}
if (window.localStorage.getItem("webInfo") != undefined && window.localStorage.getItem("webInfo") != null) {
    webInfo = JSON.parse(window.localStorage.getItem("webInfo"));
} else {
    window.localStorage.setItem("webInfo", JSON.stringify(webInfo));
}

var linkDownload = {
    "update": (id, target_id) => {
        command = "UPDATE";
        const inData = {
            "id": id,
            "command": command
        }
        $.post("ws/linkdownload.php", inData, () => {

        }).done((ret_data) => {
            const json_ret_data = JSON.parse(ret_data);
            const lasthit = json_ret_data.info.HIT;

            if (target_id != null && target_id != undefined) {

                var g = parseInt(document.getElementById(target_id).innerHTML);

                g++;
                document.getElementById(target_id).innerHTML = g;
                document.getElementById(target_id).value = lasthit;
            }

        }).fail(() => {
            console.log("Fail to update");
        });

    },
    "view": (id) => {
        command = "VIEW";
        const inData = {
            "id": id,
            "command": command
        }
    }
}

// ============= ทำการขึ้นปุ่ม Scroll Up =============
//window.onscroll = function () { scrollFunction() };
if (webInfo.webTheme == 1) {
    changeThemeToNormal();
} else {
    changeThemeToBlindYellow();
}

class Model_View {

    blind_data(pageno, select_group, searchTxt) {

        if (pageno == null) { pageno = 1; }
        if (select_group == null || select_group == undefined) { select_group = "" }
        if (searchTxt == null || searchTxt == undefined) { searchTxt = ""; }

        searchTxt = searchTxt.trim();

        var inData = {
            "pageno": pageno,
            "select_group": select_group,
            "searchTxt": searchTxt
        }
        showSpinner.show();

        $.post("ws/list_conference_ws.php", inData, () => {

        }).done((ret_data) => {

            var txtHTML = "";
            var json_ret_data = JSON.parse(ret_data);

            if (json_ret_data.message == "OK") {

                if (json_ret_data.info.length > 0) {
                    (json_ret_data.info).forEach((val1) => {
                        txtHTML += "<div class=\"col-lg-4 col-md-4 col-sm-6 mt-2 mb-2\">";
                        txtHTML += "<div><a href=\"conferenceviewdetail.php?id="+val1.id+"\"><img src=\""+val1.picture_URL+"\"></a></div>";
                        txtHTML += "<div><a href=\"conferenceviewdetail.php?id="+val1.id+"\">"+val1.type_confer+"</a></div>";
                        txtHTML += "<div><a href=\"conferenceviewdetail.php?id="+val1.id+"\">"+val1.topic_confer+"</a></div>";
                        txtHTML += "</div>";
                    });
                } else {
                    txtHTML = "<div class=\"col-lg-12 col-md-12 col-sm-12 mt-2 mb-2\">";
                    txtHTML += "<div class=\"text-center\"><img src=\"images/icon_no_data.png\" style=\"width:50%\"></div></div>";

                    $(".list-conference").html(txtHTML);
                    $("#displayPaging").empty();
                }
                showSpinner.hide();

                $(".list-conference").html(txtHTML);

                var max_page = Math.ceil(parseInt(json_ret_data.total) / parseInt(json_ret_data.rows_per_page));

                //======= Blind Paging No =======//
                var strtxt2 = "";
                $("#displayPaging").empty();

                for (let i = 0; i < max_page; i++) {
                    // if (i != 0) {
                    strtxt2 = "<li class=\"page-item " + ((i + 1 == json_ret_data.current_page) ? "active" : "") + "\">";
                    strtxt2 += "<a class=\"page-link\" href=\"javascript:model_View_Conference.blind_data(" + (i + 1) + ",'" + select_group + "','" + searchTxt + "')\">" + (i + 1) + "</a></li>";
                    $("#displayPaging").append(strtxt2);
                    //}

                }
            } else {
                txtHTML = "<div class=\"col-lg-12 col-md-12 col-sm-12 mt-2 mb-2\">";
                txtHTML += "<div class=\"text-center\"><img src=\"images/icon_no_data.png\" style=\"width:50%\"></div></div>";

                $(".list-conference").html(txtHTML);
                $("#displayPaging").empty();
                showSpinner.hide();
            }

        }).fail(errorText => {
            alert(JSON.stringify(errorText));
        });
    }
}
var model_View_Conference = new Model_View();

class Model_View_Research extends Model_View {

    blind_data(pageno, select_group, searchTxt, research_year) {

        if (pageno == null) { pageno = 1; }
        if (select_group == null || select_group == undefined) { select_group = "" }
        if (searchTxt == null || searchTxt == undefined) { searchTxt = ""; }
        if (research_year == null || research_year == undefined) { research_year = 0; }

        searchTxt = searchTxt.trim();

        var inData = {
            "pageno": pageno,
            "group_name": select_group,
            "searchTxt": searchTxt,
            "research_year" : research_year,
            "status" : "Y"
        }
        showSpinner.show();

        $.post("administrator/ws/research_list_ws.php", inData, () => {

        }).done((ret_data) => {

            var txtHTML = "";
            var json_ret_data = JSON.parse(ret_data);
            var total_page = json_ret_data.total_page;

            if (json_ret_data.message == "OK") {                
                if (json_ret_data.info.length > 0) {

                    
                    (json_ret_data.info).forEach((val1) => {
                        txtHTML += "<div class=\"row mx-lg-5 mx-md-1 mx-sm-0 shadow-sm list-research-item\">";
                        txtHTML += "<div class=\"col-12 mt-2 mb-2 text-lg-end text-md-end text-center\">";
                        txtHTML += "<span class=\"topic-header\">ชื่อผู้สร้างงานวิจัย : "+val1.name+"</span>";
                        txtHTML += "</div>"
                        txtHTML += "<div class=\"col-12 mt-2 mb-2\">";
                        txtHTML += "<a href=\"researchdetail.php?id="+val1.id+"\">หัวข้องานวิจัย : "+val1.topic+"</a>";
                        txtHTML += "</div>";

                        if(val1.topicen!=null && val1.topicen!=""){
                            txtHTML += "<div class=\"col-12 mt-2 mb-2\">";
                            txtHTML += "<a href=\"researchdetail.php?id="+val1.id+"\">"+val1.topicen+"</a>";
                            txtHTML += "</div>";
                        }
                        txtHTML += "<div class=\"col-12 mt-2 mb-2\">";
                        txtHTML += "ประเภท : "+val1.group_name;
                        txtHTML += "</div>";
                        if(val1.download_url!=null && val1.download_url!="") {
                            txtHTML += "<div class=\"col-12 mt-2 mb-2 text-center\"><a href=\""+val1.download_url+"\" download>เอกสารงานวิจัย</a></div>";
                        }
                        txtHTML += "</div>";
                    });
                } else {
                    txtHTML = "<div class=\"col-lg-12 col-md-12 col-sm-12 mt-2 mb-2\">";
                    txtHTML += "<div class=\"text-center\"><img src=\"images/icon_no_data.png\" style=\"width:50%\"></div></div>";

                    $(".list-research").html(txtHTML);
                    $("#displayPaging").empty();
                }

                showSpinner.hide();

                $(".list-research").html(txtHTML);
                $("#displayPaging").empty();

                //$("#displayPaging").html(json_ret_data.total_rec);
                //$("#displayPaging").html(json_ret_data.total+","+json_ret_data.rows_per_page);

                //total_page = Math.ceil(parseInt(json_ret_data.total_rec) / parseInt(json_ret_data.rows_per_page));

                //======= Blind Paging No =======//
                var strtxt2 = "";


                for (let i = 0; i < total_page; i++) {
                    // if (i != 0) {
                    strtxt2 = "<li class=\"page-item " + ((i + 1 == json_ret_data.current_page) ? "active" : "") + "\">";
                    strtxt2 += "<a class=\"page-link\" href=\"javascript:model_View_Research.blind_data(" + (i + 1) + ",'" + select_group + "','" + searchTxt + "')\">" + (i + 1) + "</a></li>";
                    $("#displayPaging").append(strtxt2);
                    //}

                }
            } else {
                txtHTML = "<div class=\"col-lg-12 col-md-12 col-sm-12 mt-2 mb-2\">";
                txtHTML += "<div class=\"text-center\"><img src=\"images/icon_no_data.png\" style=\"width:50%\"></div></div>";

                $(".list-research").html(txtHTML);
                $("#displayPaging").empty();
                showSpinner.hide();
            }

        }).fail(errorText => {
            alert(JSON.stringify(errorText));
        });
    }
}
var model_View_Research = new Model_View_Research();

function setActiveStyleSheet(title) {

    var i, a, main;

    for (i = 0; (a = document.getElementsByTagName("link")[i]); i++) {

        if (a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title")) {
            a.disabled = true;

            if (a.getAttribute("title") == title) a.disabled = false;
        }
    }
}

function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

function checkImage(objImage) {

    var x = objImage.naturalWidth;
    var y = objImage.naturalHeight;

    if (y > x) {
        /* ภาพแนวตั้ง */
        $(objImage).removeClass("picture-landscape").addClass("picture-portriat");

    } else {
        /* ภาพแนวนอน */
        $(objImage).removeClass("picture-portriat").addClass("picture-landscape");
    }
}
function showFullPicture(imgURL) {

    var x, y;
    var img = document.getElementById("pictureFull");
    if (imgURL != "images/static_image/a_person.png") {
        if (imgURL == "close" || imgURL == undefined) {
            $(".showFullPicture").hide();
            $("#pictureFull").removeAttr("src");
        } else {
            $(".showFullPicture").show();
            $("#pictureFull").attr("src", imgURL);
            x = img.naturalWidth;
            y = img.naturalHeight;
            if (y > x) {
                /* ภาพแนวตั้ง */
                $(".showFullPicture>div>img").css("height", "100%");
                $(".showFullPicture>div>img").css("width", "auto");

            } else {
                /* ภาพแนวนอน */
                $(".showFullPicture>div>img").css("max-width", "900px");
                $(".showFullPicture>div>img").css("height", "auto");

            }
        }
    }
}
function convertDBToThaiDate(inDateTime) {
    const nameTh = [
        'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฏาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
    ];

    var dt = inDateTime.split(" ");
    var myDate = dt[0].split("-");

    return myDate[2] + " " + nameTh[parseInt(myDate[1]) - 1] + " " + (parseInt(myDate[0]) + 543) + " " + dt[1];
}
// ======================= เปลี่ยนธีม -========================= //
function changeThemeToNormal() {
    setActiveStyleSheet("default");
    //window.location.reload();
}

function changeThemeToBlindYellow() {
    setActiveStyleSheet("blind_yellow");
    //window.location.reload();    
}
function showToast(msg, typemsg) {

    const img = {
        "NORMAL": "<img src='images/icon_ok.png'> ",
        "WARN": "<img src='images/icon_warn.png'> ",
        "DANGER": "<img src='images/icon_danger.png'> "
    }
    if (typemsg == null || typemsg == undefined) {
        typemsg = "NORMAL";
    }

    if (typemsg == "NORMAL") {
        $("#showToast").css("background-color", "var(--toast-bg)").css("color", "black").css("border-color", "var(--toast-bg)");
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

}
// ================== Services Function ================== //
function blind_vdo_data(pageno, select_group, searchTxt) {

    if (pageno == null) { pageno = 1; }
    if (select_group == null || select_group == undefined) { select_group = "" }
    if (searchTxt == null || searchTxt == undefined) { searchTxt = ""; }

    searchTxt = searchTxt.trim();

    var inData = {
        "pageno": pageno,
        "select_group": select_group,
        "searchTxt": searchTxt
    }
    showSpinner.show();
    $.post("listvdo_ws.php", inData, () => {

    }).done((ret_data) => {

        var txtHTML = "";
        var json_ret_data = JSON.parse(ret_data);

        if (json_ret_data.message == "OK") {

            if (json_ret_data.info.length > 0) {
                (json_ret_data.info).forEach((val1) => {
                    txtHTML += "<div class=\"col-lg-6 col-md-6 col-sm-12 mt-2 mb-2\">";

                    if (val1.src_clip == "Youtube") {
                        txtHTML += "<div><iframe class=\"myiframe\" src=\"" + val1.vdo_url + "\" title=\"" + val1.vdo_desc + "\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe></div>";
                        //txtHTML += "<p>" + val1.vdo_desc + "</p>";
                    } else if (val1.src_clip == "Upload") {
                        txtHTML += "<video class=\"video-js myiframe\" id=\"\" controls controlsList=\"nodownload\">";
                        txtHTML += "<source src=\"" + val1.vdo_url + "\" type=\"video/mp4\" autoplay muted>";
                        txtHTML += "<source src=\"" + val1.vdo_url + "\" type=\"video/ogg\" autoplay muted>";
                        txtHTML += "<source src=\"" + val1.vdo_url + "\" type=\"video/webm\" autoplay muted>";
                        txtHTML += "</video>";

                        //txtHTML += "<p>" + val1.vdo_desc + "</p>";
                    } else if (val1.src_clip == "Googledrive") {
                        txtHTML += "<div style=\"position:relative;background-image:url('images/logo_MED_std.jpg');background-position: center center;background-size:cover;\"><a href=\"" + val1.vdo_url + "\" target=\"_blank\" class=\"btn-vdoclip\">รับชม Clip</a></div>";
                        //txtHTML += "<p>" + val1.vdo_desc + "</p>";
                    } else if (val1.src_clip == "Noclip") {
                        txtHTML += "<div style=\"position:relative;background-image:url('images/icon_no_vdo.png');background-position: center center;background-size:contain;background-repeat:no-repeat\"></div>";
                        //txtHTML += "<p>" + val1.vdo_desc + "</p>";
                    }
                    txtHTML += "<p>[ " + (val1.catagory == "" || val1.catagory == null || val1.catagory == undefined ? "/" : val1.catagory) + " ] " + val1.vdo_desc + "</p>";
                    if (val1.attach_file_url != null && val1.attach_file_url != "") {
                        txtHTML += "<p><a href=\"" + val1.attach_file_url + "\" download>เอกสารประกอบ</a></p>";
                    }
                    txtHTML += "</div>";
                });
                showSpinner.hide();
            } else {
                txtHTML = "<div class=\"col-lg-12 col-md-12 col-sm-12 mt-2 mb-2\">";
                txtHTML += "<div class=\"text-center\"><img src=\"images/icon_no_data.png\" style=\"width:50%\"></div></div>";

                $(".list-vdo").html(txtHTML);
                $("#displayPaging").empty();
                showSpinner.hide();
            }

            $(".list-vdo").html(txtHTML);

            var max_page = Math.ceil(parseInt(json_ret_data.total) / parseInt(json_ret_data.rows_per_page));

            //======= Blind Paging No =======//
            var strtxt2 = "";
            $("#displayPaging").empty();
            for (i = 0; i < max_page; i++) {
                // if (i != 0) {
                strtxt2 = "<li class=\"page-item " + ((i + 1 == json_ret_data.current_page) ? "active" : "") + "\">";
                strtxt2 += "<a class=\"page-link\" href=\"javascript:blind_vdo_data(" + (i + 1) + ",'" + select_group + "','" + searchTxt + "')\">" + (i + 1) + "</a></li>";
                $("#displayPaging").append(strtxt2);
                //}

            }
        } else {
            txtHTML = "<div class=\"col-lg-12 col-md-12 col-sm-12 mt-2 mb-2\">";
            txtHTML += "<div class=\"text-center\"><img src=\"images/icon_no_data.png\" style=\"width:50%\"></div></div>";

            $(".list-vdo").html(txtHTML);
            $("#displayPaging").empty();
            showSpinner.hide();
        }

    }).fail(errorText => {
        alert(JSON.stringify(errorText));
    });
}
var manage_member = {

    "savedata": (formObj) => {
        const username = formObj.elements.username1.value;
        const titlename = formObj.elements.titlename.value;
        const name = formObj.elements.name.value;
        const surname = formObj.elements.surname.value;
        const email = formObj.elements.email.value;
        const telephone = formObj.elements.telephone.value;
        const inData = {
            "username": username,
            "titlename": titlename,
            "name": name,
            "surname": surname,
            "email": email,
            "telephone": telephone
        }
        $.post("ws/updatemember.php", inData, () => {

        }).done((ret_data) => {
            const json_ret_data = JSON.parse(ret_data);
            if (json_ret_data.message == "OK") {
                showToast("บันทึกข้อมูลสำเร็จ");
                setTimeout(() => {
                    window.location.href = "member.php";
                }, 2000);
            }

        }).fail((ret_data) => {

        });
    },
    "savepassword": (formObj) => {
        const username = formObj.elements.username.value;
        const oldpassword = formObj.elements.oldpassword.value;
        const newpassword1 = formObj.elements.newpassword1.value;
        const newpassword2 = formObj.elements.newpassword2.value;
        const inData = {
            "username": username,
            "oldpassword": oldpassword,
            "newpassword1": newpassword1,
            "newpassword2": newpassword2
        }
        if (inData.newpassword1 != inData.newpassword2) {
            showToast("รหัสผ่านใหม่ไม่ตรงกัน", "DANGER");
        } else {
            $.post("ws/updatepassword.php", inData, () => {

            }).done((ret_data) => {
                const json_ret_data = JSON.parse(ret_data);
                if (json_ret_data.message == "OK") {
                    showToast("อัพเดทรหัสผ่านเรียบร้อยแล้ว");
                    setTimeout(() => {
                        window.location.href = "member.php";
                    }, 2000);
                } else {
                    showToast("ไม่มีอัพเดท อาจเนื่องจากไม่มีการเปลี่ยนรหัสใหม่", "WARN");
                }
            }).fail((ret_data) => {

            });
        }

    }

}
function adjustImageDimension(imgObj) {

    //====== This function need Bootstrap 4.0+ =========
    //====== Also these styles
    // .landscape {
    //     height: 100%;
    // }

    // .portrait {
    //     height: 100%;
    //     width: auto;
    // }
    const x = imgObj.clientWidth;
    const y = imgObj.clientHeight;

    if (x >= y) {
        //========= landscape ==========
        imgObj.classList.add("landscape");
        imgObj.classList.remove("portrait");
        imgObj.parentElement.classList.add("text-start");
        if (x / y > 2) {
            imgObj.style.width = "100%";
            imgObj.style.height = "auto";

            imgObj.parentElement.classList.add("d-flex");
            imgObj.parentElement.style.alignItems = "center";
            imgObj.parentElement.style.backgroundColor = "#999";
        }
    } else {
        imgObj.classList.add("portrait");
        imgObj.classList.remove("landscape");
        imgObj.parentElement.classList.add("text-center");
        imgObj.parentElement.style.backgroundColor = "#999";
    }
}
function convertToSmartDateShort(inputDate) {
    const shortMonth = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.']
    const inDate = inputDate.replace(" ", "T");
    const rel_Date = new Date(inDate);
    return rel_Date.getDate() + " " + shortMonth[rel_Date.getMonth()] + (parseInt(rel_Date.getFullYear()) + 543);
}
function checklogin(formObj) {

    const username = formObj.elements.username.value;
    const password = formObj.elements.password.value;
    const inData = {
        "username": username,
        "password": password
    }
    $.post("ws/checklogin.php", inData, () => {

    }).done((ret_data) => {
        const json_ret_data = JSON.parse(ret_data);
        if (json_ret_data.message == "OK") {
            showToast("LOGIN สำเร็จ");
            setTimeout(() => {
                window.location.href = "member.php";
            }, 2000);
        } else {
            showToast("LOGIN ไม่สำเร็จ Username หรือรหัสผ่านไม่ถูกต้อง", "DANGER");
        }
    }).fail((ret_data) => {
        showToast("LOGIN ไม่สำเร็จ Username หรือรหัสผ่านไม่ถูกต้อง", "DANGER");
    });
}
$(function () {
    $("#setNormalTheme").click(function () {
        if (webInfo.webTheme != 1) {
            webInfo.webTheme = 1;
            window.localStorage.setItem("webInfo", JSON.stringify(webInfo));
            changeThemeToNormal();
        }
    });

    $("#setBlindTheme").click(function () {
        if (webInfo.webTheme != 2) {
            webInfo.webTheme = 2;
            window.localStorage.setItem("webInfo", JSON.stringify(webInfo));
            changeThemeToBlindYellow();
        }
    });
    $("#setBiggerSize").on("click", function (e) {
        e.preventDefault();

        if (fontSizeStatus != "+1") {
            document.body.style.fontSize = "125%";
            fontSizeStatus = "+1";
        }
    });
    $("#setNormalSize").on("click", function (e) {
        e.preventDefault();

        if (fontSizeStatus != "0") {
            document.body.style.fontSize = "100%";
            fontSizeStatus = "0";
        }
    });
    $("#setSmallerSize").on("click", function (e) {
        e.preventDefault();

        if (fontSizeStatus != "-1") {
            document.body.style.fontSize = "75%";
            fontSizeStatus = "-1";
        }
    });
    $(".head-image-section1").on("click", () => {
        window.location.href = "index.php";
    });
});

// =========== set theme =========== //
