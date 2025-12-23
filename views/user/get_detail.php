<?php

// Aktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

try {
    // Cek apakah ada parameter ID
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID tidak ditemukan']);
        exit;
    }

    // Path absolut untuk require
    $controllerPath = __DIR__ . '/../../app/controllers/user/pengumuman_controller.php';
    
    if (!file_exists($controllerPath)) {
        echo json_encode(['success' => false, 'message' => 'Controller file tidak ditemukan: ' . $controllerPath]);
        exit;
    }

    require_once $controllerPath;

    $controller = new PengumumanControllerUser();
    $data = $controller->detail($_GET['id']);

    // Jika data tidak ditemukan
    if ($data === null || !isset($data['pengumuman'])) {
        echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan di database']);
        exit;
    }

    $pengumuman = $data['pengumuman'];

    // Validasi data
    if (empty($pengumuman['judul']) || empty($pengumuman['isi'])) {
        echo json_encode(['success' => false, 'message' => 'Data pengumuman tidak lengkap']);
        exit;
    }

    // Konversi bulan ke bahasa Indonesia
    $bulanIndo = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    $timestamp = strtotime($pengumuman['created_at']);
    $tanggal = date('d', $timestamp);
    $bulan = $bulanIndo[(int)date('m', $timestamp)];
    $tahun = date('Y', $timestamp);
    $waktu = date('H:i', $timestamp);

    // Format data untuk response
    $response = [
        'success' => true,
        'pengumuman' => [
            'id' => $pengumuman['pengumuman_id'],
            'judul' => htmlspecialchars($pengumuman['judul']),
            'isi' => nl2br(htmlspecialchars($pengumuman['isi'])),
            'kategori' => htmlspecialchars(ucfirst($pengumuman['nama_kategori'] ?? 'Umum')),
            'tanggal_lengkap' => "$tanggal $bulan $tahun, $waktu WIB"
        ]
    ];

    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Error: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}