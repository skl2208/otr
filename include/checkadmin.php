<?php

    $username = isset($_SESSION["username"])?$_SESSION["username"]:"";
    $usergroup = isset($_SESSION["usergroup"])?$_SESSION["usergroup"]:"";

    if($username == "" || $usergroup != "ADMIN") {
        // Admin Zone 
        echo "<script>javascript:window.top.location.href='../errorpage.php';</script>";
        //echo "username : ".$username." , group = ".$usergroup;
    } 
?>