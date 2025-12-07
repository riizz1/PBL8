<?php
session_start();
session_destroy();
header("Location: /PBL8/views/auth/login.php");
exit();
?>
