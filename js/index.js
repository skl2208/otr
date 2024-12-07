var fontSizeStatus = "0";

function topFunction() {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}
function scrollFunction() {

    const mybutton = document.getElementById("scrollBtn");

    if (typeof mybutton === 'object' && mybutton !== null) {
        if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    }

}
function createScrollButton() {

    const newBtn = document.createElement("button");

    newBtn.setAttribute("id", "scrollBtn");
    newBtn.setAttribute("title", "Go to top");
    newBtn.setAttribute("onclick", "topFunction()");

    const newImage = document.createElement("img");
    newImage.src = "images/icon_scroll_up.png";

    newBtn.appendChild(newImage);


    document.body.appendChild(newBtn);
}
$(function () {

    var myCarousel = document.querySelector('#carouselBanner');
    var carousel = new bootstrap.Carousel(myCarousel, { interval: 5000 });

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
    window.onload = function () { createScrollButton(); }
    window.onscroll = function () { scrollFunction() };
});