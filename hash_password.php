<?php
include 'config/koneksi.php';

// Password asli yang ingin di-hash
$username = 'admin'; // sesuaikan username Anda
$password_asli = 'admin123'; // password asli yang Anda insert manual

// Hash password
$password_hash = password_hash($password_asli, PASSWORD_DEFAULT);

// Update ke database
$stmt = $koneksi->prepare("UPDATE users SET password = ? WHERE username = ?");
$stmt->bind_param("ss", $password_hash, $username);

if ($stmt->execute()) {
    echo "Password berhasil di-hash!<br>";
    echo "Username: $username<br>";
    echo "Password Hash: $password_hash<br>";
    echo "<br><strong>Silakan login dengan password asli Anda: $password_asli</strong>";
} else {
    echo "Gagal update password!";
}

$stmt->close();
?>