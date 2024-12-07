<?php include "include/header.php"; ?>
<section class="loginSection">
    <div class="shadow-lg">
        <form method="POST" name="formlogin" id="formlogin">
            <label for="username">เฉพาะเจ้าหน้าที่</label>
            <div class="icon-input m-0 p-0"><i class="showicon icon-login"></i><input type="text" class="d-block w-100" name="username" id="username" data-caption="LOGIN" type="text" value="" placeholder="ID" title="รหัสสำหรับเข้าใช้งาน"></div>
            <div class="icon-input m-0 p-0"><i class="showicon icon-password"></i><input class="d-block w-100" name="password" id="password" type="password" value="" placeholder="Password" title="รหัสผ่าน"></div>
            <button class="btn-ok" id="btnSubmit" type="button" onclick="javascript:checklogin(document.forms.formlogin);">เข้าสู่ระบบ</button>
        </form>
    </div>
</section>
<div id="showAlert">
    <div>
        <div>
            <img src="images/icon_ok.png">
        </div>
        <div></div>
        <div>
            <button onclick="javascript:showAlert.hide();" class="btnOK">ปิด</button>
        </div>
    </div>
</div>
<div id="showToast"></div>
<script>
    var input = document.getElementById("password");
    input.addEventListener("keyup", function(event) {
        // Number 13 is the "Enter" key on the keyboard
        if (event.code === "Enter" || event.code === "NumpadEnter") {
            // Cancel the default action, if needed
            event.preventDefault();
            document.getElementById("btnSubmit").focus();
            document.getElementById("btnSubmit").click();
        }
    });
    document.getElementById("username").focus();
</script>
<?php include "include/footer.php"; ?>