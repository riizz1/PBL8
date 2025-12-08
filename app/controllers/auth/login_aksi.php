<?php
session_start();

include __DIR__ . '/../../../config/config.php';

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

// Cek di tabel admin dulu (superadmin/dosen)
$stmt = $config->prepare("
    SELECT admin.*, roles.role_name 
    FROM admin
    JOIN roles ON admin.role_id = roles.role_id
    WHERE admin.username = ?
");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Login sebagai admin (superadmin/dosen)
    $data = $result->fetch_assoc();

    if (!password_verify($password, $data['password'])) {
        echo "<script>alert('Password salah!'); location.href='/PBL8/views/auth/login.php';</script>";
        exit();
    }

    $_SESSION['user_id'] = $data['user_id'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['role_id'] = $data['role_id'];
    $_SESSION['role_name'] = $data['role_name'];
    $_SESSION['user_type'] = 'admin'; // Untuk membedakan tabel asal

} else {
    // Cek di tabel mahasiswa
    $stmt2 = $config->prepare("
        SELECT * FROM mahasiswa WHERE username = ?
    ");
    $stmt2->bind_param("s", $username);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    if ($result2->num_rows !== 1) {
        echo "<script>alert('Username tidak ditemukan!'); location.href='/PBL8/views/auth/login.php';</script>";
        exit();
    }

    $data = $result2->fetch_assoc();

    if (!password_verify($password, $data['password'])) {
        echo "<script>alert('Password salah!'); location.href='/PBL8/views/auth/login.php';</script>";
        exit();
    }

    $_SESSION['user_id'] = $data['mahasiswa_id'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
    $_SESSION['nim'] = $data['nim'];
    $_SESSION['prodi'] = $data['prodi'];
    $_SESSION['role_id'] = 3; // Mahasiswa
    $_SESSION['role_name'] = 'mahasiswa';
    $_SESSION['user_type'] = 'mahasiswa'; // Untuk membedakan tabel asal

    $stmt2->close();
}

$_SESSION['status'] = 'login';
$stmt->close();

// Redirect sesuai role
switch ($_SESSION['role_name']) {
    case 'superadmin':
        echo "<script>alert('Login berhasil sebagai Superadmin!'); location.href='/PBL8/views/superadmin/dashboard.php';</script>";
        break;
    case 'dosen':
        echo "<script>alert('Login berhasil sebagai Dosen!'); location.href='/PBL8/views/admin/dashboard.php';</script>";
        break;
    case 'mahasiswa':
        echo "<script>alert('Login berhasil sebagai Mahasiswa!'); location.href='/PBL8/views/user/dashboard.php';</script>";
        break;
    default:
        echo "<script>alert('Role tidak dikenali!'); location.href='/PBL8/views/auth/login.php';</script>";
        break;
}

exit();
