<?php
require_once realpath(__DIR__ . '/../../config/config.php');
require_once realpath(__DIR__ . '/../models/kategori_model.php');

$kategori = new KategoriModel($config);

// ========== TAMBAH KATEGORI ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['_method'])) {
    
    $nama = isset($_POST['nama_kategori']) ? trim($_POST['nama_kategori']) : '';
    $deskripsi = isset($_POST['deskripsi']) ? trim($_POST['deskripsi']) : '';
    
    if (empty($nama)) {
        header("Location: ../../views/admin/kategori.php?status=error&msg=" . urlencode("Nama kategori tidak boleh kosong"));
        exit;
    }
    
    $result = $kategori->tambahKategori($nama, $deskripsi);
    
    if ($result) {
        header("Location: ../../views/admin/kategori.php?status=success&msg=" . urlencode("Kategori berhasil ditambahkan"));
    } else {
        header("Location: ../../views/admin/kategori.php?status=error&msg=" . urlencode("Gagal menambahkan kategori"));
    }
    exit;
}

// ========== EDIT KATEGORI ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
    
    $id = isset($_POST['kategori_id']) ? intval($_POST['kategori_id']) : 0;
    $nama = isset($_POST['nama_kategori']) ? trim($_POST['nama_kategori']) : '';
    $deskripsi = isset($_POST['deskripsi']) ? trim($_POST['deskripsi']) : '';
    
    if (empty($nama) || $id <= 0) {
        header("Location: ../../views/admin/kategori.php?status=error&msg=" . urlencode("Data tidak valid"));
        exit;
    }
    
    $result = $kategori->updateKategori($id, $nama, $deskripsi);
    
    if ($result) {
        header("Location: ../../views/admin/kategori.php?status=success&msg=" . urlencode("Kategori berhasil diupdate"));
    } else {
        header("Location: ../../views/admin/kategori.php?status=error&msg=" . urlencode("Gagal mengupdate kategori"));
    }
    exit;
}

// ========== HAPUS KATEGORI ==========
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    
    $id = intval($_GET['id']);
    
    if ($id <= 0) {
        header("Location: ../../views/admin/kategori.php?status=error&msg=" . urlencode("ID tidak valid"));
        exit;
    }
    
    $result = $kategori->hapusKategori($id);
    
    if ($result) {
        header("Location: ../../views/admin/kategori.php?status=success&msg=" . urlencode("Kategori berhasil dihapus"));
    } else {
        header("Location: ../../views/admin/kategori.php?status=error&msg=" . urlencode("Gagal menghapus kategori"));
    }
    exit;
}

// Redirect jika akses langsung
header("Location: ../../views/admin/kategori.php");
exit;
?>