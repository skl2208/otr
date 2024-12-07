

$(function () {

    var height = screen.height;

    $("#showHeight").append(height);
    if(window.innerHeight > window.innerWidth) {
        console.log("Mobile");

    } else {
        console.log("desktop");
    }
});