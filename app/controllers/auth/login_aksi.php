<?php
session_start();

// FILE CONFIG BERADA DI: PBL8/config/config.php
// Dari app/controllers/auth -> butuh naik 3 folder
include '../../../config/config.php';

// Hanya boleh via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /PBL8/views/auth/login.php");
    exit();
}

$username = trim($_POST['username']);
$password = $_POST['password'];

if ($username === '' || $password === '') {
    echo "<script>alert('Username dan password harus diisi!'); location.href='/PBL8/views/auth/login.php';</script>";
    exit();
}

$stmt = $config->prepare("
    SELECT users.*, roles.role_name 
    FROM users
    JOIN roles ON users.role_id = roles.role_id
    WHERE users.username = ?
");

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "<script>alert('Username tidak ditemukan!'); location.href='/PBL8/views/auth/login.php';</script>";
    exit();
}

$data = $result->fetch_assoc();

if (!password_verify($password, $data['password'])) {
    echo "<script>alert('Password salah!'); location.href='/PBL8/views/auth/login.php';</script>";
    exit();
}

$_SESSION['user_id'] = $data['user_id'];
$_SESSION['username'] = $data['username'];
$_SESSION['role_id'] = $data['role_id'];
$_SESSION['role_name'] = $data['role_name'];
$_SESSION['status'] = 'login';

switch ($data['role_name']) {
    case 'superadmin':
        echo "<script>alert('Login berhasil sebagai Superadmin!'); location.href='/PBL8/views/superadmin/dashboard.php';</script>";
        break;
    case 'dosen':
        echo "<script>alert('Login berhasil sebagai Dosen!'); location.href='/PBL8/views/admin/dashboard.php';</script>";
        break;
    default:
        echo "<script>alert('Login berhasil sebagai Mahasiswa!'); location.href='/PBL8/views/user/dashboard.php';</script>";
        break;
}

exit();
?>
