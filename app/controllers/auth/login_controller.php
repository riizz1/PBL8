<?php
session_start();

include __DIR__ . '/../../../config/config.php';

// Hanya boleh via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /PBL8/views/auth/login.php");
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    echo "<script>
        alert('Username dan password harus diisi!');
        location.href='/PBL8/views/auth/login.php';
    </script>";
    exit();
}

/* =========================
   LOGIN ADMIN / SUPERADMIN
   ========================= */
$stmt = $config->prepare("
    SELECT a.*, r.role_name
    FROM admin a
    JOIN roles r ON a.role_id = r.role_id
    WHERE a.username = ?
");

if (!$stmt) {
    die("Prepare error: " . $config->error);
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {

    $data = $result->fetch_assoc();

    if (!password_verify($password, $data['password'])) {
        echo "<script>
            alert('Password salah!');
            location.href='/PBL8/views/auth/login.php';
        </script>";
        exit();
    }

    // SESSION ADMIN
    $_SESSION['status']     = 'login';
    $_SESSION['user_id']    = $data['user_id'];
    $_SESSION['username']   = $data['username'];
    $_SESSION['role_id']    = $data['role_id'];
    $_SESSION['role_name']  = $data['role_name']; // superadmin / dosen
    $_SESSION['user_type']  = 'admin';

    $stmt->close();

} else {

    /* =========================
       LOGIN MAHASISWA
       ========================= */
    $stmt->close();

    $stmt2 = $config->prepare("
        SELECT * FROM mahasiswa
        WHERE username = ?
    ");

    if (!$stmt2) {
        die("Prepare error: " . $config->error);
    }

    $stmt2->bind_param("s", $username);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    if ($result2->num_rows !== 1) {
        echo "<script>
            alert('Username tidak ditemukan!');
            location.href='/PBL8/views/auth/login.php';
        </script>";
        exit();
    }

    $data = $result2->fetch_assoc();

    if (!password_verify($password, $data['password'])) {
        echo "<script>
            alert('Password salah!');
            location.href='/PBL8/views/auth/login.php';
        </script>";
        exit();
    }

    // SESSION MAHASISWA
    $_SESSION['status']        = 'login';
    $_SESSION['user_id']       = $data['mahasiswa_id'];
    $_SESSION['username']      = $data['username'];
    $_SESSION['nama_lengkap']  = $data['nama_lengkap'];
    $_SESSION['nim']           = $data['nim'];
    $_SESSION['jurusan_id']    = $data['jurusan_id'];
    $_SESSION['prodi_id']      = $data['prodi_id'];
    $_SESSION['role_id']       = $data['role_id']; // 3
    $_SESSION['role_name']     = 'mahasiswa';
    $_SESSION['user_type']     = 'mahasiswa';

    $stmt2->close();
}

/* =========================
   REDIRECT BERDASARKAN ROLE
   ========================= */
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
        echo "<script>
            alert('Role tidak dikenali!');
            location.href='/PBL8/views/auth/login.php';
        </script>";
        break;
}

exit();
