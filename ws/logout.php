<?php
session_start();
session_unset();
header( "location: ../logout.php" );
exit(0);
?>