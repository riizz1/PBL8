<?php
session_start();
include '../config/koneksi.php';

// Hanya proses jika method POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Ambil data dari form
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // Validasi input kosong
    if (empty($username) || empty($password)) {
        echo "<script>
        alert('Username dan password harus diisi!');
        location.href='../login.php';
        </script>";
        exit();
    }
    
    // Prepare statement untuk ambil data user
    $stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Cek apakah username ditemukan
    if ($result->num_rows === 1) {
        $data = $result->fetch_assoc();
        
        // Verifikasi password
        if (password_verify($password, $data['password'])) {
            
            // Set session
            $_SESSION['user_id'] = $data['user_id'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['role'] = $data['role'];
            $_SESSION['status'] = 'login';
            
            // Redirect berdasarkan role
            if ($data['role'] === 'dosen') {
                echo "<script>
                alert('Login berhasil sebagai Dosen!');
                location.href='../admin/dashboard.php';
                </script>";
            } else {
                echo "<script>
                alert('Login berhasil sebagai Mahasiswa!');
                location.href='../user/dashboard.php';
                </script>";
            }
            exit();
            
        } else {
            // Password salah
            echo "<script>
            alert('Password salah!');
            location.href='../login.php';
            </script>";
            exit();
        }
        
    } else {
        // Username tidak ditemukan
        echo "<script>
        alert('Username tidak ditemukan!');
        location.href='../login.php';
        </script>";
        exit();
    }
    
} else {
    // Jika bukan POST, redirect ke login
    header("Location: ../login.php");
    exit();
}
?>