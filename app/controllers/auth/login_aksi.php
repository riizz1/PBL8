<?php
session_start();
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validasi
    if (empty($username) || empty($password)) {
        echo "<script>alert('Username dan password harus diisi!'); location.href='../views/auth/login.php';</script>";
        exit();
    }

    // Query JOIN roles
    $stmt = $config->prepare("
        SELECT users.*, roles.role_name 
        FROM users
        JOIN roles ON users.role_id = roles.role_id
        WHERE users.username = ?
    ");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $data = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $data['password'])) {

            // Set session
            $_SESSION['user_id'] = $data['user_id'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['role_id'] = $data['role_id'];
            $_SESSION['role_name'] = $data['role_name'];
            $_SESSION['status'] = 'login';

            // Redirect berdasarkan role
            if ($data['role_name'] === 'superadmin') {
                echo "<script>alert('Login berhasil sebagai Superadmin!'); location.href='../views/superadmin/dashboard.php';</script>";
            } 
            else if ($data['role_name'] === 'dosen') {
                echo "<script>alert('Login berhasil sebagai Dosen!'); location.href='../views/admin/dashboard.php';</script>";
            } 
            else {
                echo "<script>alert('Login berhasil sebagai Mahasiswa!'); location.href='../views/user/dashboard.php';</script>";
            }

            exit();
        } else {
            echo "<script>alert('Password salah!'); location.href='../views/auth/login.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!'); location.href='../views/auth/login.php';</script>";
        exit();
    }

} else {
    header("Location: ../views/auth/login.php");
    exit();
}
?>
