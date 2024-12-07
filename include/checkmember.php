<?php
    if(isset($_SESSION["username"]) && $_SESSION["username"] != "") {
        // Member Zone
    } else {
        echo "<script>javascript:window.location.href='errorpage.php';</script>";
    }
?>