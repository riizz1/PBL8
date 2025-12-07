<?php
session_start();
include '../config/config.php';

// Cek hanya superadmin
if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'superadmin') {
    header("Location: ../views/auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo "<script>alert('Username dan password tidak boleh kosong!'); location.href='/PBL8/views/superadmin/dosen.php';</script>";
        exit();
    }

    // Cek username duplikat
    $check = $config->prepare("SELECT user_id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username sudah digunakan!'); location.href='/PBL8/views/superadmin/dosen.php';</script>";
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // role_id = 2 untuk dosen/admin biasa
    $role_id = 2;

    // Insert user
    $stmt = $config->prepare("INSERT INTO users (username, password, role_id, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("ssi", $username, $hashed_password, $role_id);

    if ($stmt->execute()) {
        echo "<script>alert('Akun admin/dosen berhasil dibuat!'); location.href='/PBL8/views/superadmin/dosen.php';</script>";
    } else {
        echo "<script>alert('Gagal membuat akun!'); location.href='/PBL8/views/superadmin/dosen.php';</script>";
    }

} else {
    header("Location: /PBL8/views/superadmin/dosen.php");
    exit();
}
?>
