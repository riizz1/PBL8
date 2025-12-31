<?php
session_start();

// Proteksi halaman - harus login dulu
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
    echo "<script>
        alert('Anda harus login terlebih dahulu!');
        location.href='/PBL8/views/auth/login.php';
    </script>";
    exit();
}

// Proteksi role - hanya mahasiswa yang bisa akses
if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'dosen') {
    echo "<script>
        alert('Akses ditolak! Halaman ini hanya untuk Dosen.');
        location.href='/PBL8/views/auth/login.php';
    </script>";
    exit();
}
require_once __DIR__ . '/../../app/controllers/admin/pengumuman_controller.php';

$controller = new PengumumanControllerAdmin();

// Handle AJAX requests - PENTING: Harus sebelum output HTML apapun
if (isset($_POST['action'])) {
    // Clear any output buffer
    ob_clean();

    header('Content-Type: application/json');

    try {
        switch ($_POST['action']) {
            case 'create':
                echo json_encode($controller->create($_POST));
                break;

            case 'update':
                echo json_encode($controller->edit($_POST));
                break;

            case 'delete':
                echo json_encode($controller->hapus($_POST['pengumuman_id']));
                break;

            case 'get':
                $id = intval($_POST['pengumuman_id']);
                echo json_encode($controller->getById($id));
                break;

            case 'getKelas':
                $prodi_id = intval($_POST['prodi_id']);
                echo json_encode($controller->getKelasByProdi($prodi_id));
                break;

            case 'kirimEmail':
                echo json_encode($controller->kirimEmail($_POST));
                break;

            default:
                echo json_encode([
                    'success' => false,
                    'message' => 'Action tidak valid'
                ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }

    exit();
}

// Setelah ini baru ambil data untuk tampilan
$data = $controller->index();

$pengumuman = $data['pengumuman'];
$kategori = $data['kategori'];
$jurusan = $data['jurusan'];
$prodi = $data['prodi'];

// Pagination settings
$itemsPerPage = 10;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

// Get all pengumuman
$allPengumuman = $pengumuman;
$totalData = count($allPengumuman);
$totalPages = ceil($totalData / $itemsPerPage);

// Slice data for current page
$pengumumanList = array_slice($allPengumuman, $offset, $itemsPerPage);

// Calculate display range
$startData = $totalData > 0 ? $offset + 1 : 0;
$endData = min($offset + $itemsPerPage, $totalData);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Pengumuman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* ================= PENGUMUMAN ================= */
        .table-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-top: 20px;
        }

        .table-header {
            padding: 20px 25px;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
        }

        .entries-info {
            font-size: 14px;
            color: #777;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        /* ================= HEADER TABEL BIRU CERAH ================= */
        table.table {
            margin-bottom: 0;
        }

        table.table thead th {
            background-color: #51c8e9 !important;
            /* Warna biru cerah */
            color: white !important;
            text-align: center !important;
            padding: 15px 20px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table.table thead th:first-child {
            text-align: left !important;
        }

        /* ================= TBODY STYLING ================= */
        table.table tbody td {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
            font-size: 14px;
        }

        table.table tbody tr {
            transition: background 0.2s;
        }

        table.table tbody tr:nth-child(odd) td {
            background-color: #ffffff !important;
        }

        table.table tbody tr:nth-child(even) td {
            background-color: #f9f9f9 !important;
        }

        table.table tbody tr:hover td {
            background-color: #e8f8fd !important;
            /* Hover dengan warna biru cerah transparan */
        }

        table.table tbody td:first-child {
            text-align: left !important;
        }

        td.isi-cell {
            white-space: pre-wrap;
            word-wrap: break-word;
            max-width: 400px;
        }

        td.action-cell {
            white-space: nowrap;
            /* Paksa tombol tetap 1 baris */
            vertical-align: middle;
        }

        /* ================= EMPTY STATE ================= */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
            margin-top: 20px;
        }

        .empty-state i {
            font-size: 5rem;
            color: #dee2e6;
            margin-bottom: 20px;
        }

        .empty-state h5 {
            color: #6c757d;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #adb5bd;
            margin-bottom: 20px;
        }

        /* ================= PAGINATION ================= */
        .pagination {
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 2px solid #f0f0f0;
        }

        .page-info {
            font-size: 14px;
            color: #777;
            margin-left: 10px;
        }

        .page-buttons {
            display: flex;
            gap: 10px;
        }

        .page-btn {
            padding: 8px 15px;
            border: 1px solid #e0e0e0;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            text-decoration: none;
            color: #333;
        }

        .page-btn:hover:not(:disabled) {
            background: #51c8e9;
            /* Warna biru cerah */
            color: white;
            border-color: #51c8e9;
        }

        .page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .page-btn.active {
            background: #51c8e9;
            /* Warna biru cerah */
            color: white;
            border-color: #51c8e9;
        }

        .page-dots {
            padding: 8px 5px;
            color: #999;
            font-weight: bold;
        }

        /* ================= MODAL GELAP ================= */
        .modal-content {
            background-color: #0000004f;
            color: white;
            border-radius: 10px;
            padding: 20px;
        }

        .modal-content input,
        .modal-content textarea,
        .modal-content select {
            border: 1px solid #b0b0b0 !important;
            color: black !important;
            background-color: white !important;
            transition: all 0.3s ease;
        }

        .modal-content input:focus,
        .modal-content textarea:focus,
        .modal-content select:focus {
            border: 1px solid #51c8e9 !important;
            /* Border focus biru cerah */
            background-color: #f2f2f2 !important;
            color: black !important;
            box-shadow: 0 0 0 0.2rem rgba(81, 200, 233, 0.25) !important;
            /* Glow effect */
        }

        .btn-close {
            filter: invert(0) !important;
            background-color: white;
            opacity: 1;
        }

        input::placeholder,
        textarea::placeholder {
            color: #999999 !important;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        .form-control {
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group {
            position: relative;
        }

        /* Dropdown icon styling */
        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        .select-wrapper {
            position: relative;
        }

        .select-wrapper i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #6c757d;
        }

        /* Hide/show conditional fields */
        .conditional-field {
            display: none;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            10%,
            30%,
            50%,
            70%,
            90% {
                transform: translateX(-5px);
            }

            20%,
            40%,
            60%,
            80% {
                transform: translateX(5px);
            }
        }

        .shake {
            animation: shake 0.5s;
        }

        @media (max-width: 768px) {
            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .pagination {
                flex-direction: column;
                gap: 15px;
            }

            .page-buttons {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <?php include("header.php"); ?>

    <main class="container my-4">
        <div class="mb-3">
            <h4 class="fw-bold mb-1">Pengumuman</h4>
            <button class="btn btn-secondary btn-sm mt-2" data-bs-toggle="modal"
                data-bs-target="#modalTambahPengumuman">
                + Tambah Pengumuman
            </button>
        </div>

        <div id="alertContainer"></div>

        <?php if (empty($allPengumuman)): ?>
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h5>Belum Ada Pengumuman</h5>
                <p>Silakan tambahkan pengumuman pertama Anda</p>
            </div>
        <?php else: ?>
            <div class="table-section">
                <div class="table-header">
                    <div class="table-title">Daftar Pengumuman</div>
                    <div class="entries-info">
                        Menampilkan <?= $startData; ?> - <?= $endData; ?> dari <?= $totalData; ?> pengumuman
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Isi</th>
                                <th>Kategori</th>
                                <th>Target</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pengumumanList as $p): ?>
                                <tr>
                                    <td><?= htmlspecialchars($p['judul']) ?></td>
                                    <td class="isi-cell"><?= nl2br(htmlspecialchars($p['isi'])) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($p['nama_kategori']) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($p['target_display'] ?? 'Semua Mahasiswa') ?>
                                    </td>
                                    <td class="text-center action-cell">
                                        <button class="btn btn-info btn-sm email-btn" data-id="<?= $p['pengumuman_id'] ?>"
                                            data-judul="<?= htmlspecialchars($p['judul']) ?>"
                                            data-target="<?= htmlspecialchars($p['target_display'] ?? 'Semua Mahasiswa') ?>"
                                            data-bs-toggle="modal" data-bs-target="#modalKirimEmail" title="Kirim Email">
                                            <i class="bi bi-envelope-fill"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm edit-btn" data-id="<?= $p['pengumuman_id'] ?>"
                                            data-bs-toggle="modal" data-bs-target="#modalEditPengumuman" title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $p['pengumuman_id'] ?>"
                                            data-judul="<?= htmlspecialchars($p['judul']) ?>" title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($totalData > 0): ?>
                    <div class="pagination">
                        <div class="page-info">
                            Halaman <?= $currentPage; ?> dari <?= $totalPages; ?>
                        </div>

                        <div class="page-buttons">
                            <?php if ($currentPage > 1): ?>
                                <a href="?page=<?= $currentPage - 1; ?>" class="page-btn">← Sebelumnya</a>
                            <?php else: ?>
                                <button class="page-btn" disabled>← Sebelumnya</button>
                            <?php endif; ?>

                            <?php
                            $range = 2;
                            $start = max(1, $currentPage - $range);
                            $end = min($totalPages, $currentPage + $range);

                            if ($start > 1) {
                                echo '<a href="?page=1" class="page-btn">1</a>';
                                if ($start > 2)
                                    echo '<span class="page-dots">...</span>';
                            }

                            for ($i = $start; $i <= $end; $i++) {
                                $active = ($i == $currentPage) ? 'active' : '';
                                echo '<a href="?page=' . $i . '" class="page-btn ' . $active . '">' . $i . '</a>';
                            }

                            if ($end < $totalPages) {
                                if ($end < $totalPages - 1)
                                    echo '<span class="page-dots">...</span>';
                                echo '<a href="?page=' . $totalPages . '" class="page-btn">' . $totalPages . '</a>';
                            }
                            ?>

                            <?php if ($currentPage < $totalPages): ?>
                                <a href="?page=<?= $currentPage + 1; ?>" class="page-btn">Selanjutnya →</a>
                            <?php else: ?>
                                <button class="page-btn" disabled>Selanjutnya →</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- MODAL TAMBAH PENGUMUMAN -->
    <div class="modal fade" id="modalTambahPengumuman" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title w-100 text-center">Penambahan Pengumuman</h5>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formTambahPengumuman">
                        <input type="hidden" name="action" value="create">

                        <div class="mb-3">
                            <label class="form-label">Judul <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="judul" required placeholder="Masukkan Judul">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <div class="select-wrapper">
                                <select name="kategori_id" class="form-control" required>
                                    <option value="">Pilih kategori</option>
                                    <?php foreach ($kategori as $k): ?>
                                        <option value="<?= $k['kategori_id'] ?>">
                                            <?= htmlspecialchars($k['nama_kategori']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pengumuman Untuk <span class="text-danger">*</span></label>
                            <div class="select-wrapper">
                                <select name="target_type" id="tambahTargetType" class="form-control" required
                                    onchange="handleTargetChange('tambah')">
                                    <option value="">Pilih Target</option>
                                    <option value="all">Semua Mahasiswa</option>
                                    <option value="jurusan">Jurusan</option>
                                </select>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                        </div>

                        <!-- Conditional Fields Tambah -->
                        <div id="tambahJurusanField" class="mb-3 conditional-field">
                            <label class="form-label">Jurusan <span class="text-danger">*</span></label>
                            <div class="select-wrapper">
                                <select name="target_jurusan_id" id="tambahJurusan" class="form-control"
                                    onchange="handleJurusanChange('tambah')">
                                    <option value="">Pilih Jurusan</option>
                                    <?php foreach ($jurusan as $j): ?>
                                        <option value="<?= $j['jurusan_id'] ?>"><?= htmlspecialchars($j['nama_jurusan']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                        </div>

                        <div id="tambahProdiField" class="mb-3 conditional-field">
                            <label class="form-label">Prodi <span class="text-danger">*</span></label>
                            <div class="select-wrapper">
                                <select name="target_prodi_id" id="tambahProdi" class="form-control"
                                    onchange="handleProdiChange('tambah')">
                                    <option value="">Pilih Prodi</option>
                                </select>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                        </div>

                        <div id="tambahKelasField" class="mb-3 conditional-field">
                            <label class="form-label">Kelas <span class="text-danger">*</span></label>
                            <div class="select-wrapper">
                                <select name="target_kelas" id="tambahKelas" class="form-control">
                                    <option value="">Pilih Kelas</option>
                                </select>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Isi Pengumuman <span class="text-danger">*</span></label>
                            <textarea name="isi" class="form-control" rows="4" required
                                placeholder="Masukkan Isi Pengumuman"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" id="btnTambah">
                            <span class="btn-text">Simpan</span>
                            <span class="spinner-border spinner-border-sm d-none"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT (Similar structure) -->
    <div class="modal fade" id="modalEditPengumuman" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title w-100 text-center">Edit Pengumuman</h5>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditPengumuman">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="pengumuman_id" id="editPengumumanId">

                        <div class="mb-3">
                            <label class="form-label">Judul <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="judul" id="editJudul" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <div class="select-wrapper">
                                <select name="kategori_id" id="editKategori" class="form-control" required>
                                    <option value="">Pilih kategori</option>
                                    <?php foreach ($kategori as $k): ?>
                                        <option value="<?= $k['kategori_id'] ?>">
                                            <?= htmlspecialchars($k['nama_kategori']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pengumuman Untuk <span class="text-danger">*</span></label>
                            <div class="select-wrapper">
                                <select name="target_type" id="editTargetType" class="form-control" required
                                    onchange="handleTargetChange('edit')">
                                    <option value="">Pilih Target</option>
                                    <option value="all">Semua Mahasiswa</option>
                                    <option value="jurusan">Jurusan</option>
                                </select>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                        </div>

                        <div id="editJurusanField" class="mb-3 conditional-field">
                            <label class="form-label">Jurusan <span class="text-danger">*</span></label>
                            <div class="select-wrapper">
                                <select name="target_jurusan_id" id="editJurusan" class="form-control"
                                    onchange="handleJurusanChange('edit')">
                                    <option value="">Pilih Jurusan</option>
                                    <?php foreach ($jurusan as $j): ?>
                                        <option value="<?= $j['jurusan_id'] ?>"><?= htmlspecialchars($j['nama_jurusan']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                        </div>

                        <div id="editProdiField" class="mb-3 conditional-field">
                            <label class="form-label">Prodi <span class="text-danger">*</span></label>
                            <div class="select-wrapper">
                                <select name="target_prodi_id" id="editProdi" class="form-control"
                                    onchange="handleProdiChange('edit')">
                                    <option value="">Pilih Prodi</option>
                                </select>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                        </div>

                        <div id="editKelasField" class="mb-3 conditional-field">
                            <label class="form-label">Kelas <span class="text-danger">*</span></label>
                            <div class="select-wrapper">
                                <select name="target_kelas" id="editKelas" class="form-control">
                                    <option value="">Pilih Kelas</option>
                                </select>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Isi Pengumuman <span class="text-danger">*</span></label>
                            <textarea name="isi" id="editIsi" class="form-control" rows="4" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100" id="btnEdit">
                            <span class="btn-text">Simpan Perubahan</span>
                            <span class="spinner-border spinner-border-sm d-none"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL KIRIM EMAIL -->
    <div class="modal fade" id="modalKirimEmail" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title w-100 text-center">
                        <i class="bi bi-envelope-fill me-2"></i>Kirim Pengumuman via Email
                    </h5>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Informasi:</strong><br>
                        Email akan dikirim ke mahasiswa sesuai target pengumuman ini.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Pengumuman:</label>
                        <p id="emailJudul" class="form-control-plaintext bg-light p-2 rounded"></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Target Penerima:</label>
                        <p id="emailTarget" class="form-control-plaintext bg-light p-2 rounded"></p>
                    </div>

                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong><br>
                        Proses pengiriman email mungkin memerlukan waktu beberapa saat tergantung jumlah penerima.
                    </div>

                    <form id="formKirimEmail">
                        <input type="hidden" name="action" value="kirimEmail">
                        <input type="hidden" name="pengumuman_id" id="emailPengumumanId">

                        <button type="submit" class="btn btn-primary w-100" id="btnKirimEmail">
                            <i class="bi bi-send-fill me-2"></i>
                            <span class="btn-text">Kirim Email Sekarang</span>
                            <span class="spinner-border spinner-border-sm d-none ms-2"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include("footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Data untuk dropdown dinamis
        const prodiData = <?= json_encode($prodi) ?>;

        function showAlert(message, type = 'success') {
            const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
            document.getElementById('alertContainer').innerHTML = alertHtml;

            // Scroll ke alert
            document.getElementById('alertContainer').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

            setTimeout(() => {
                const alert = document.querySelector('.alert');
                if (alert) alert.remove();
            }, 8000);
        }

        // Handle perubahan target type
        function handleTargetChange(prefix) {
            const targetType = document.getElementById(prefix + 'TargetType').value;

            // Hide all conditional fields
            document.getElementById(prefix + 'JurusanField').style.display = 'none';
            document.getElementById(prefix + 'ProdiField').style.display = 'none';
            document.getElementById(prefix + 'KelasField').style.display = 'none';

            // Reset values
            document.getElementById(prefix + 'Jurusan').value = '';
            document.getElementById(prefix + 'Prodi').value = '';
            document.getElementById(prefix + 'Kelas').value = '';

            // Show relevant fields
            if (targetType === 'jurusan') {
                document.getElementById(prefix + 'JurusanField').style.display = 'block';
                document.getElementById(prefix + 'ProdiField').style.display = 'block';
                document.getElementById(prefix + 'KelasField').style.display = 'block';
            } else if (targetType === 'prodi') {
                document.getElementById(prefix + 'ProdiField').style.display = 'block';
                document.getElementById(prefix + 'KelasField').style.display = 'block';
            } else if (targetType === 'kelas') {
                document.getElementById(prefix + 'ProdiField').style.display = 'block';
                document.getElementById(prefix + 'KelasField').style.display = 'block';
            }
        }

        // Handle perubahan jurusan
        function handleJurusanChange(prefix) {
            const jurusanId = document.getElementById(prefix + 'Jurusan').value;
            const prodiSelect = document.getElementById(prefix + 'Prodi');

            // Reset prodi dropdown
            prodiSelect.innerHTML = '<option value="">Semua Prodi</option>';

            if (jurusanId) {
                // Filter prodi berdasarkan jurusan
                const filteredProdi = prodiData.filter(p => p.jurusan_id == jurusanId);
                filteredProdi.forEach(prodi => {
                    const option = document.createElement('option');
                    option.value = prodi.prodi_id;
                    option.textContent = prodi.nama_prodi;
                    prodiSelect.appendChild(option);
                });
            }

            // Reset kelas
            document.getElementById(prefix + 'Kelas').innerHTML = '<option value="">Semua Kelas</option>';
        }

        // Handle perubahan prodi
        async function handleProdiChange(prefix) {
            const prodiId = document.getElementById(prefix + 'Prodi').value;
            const kelasSelect = document.getElementById(prefix + 'Kelas');
            const targetType = document.getElementById(prefix + 'TargetType').value;

            // Reset kelas dropdown
            kelasSelect.innerHTML = '<option value="">Semua Kelas</option>';

            if (prodiId) {
                // Jika target type adalah 'prodi', set jurusan otomatis
                if (targetType === 'prodi' || targetType === 'kelas') {
                    const selectedProdi = prodiData.find(p => p.prodi_id == prodiId);
                    if (selectedProdi) {
                        const jurusanSelect = document.getElementById(prefix + 'Jurusan');
                        if (jurusanSelect) {
                            jurusanSelect.value = selectedProdi.jurusan_id;
                            // Show jurusan field untuk referensi
                            if (targetType === 'prodi' || targetType === 'kelas') {
                                document.getElementById(prefix + 'JurusanField').style.display = 'block';
                                jurusanSelect.disabled = true;
                            }
                        }
                    }
                }

                // Fetch kelas dari database
                try {
                    const formData = new FormData();
                    formData.append('action', 'getKelas');
                    formData.append('prodi_id', prodiId);

                    const response = await fetch(window.location.pathname, {
                        method: 'POST',
                        body: formData
                    });

                    const kelas = await response.json();
                    kelas.forEach(k => {
                        const option = document.createElement('option');
                        option.value = k.kelas;
                        option.textContent = k.kelas;
                        kelasSelect.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error fetching kelas:', error);
                }
            }
        }

        // TAMBAH PENGUMUMAN
        document.getElementById('formTambahPengumuman').addEventListener('submit', async function (e) {
            e.preventDefault();

            if (!this.checkValidity()) {
                this.reportValidity();
                return;
            }

            const btn = document.getElementById('btnTambah');
            const btnText = btn.querySelector('.btn-text');
            const spinner = btn.querySelector('.spinner-border');

            btn.disabled = true;
            btnText.classList.add('d-none');
            spinner.classList.remove('d-none');

            try {
                const formData = new FormData(this);
                const response = await fetch(window.location.pathname, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalTambahPengumuman'));
                    modal.hide();
                    this.reset();
                    showAlert(result.message, 'success');
                    setTimeout(() => window.location.href = window.location.pathname, 1000);
                } else {
                    showAlert(result.message, 'danger');
                }
            } catch (error) {
                showAlert('Terjadi kesalahan: ' + error.message, 'danger');
            } finally {
                btn.disabled = false;
                btnText.classList.remove('d-none');
                spinner.classList.add('d-none');
            }
        });

        // EDIT PENGUMUMAN - Setup button click
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', async function () {
                const pengumumanId = this.getAttribute('data-id');

                try {
                    const formData = new FormData();
                    formData.append('action', 'get');
                    formData.append('pengumuman_id', pengumumanId);

                    const response = await fetch(window.location.pathname, {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    if (data) {
                        document.getElementById('editPengumumanId').value = data.pengumuman_id;
                        document.getElementById('editJudul').value = data.judul;
                        document.getElementById('editIsi').value = data.isi;
                        document.getElementById('editKategori').value = data.kategori_id;
                        document.getElementById('editTargetType').value = data.target_type || 'all';

                        // Trigger target change untuk show fields
                        handleTargetChange('edit');

                        // Set nilai untuk target spesifik
                        if (data.target_jurusan_id) {
                            setTimeout(() => {
                                document.getElementById('editJurusan').value = data.target_jurusan_id;
                                handleJurusanChange('edit');

                                if (data.target_prodi_id) {
                                    setTimeout(() => {
                                        document.getElementById('editProdi').value = data.target_prodi_id;
                                        handleProdiChange('edit');

                                        if (data.target_kelas) {
                                            setTimeout(() => {
                                                document.getElementById('editKelas').value = data.target_kelas;
                                            }, 200);
                                        }
                                    }, 200);
                                }
                            }, 100);
                        } else if (data.target_prodi_id) {
                            setTimeout(() => {
                                document.getElementById('editProdi').value = data.target_prodi_id;
                                handleProdiChange('edit');

                                if (data.target_kelas) {
                                    setTimeout(() => {
                                        document.getElementById('editKelas').value = data.target_kelas;
                                    }, 200);
                                }
                            }, 100);
                        }
                    }
                } catch (error) {
                    showAlert('Gagal memuat data: ' + error.message, 'danger');
                }
            });
        });

        // EDIT PENGUMUMAN - Form submit
        document.getElementById('formEditPengumuman').addEventListener('submit', async function (e) {
            e.preventDefault();

            if (!this.checkValidity()) {
                this.reportValidity();
                return;
            }

            const btn = document.getElementById('btnEdit');
            const btnText = btn.querySelector('.btn-text');
            const spinner = btn.querySelector('.spinner-border');

            btn.disabled = true;
            btnText.classList.add('d-none');
            spinner.classList.remove('d-none');

            try {
                const formData = new FormData(this);
                const response = await fetch(window.location.pathname, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditPengumuman'));
                    modal.hide();
                    showAlert(result.message, 'success');
                    setTimeout(() => window.location.href = window.location.pathname, 1000);
                } else {
                    showAlert(result.message, 'danger');
                }
            } catch (error) {
                showAlert('Terjadi kesalahan: ' + error.message, 'danger');
            } finally {
                btn.disabled = false;
                btnText.classList.remove('d-none');
                spinner.classList.add('d-none');
            }
        });

        // DELETE PENGUMUMAN
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', async function () {
                const pengumumanId = this.getAttribute('data-id');
                const judulPengumuman = this.getAttribute('data-judul');

                if (!confirm(`Apakah Anda yakin ingin menghapus pengumuman "${judulPengumuman}"?`)) {
                    return;
                }

                try {
                    const formData = new FormData();
                    formData.append('action', 'delete');
                    formData.append('pengumuman_id', pengumumanId);

                    const response = await fetch(window.location.pathname, {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        showAlert(result.message, 'success');
                        setTimeout(() => window.location.href = window.location.pathname, 1000);
                    } else {
                        showAlert(result.message, 'danger');
                    }
                } catch (error) {
                    showAlert('Terjadi kesalahan: ' + error.message, 'danger');
                }
            });
        });

        // KIRIM EMAIL - Setup button click
        document.querySelectorAll('.email-btn').forEach(button => {
            button.addEventListener('click', function () {
                const pengumumanId = this.getAttribute('data-id');
                const judul = this.getAttribute('data-judul');
                const target = this.getAttribute('data-target');

                document.getElementById('emailPengumumanId').value = pengumumanId;
                document.getElementById('emailJudul').textContent = judul;
                document.getElementById('emailTarget').textContent = target;
            });
        });

        // KIRIM EMAIL - Setup button click
        document.querySelectorAll('.email-btn').forEach(button => {
            button.addEventListener('click', function () {
                const pengumumanId = this.getAttribute('data-id');
                const judul = this.getAttribute('data-judul');
                const target = this.getAttribute('data-target');

                document.getElementById('emailPengumumanId').value = pengumumanId;
                document.getElementById('emailJudul').textContent = judul;
                document.getElementById('emailTarget').textContent = target;
            });
        });

        // Helper function untuk force cleanup modal
        function forceCleanupModal() {
            // Tutup semua modal Bootstrap
            document.querySelectorAll('.modal').forEach(modalEl => {
                const instance = bootstrap.Modal.getInstance(modalEl);
                if (instance) {
                    instance.hide();
                }
                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
                modalEl.setAttribute('aria-hidden', 'true');
                modalEl.removeAttribute('aria-modal');
                modalEl.removeAttribute('role');
            });

            // Hapus semua backdrop
            document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                backdrop.remove();
            });

            // Reset body - INI YANG PALING PENTING!
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
            document.body.removeAttribute('data-bs-overflow');
            document.body.removeAttribute('data-bs-padding-right');
        }

        // KIRIM EMAIL - Form submit
        document.getElementById('formKirimEmail').addEventListener('submit', async function (e) {
            e.preventDefault();

            const btn = document.getElementById('btnKirimEmail');
            const btnText = btn.querySelector('.btn-text');
            const spinner = btn.querySelector('.spinner-border');

            if (!confirm('Apakah Anda yakin ingin mengirim email pengumuman ini ke semua mahasiswa yang ditargetkan?\n\nProses ini mungkin memerlukan waktu beberapa saat.')) {
                return;
            }

            btn.disabled = true;
            btnText.textContent = 'Mengirim Email...';
            spinner.classList.remove('d-none');

            try {
                const formData = new FormData(this);
                const response = await fetch(window.location.pathname, {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const responseText = await response.text();
                let result;

                try {
                    result = JSON.parse(responseText);
                } catch (parseError) {
                    console.error('Response text:', responseText);
                    throw new Error('Server mengembalikan response yang tidak valid.');
                }

                // Cleanup modal
                forceCleanupModal();

                // Tampilkan alert
                setTimeout(() => {
                    if (result.success) {
                        let message = result.message;
                        if (result.detail) {
                            message += `<br><small class="d-block mt-2">
                        <strong>Detail Pengiriman:</strong><br>
                        📧 Total Penerima: ${result.detail.total}<br>
                        ✅ Berhasil: ${result.detail.success}<br>
                        ${result.detail.failed > 0 ? `❌ Gagal: ${result.detail.failed}` : ''}
                    </small>`;
                        }
                        showAlert(message, 'success');
                    } else {
                        showAlert(result.message || 'Terjadi kesalahan saat mengirim email', 'danger');
                    }
                }, 300);

            } catch (error) {
                console.error('Error detail:', error);
                forceCleanupModal();

                setTimeout(() => {
                    showAlert('Terjadi kesalahan: ' + error.message, 'danger');
                }, 300);

            } finally {
                btn.disabled = false;
                btnText.textContent = 'Kirim Email Sekarang';
                spinner.classList.add('d-none');
            }
        });

        // Reset modal saat ditutup
        document.getElementById('modalTambahPengumuman').addEventListener('hidden.bs.modal', function () {
            const form = document.getElementById('formTambahPengumuman');
            form.reset();
            document.querySelectorAll('.conditional-field').forEach(field => field.style.display = 'none');
        });

        document.getElementById('modalEditPengumuman').addEventListener('hidden.bs.modal', function () {
            document.querySelectorAll('.conditional-field').forEach(field => field.style.display = 'none');
        });

        document.getElementById('modalKirimEmail').addEventListener('hidden.bs.modal', function () {
            const form = document.getElementById('formKirimEmail');
            form.reset();
        });
    </script>
</body>

</html>