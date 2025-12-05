<?php
// Konfigurasi Database
$host = "localhost";
$username = "root";
$password = "";
$database = "db_pbl8";

// Membuat koneksi
$config = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($config->connect_error) {
    die("Koneksi gagal: " . $config->connect_error);
}

// Set charset ke UTF-8
$config->set_charset("utf8");

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Start session jika belum ada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
