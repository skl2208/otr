var showAlert = {
    "callbackfn": () => { },
    "show": (msg, typemsg, callbackfn) => {
        if (msg != "" && msg != null && msg != undefined) {
            if (typemsg == null || typemsg == undefined) {
                typemsg = "";
            }

            if (typemsg.toUpperCase() == "DANGER") {
                img_src = "../images/icon_danger.png";
                $("#showAlert>div").css("background-color", "var(--toast-danger-bg)").css("color", "black").css("border-color", "var(--toast-danger-bg)");
            } else if (typemsg.toUpperCase() == "WARN") {
                img_src = "../images/icon_warn.png";
                $("#showAlert>div").css("background-color", "var(--toast-warn-bg)").css("border-color", "var(--toast-warn-bg)");
            } else {
                img_src = "../images/icon_ok.png";
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
};

var showConfirm = {
    "callbackfn": () => { },
    "cancelfn": () => { },
    "show": (msg,confirmFn,abortFn) => {

        showConfirm.callbackfn = confirmFn;
        showConfirm.cancelfn = abortFn;
        if (msg != "" && msg != null && msg != undefined) {
            img_src = "../images/icon_warn.png";
            $("#showConfirm>div").css("background-color", "var(--toast-warn-bg)").css("border-color", "var(--toast-warn-bg)");

            document.querySelector("#showConfirm img").src = img_src;
            document.getElementById("showConfirm").style.display = "block";
            document.querySelector("#showConfirm>div div:nth-child(2)").innerHTML = msg;

        }
    },
    "confirm": () => {
        showConfirm.hide();
        if(showConfirm.callbackfn!=null && showConfirm.callbackfn!=undefined) {
            showConfirm.callbackfn();
        }
    },
    "cancel": () => {
        showConfirm.hide();
        if (showConfirm.cancelfn != null && showConfirm.cancelfn != undefined) {
            showConfirm.cancelfn();
        }
    },
    "hide": () => {
        document.querySelector("#showConfirm>div div:nth-child(2)").innerHTML = "";
        document.getElementById("showConfirm").style.display = "none";
    }
};

const showSpinner = {
    "show": ()=> {
        $("#spinner").show();
    },
    "hide": ()=> {
        $("#spinner").hide();
    }
}

