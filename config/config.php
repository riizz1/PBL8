<?php
// Konfigurasi Database
$host = "localhost";
$username = "root";
$password = "";
$database = "pbl8";

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset ke UTF-8
$conn->set_charset("utf8");

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Start session jika belum ada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
