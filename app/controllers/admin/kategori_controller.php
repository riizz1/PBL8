<?php
require_once realpath(__DIR__ . '/../../../config/config.php');
require_once realpath(__DIR__ . '/../../models/kategori_model.php');

header('Content-Type: application/json');

$kategori = new KategoriModel($config);

// ========== TAMBAH KATEGORI ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['_method'])) {
    
    $nama = isset($_POST['nama_kategori']) ? trim($_POST['nama_kategori']) : '';
    $deskripsi = isset($_POST['deskripsi']) ? trim($_POST['deskripsi']) : '';
    
    if (empty($nama)) {
        echo json_encode([
            'success' => false,
            'message' => 'Nama kategori tidak boleh kosong'
        ]);
        exit;
    }
    
    // CEK DUPLIKASI NAMA
    if ($kategori->cekDuplikasiNama($nama)) {
        echo json_encode([
            'success' => false,
            'message' => 'Nama kategori sudah ada, gunakan nama lain'
        ]);
        exit;
    }
    
    $result = $kategori->tambahKategori($nama, $deskripsi);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menambahkan kategori'
        ]);
    }
    exit;
}

// ========== EDIT KATEGORI ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
    
    $id = isset($_POST['kategori_id']) ? intval($_POST['kategori_id']) : 0;
    $nama = isset($_POST['nama_kategori']) ? trim($_POST['nama_kategori']) : '';
    $deskripsi = isset($_POST['deskripsi']) ? trim($_POST['deskripsi']) : '';
    
    if (empty($nama) || $id <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Data tidak valid'
        ]);
        exit;
    }
    
    // CEK DUPLIKASI NAMA (exclude ID yang sedang diedit)
    if ($kategori->cekDuplikasiNama($nama, $id)) {
        echo json_encode([
            'success' => false,
            'message' => 'Nama kategori sudah ada, gunakan nama lain'
        ]);
        exit;
    }
    
    $result = $kategori->updateKategori($id, $nama, $deskripsi);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Kategori berhasil diupdate'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal mengupdate kategori'
        ]);
    }
    exit;
}

// ========== HAPUS KATEGORI ==========
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    
    $id = intval($_GET['id']);
    
    if ($id <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'ID tidak valid'
        ]);
        exit;
    }
    
    // Cek apakah kategori masih digunakan
    if ($kategori->cekKategoriDigunakan($id)) {
        echo json_encode([
            'success' => false,
            'message' => 'Kategori tidak dapat dihapus karena masih digunakan'
        ]);
        exit;
    }
    
    $result = $kategori->hapusKategori($id);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Kategori berhasil dihapus'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menghapus kategori'
        ]);
    }
    exit;
}

// Invalid request
echo json_encode([
    'success' => false,
    'message' => 'Invalid request'
]);
exit;
?>