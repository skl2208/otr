var uploadPicture = {
    "uploadFile": (typenews, output_id) => {
        const picture_source = $("#showPreviewPicture").attr("src");
        // เรียก Web Service สำหรับบันทึกภาพที่ได้จากการ upload 

        const inData = {
            "typenews": typenews,
            "image_url": picture_source
        };

        $.post("save_pictodb_ws.php", inData, () => {

        }).done((ret_data) => {
            const json_ret_data = JSON.parse(ret_data);
            if (json_ret_data.message == "OK") {
                document.getElementById(output_id).innerHTML = json_ret_data.info;
                uploadPicture.close();
            }

        }).fail(() => {
            alert("fail to upload");
            uploadPicture.close();
        });


        $("#uploadpicture").hide();

    },
    "upload": () => {
        document.getElementById("albumpicture").style.display = "none";
        document.getElementById("uploadpicture").style.display = "block";
    },
    "album": () => {
        document.getElementById("albumpicture").style.display = "block";
        document.getElementById("uploadpicture").style.display = "none";
    },
    "previewImage": (inURL, command) => {
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
    "showalbum": (catagory_name, pageno,command) => {
        $("#showListAlbumPicture").show();

        if (command == "show" || command == "on") {

            if (pageno == null) {
                pageno = 1;
            }
            var inData = {
                "pageno": pageno,
                "catagory": catagory_name,
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

                            strtxt = "<div class=\"col-3 text-center bg-white p-1\"><a href=\"javascript:uploadPicture.previewImage('" + ret_list.image_url + "','show');\" title=\"" + ret_list.image_desc + "\">";
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
                            strtxt2 += "<a class=\"page-link\" href=\"javascript:uploadPicture.showalbum(document.getElementById('catagory').value," + (i + 1) + ",'show')\">" + (i + 1) + "</a></li>";
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

    },
    "changePictureFromAlbum": (target_id) => {
        document.querySelector("#albumpicture .preview").style.display = "none";
        document.getElementById("showListAlbumPicture").style.display = "flex";
        document.getElementById(target_id).innerHTML = document.getElementById("changePictureFromAlbum").value;
        document.getElementById("changePictureFromAlbum").value = "";
        uploadPicture.close();
    },
    "close": () => {
        document.getElementById("albumpicture").style.display = "none";
        document.getElementById("uploadpicture").style.display = "none";
        document.getElementById("showPreviewPicture").setAttribute("src", "../images/empty_image.png");
        document.getElementById("formuploadpicture").reset();
    },
    "closepreview": () => {
        document.getElementById("changePictureFromAlbum").src = "";
        document.querySelector("#albumpicture .preview").style.display = "none";
        document.getElementById("showListAlbumPicture").style.display = "flex";
    }
}