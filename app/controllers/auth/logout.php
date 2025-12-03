<?php
session_start();
<<<<<<< HEAD
session_destroy();
header("Location: ../views/auth/login.php");
exit();
?>
=======
session_unset();
session_destroy();
header("Location: ../../public/views/auth/login.php");
exit();
>>>>>>> dec0de8 (membuat dashboard di superadmin dan membuat agar admin yang ada di superadmin bisa jalan)
