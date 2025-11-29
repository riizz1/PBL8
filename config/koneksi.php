<?php
// Konfigurasi Database
$host = "localhost";        // Host database (biasanya localhost)
$username = "root";         // Username database (default: root)
$password = "";             // Password database (default: kosong untuk XAMPP)
$database = "db_pbl8"; // Ganti dengan nama database Anda

// Membuat koneksi
$koneksi = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset ke UTF-8 untuk mendukung karakter Indonesia
$koneksi->set_charset("utf8");

// Optional: Set timezone (sesuaikan dengan zona waktu Anda)
date_default_timezone_set('Asia/Jakarta');

// Session start (jika menggunakan login/session)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>