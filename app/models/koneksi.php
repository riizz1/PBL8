<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "db_pbl8";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Gagal konek ke database: " . mysqli_connect_error());
}
?>