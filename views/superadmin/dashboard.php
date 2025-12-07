<?php
session_start();

// Cek hanya superadmin yang bisa akses
if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'superadmin') {

    header("Location: ../views/auth/login.php");
    header("Location: ../../views/auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Admin (Dosen)</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <?php
        include('header.php')
    ?>

<!-- isi coding disini -->

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <?php
            include('footer.php')
        ?>
</body>
</html>
