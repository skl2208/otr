var fontSizeStatus = "0";

$(document).ready(function () {

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
    $(".head-image-section1").on("click",()=>{
        window.location.href="index.html";
    });

    document.getElementById("username").focus();

});