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

// Cek di tabel admin dulu (superadmin/dosen) - TANPA JOIN DULU
$stmt = $config->prepare("SELECT * FROM admin WHERE username = ?");

if (!$stmt) {
    die("Error prepare statement: " . $config->error);
}

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

    // Ambil role_name dari tabel roles
    $role_name = 'dosen'; // default
    if (isset($data['role_id'])) {
        $stmtRole = $config->prepare("SELECT role_name FROM roles WHERE role_id = ?");
        if ($stmtRole) {
            $stmtRole->bind_param("i", $data['role_id']);
            $stmtRole->execute();
            $resultRole = $stmtRole->get_result();
            if ($resultRole->num_rows === 1) {
                $roleData = $resultRole->fetch_assoc();
                $role_name = $roleData['role_name'];
            }
            $stmtRole->close();
        }
    }

    // Cek kolom ID yang tersedia
    $user_id = isset($data['admin_id']) ? $data['admin_id'] : (isset($data['user_id']) ? $data['user_id'] : null);

    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $data['username'];
    $_SESSION['role_id'] = isset($data['role_id']) ? $data['role_id'] : 2;
    $_SESSION['role_name'] = $role_name;
    $_SESSION['user_ty  pe'] = 'admin';

} else {
    // Cek di tabel mahasiswa
    $stmt2 = $config->prepare("SELECT * FROM mahasiswa WHERE username = ?");
    
    if (!$stmt2) {
        die("Error prepare statement: " . $config->error);
    }
    
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
    $_SESSION['role_id'] = 3;
    $_SESSION['role_name'] = 'mahasiswa';
    $_SESSION['user_type'] = 'mahasiswa';

    $stmt2->close();
}

$_SESSION['status'] = 'login';
$stmt->close();

// Redirect sesuai role
switch ($_SESSION['role_name']) {
    case 'superadmin':
        header("Location: /PBL8/views/superadmin/dashboard.php");
        break;
    case 'dosen':
        header("Location: /PBL8/views/admin/dashboard.php");
        break;
    case 'mahasiswa':
        header("Location: /PBL8/views/user/dashboard.php");
        break;
    default:
        echo "<script>alert('Role tidak dikenali!'); location.href='/PBL8/views/auth/login.php';</script>";
        break;
}

exit();
?>