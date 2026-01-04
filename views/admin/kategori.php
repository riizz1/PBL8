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

require_once realpath(__DIR__ . '/../../config/config.php');
require_once realpath(__DIR__ . '/../../app/models/kategori_model.php');

$kategori = new KategoriModel($config);

// Pagination settings
$itemsPerPage = 10;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

// Get all kategori
$allKategori = $kategori->getAll();
$totalData = count($allKategori);
$totalPages = ceil($totalData / $itemsPerPage);

// Slice data for current page
$dataKategori = array_slice($allKategori, $offset, $itemsPerPage);

// Calculate display range
$startData = $totalData > 0 ? $offset + 1 : 0;
$endData = min($offset + $itemsPerPage, $totalData);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="/PBL8/public/assets/img/hat.svg">

    <style>
        /* ================= KATEGORI ================= */
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

        table.table thead th:nth-child(1) {
            width: 85%;
        }

        table.table thead th:nth-child(2) {
            width: 15%;
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

        /* Zebra stripe baris tabel */
        table.table tbody tr:nth-child(odd) td {
            background-color: #ffffff !important;
        }

        table.table tbody tr:nth-child(even) td {
            background-color: #f9f9f9 !important;
        }

        table.table tbody tr:hover td {
            background-color: #e8f8fd !important;
        }

        /* Nama kategori rata kiri */
        table.table tbody td:first-child {
            text-align: left !important;
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
            color: white;
            border-color: #51c8e9;
        }

        .page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .page-btn.active {
            background: #51c8e9;
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

        /* Border input pada modal jadi abu rokok */
        #modalTambahKategori .modal-content input,
        #modalTambahKategori .modal-content textarea,
        #modalEditKategori .modal-content input,
        #modalEditKategori .modal-content textarea {
            border: 1px solid #b0b0b0 !important;
            color: black !important;
            background-color: white !important;
            transition: all 0.3s ease;
        }

        /* Saat fokus */
        #modalTambahKategori .modal-content input:focus,
        #modalTambahKategori .modal-content textarea:focus,
        #modalEditKategori .modal-content input:focus,
        #modalEditKategori .modal-content textarea:focus {
            border: 1px solid #51c8e9 !important;
            background-color: #f2f2f2 !important;
            color: black !important;
            box-shadow: 0 0 0 0.2rem rgba(81, 200, 233, 0.25) !important;
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

        /* Loading Spinner */
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        /* ================= VALIDATION STYLING ================= */
        .form-control {
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group {
            position: relative;
        }

        .is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right calc(0.375em + 0.1875rem) center !important;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem) !important;
            padding-right: calc(1.5em + 0.75rem) !important;
        }

        textarea.is-invalid {
            background-position: top calc(0.375em + 0.1875rem) right calc(0.375em + 0.1875rem) !important;
        }

        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }

        .is-invalid~.invalid-feedback {
            display: block;
        }

        /* ================= SHAKE ANIMATION ================= */
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

        /* Responsive */
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
            <h4 class="fw-bold mb-1">Kategori</h4>
            <button class="btn btn-secondary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
                + Tambah Kategori
            </button>
        </div>

        <!-- Alert Messages -->
        <div id="alertContainer"></div>

        <?php if (empty($allKategori)): ?>
            <!-- Empty State -->
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h5>Belum Ada Kategori</h5>
                <p>Silakan tambahkan kategori pertama Anda</p>
            </div>
        <?php else: ?>
            <!-- Table with Data -->
            <div class="table-section">
                <div class="table-header">
                    <div class="table-title">Daftar Kategori</div>
                    <div class="entries-info">
                        Menampilkan <?= $startData; ?> - <?= $endData; ?> dari <?= $totalData; ?> kategori
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = $startData;
                            foreach ($dataKategori as $row): ?>
                                <tr data-id="<?= $row['kategori_id'] ?>">
                                    <td><?= $no++ . ". " . htmlspecialchars($row['nama_kategori']); ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-sm edit-btn"
                                            onclick='editKategori(<?= json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $row['kategori_id'] ?>"
                                            data-nama="<?= htmlspecialchars($row['nama_kategori']) ?>">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                <?php if ($totalData > 0): ?>
                    <div class="pagination">
                        <div class="page-info">
                            Halaman <?= $currentPage; ?> dari <?= $totalPages; ?>
                        </div>

                        <div class="page-buttons">
                            <!-- Previous Button -->
                            <?php if ($currentPage > 1): ?>
                                <a href="?page=<?= $currentPage - 1; ?>" class="page-btn">
                                    ← Sebelumnya
                                </a>
                            <?php else: ?>
                                <button class="page-btn" disabled>← Sebelumnya</button>
                            <?php endif; ?>

                            <!-- Page Numbers -->
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

                            <!-- Next Button -->
                            <?php if ($currentPage < $totalPages): ?>
                                <a href="?page=<?= $currentPage + 1; ?>" class="page-btn">
                                    Selanjutnya →
                                </a>
                            <?php else: ?>
                                <button class="page-btn" disabled>Selanjutnya →</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- Modal Tambah Kategori -->
    <div class="modal fade" id="modalTambahKategori" tabindex="-1" aria-labelledby="modalTambahKategoriLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title w-100 text-center" id="modalTambahKategoriLabel">
                        Penambahan Kategori Pengumuman
                    </h5>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formTambahKategori" action="../../app/controllers/admin/kategori_controller.php"
                        method="POST">
                        <div class="mb-3 form-group">
                            <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kategori" id="tambahNamaKategori" class="form-control"
                                placeholder="Masukkan Nama Kategori" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="2"
                                placeholder="Masukkan Deskripsi (Opsional)"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" id="btnTambah">
                            <span class="btn-text">Simpan</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Kategori -->
    <div class="modal fade" id="modalEditKategori" tabindex="-1" aria-labelledby="modalEditKategoriLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title w-100 text-center" id="modalEditKategoriLabel">
                        Edit Kategori
                    </h5>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditKategori" action="../../app/controllers/admin/kategori_controller.php"
                        method="POST">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="kategori_id" id="edit_kategori_id">
                        <div class="mb-3 form-group">
                            <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kategori" id="edit_nama_kategori" class="form-control"
                                required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100" id="btnEdit">
                            <span class="btn-text">Simpan Perubahan</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include("footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show Alert di luar modal (untuk notifikasi umum)
        function showAlert(message, type = 'success') {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            document.getElementById('alertContainer').innerHTML = alertHtml;

            // Auto hide after 5 seconds
            setTimeout(() => {
                const alert = document.querySelector('.alert');
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }

        // Fungsi untuk menambahkan animasi shake
        function shakeElement(element) {
            element.classList.add('shake');
            setTimeout(() => {
                element.classList.remove('shake');
            }, 500);
        }

        // ================= VALIDASI REAL-TIME =================
        // Fungsi untuk validasi Nama Kategori (cek duplikasi)
        async function validateNamaKategori(namaKategori, excludeId = null, feedbackElement, inputElement) {
            if (!namaKategori) {
                return true; // Biarkan HTML5 validation yang handle required
            }

            try {
                const formData = new FormData();
                formData.append('action', 'checkNamaKategori');
                formData.append('nama_kategori', namaKategori);
                if (excludeId) formData.append('exclude_id', excludeId);

                const response = await fetch('../../app/controllers/admin/kategori_controller.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.exists) {
                    feedbackElement.textContent = 'Nama kategori sudah ada';
                    inputElement.classList.add('is-invalid');
                    return false;
                } else {
                    feedbackElement.textContent = '';
                    inputElement.classList.remove('is-invalid');
                    return true;
                }
            } catch (error) {
                console.error('Error validating nama kategori:', error);
                return true; // Jika error API, biarkan submit tetap jalan
            }
        }

        // ================= EVENT LISTENERS UNTUK FORM TAMBAH =================
        document.getElementById('tambahNamaKategori').addEventListener('blur', async function () {
            const feedbackElement = this.nextElementSibling;
            const isValid = await validateNamaKategori(this.value, null, feedbackElement, this);

            if (!isValid) {
                shakeElement(this.parentElement);
            }
        });

        function editKategori(data) {
            document.getElementById('edit_kategori_id').value = data.kategori_id;
            document.getElementById('edit_nama_kategori').value = data.nama_kategori;
            document.getElementById('edit_deskripsi').value = data.deskripsi || '';

            // Simpan nilai asli untuk validasi
            document.getElementById('edit_nama_kategori').setAttribute('data-original', data.nama_kategori);

            // Reset semua validasi
            document.querySelectorAll('#modalEditKategori .is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });

            var modal = new bootstrap.Modal(document.getElementById('modalEditKategori'));
            modal.show();
        }

        // ================= EVENT LISTENERS UNTUK FORM EDIT =================
        document.getElementById('edit_nama_kategori').addEventListener('blur', async function () {
            const feedbackElement = this.nextElementSibling;
            const originalValue = this.getAttribute('data-original');
            const excludeId = document.getElementById('edit_kategori_id').value;

            // Jika nilai tidak berubah, tidak perlu validasi
            if (this.value === originalValue) {
                feedbackElement.textContent = '';
                this.classList.remove('is-invalid');
                return;
            }

            const isValid = await validateNamaKategori(this.value, excludeId, feedbackElement, this);

            if (!isValid) {
                shakeElement(this.parentElement);
            }
        });

        // ================= TAMBAH KATEGORI =================
        document.querySelector('#modalTambahKategori form').addEventListener('submit', async function (e) {
            e.preventDefault();

            let hasError = false;
            const form = this;

            // Validasi duplikasi untuk nama kategori
            const namaKategoriInput = document.getElementById('tambahNamaKategori');
            const namaKategoriFeedback = namaKategoriInput.nextElementSibling;

            // Cek HTML5 validation dulu
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const isNamaKategoriValid = await validateNamaKategori(namaKategoriInput.value, null, namaKategoriFeedback, namaKategoriInput);

            if (!isNamaKategoriValid) {
                shakeElement(namaKategoriInput.parentElement);
                hasError = true;
            }

            if (hasError) {
                return;
            }

            const btn = document.getElementById('btnTambah');
            const btnText = btn.querySelector('.btn-text');
            const spinner = btn.querySelector('.spinner-border');

            // Show loading
            btn.disabled = true;
            btnText.classList.add('d-none');
            spinner.classList.remove('d-none');

            try {
                const formData = new FormData(this);
                const response = await fetch('../../app/controllers/admin/kategori_controller.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Tutup modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalTambahKategori'));
                    modal.hide();

                    // Reset form
                    this.reset();
                    // Hapus semua class is-invalid
                    this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

                    // Tampilkan notifikasi sukses
                    showAlert(result.message, 'success');

                    // Reload page setelah 1 detik
                    setTimeout(() => {
                        window.location.href = window.location.pathname;
                    }, 1000);
                } else {
                    showAlert(result.message, 'danger');
                }
            } catch (error) {
                showAlert('Terjadi kesalahan: ' + error.message, 'danger');
            } finally {
                // Hide loading
                btn.disabled = false;
                btnText.classList.remove('d-none');
                spinner.classList.add('d-none');
            }
        });

        // ================= EDIT KATEGORI =================
        document.querySelector('#modalEditKategori form').addEventListener('submit', async function (e) {
            e.preventDefault();

            let hasError = false;
            const form = this;

            // Cek HTML5 validation dulu
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Validasi duplikasi hanya untuk field yang berubah
            const namaKategoriInput = document.getElementById('edit_nama_kategori');
            const namaKategoriFeedback = namaKategoriInput.nextElementSibling;

            const originalNamaKategori = namaKategoriInput.getAttribute('data-original');
            const excludeId = document.getElementById('edit_kategori_id').value;

            // Validasi Nama Kategori hanya jika berubah
            if (namaKategoriInput.value !== originalNamaKategori) {
                const isNamaKategoriValid = await validateNamaKategori(namaKategoriInput.value, excludeId, namaKategoriFeedback, namaKategoriInput);
                if (!isNamaKategoriValid) {
                    shakeElement(namaKategoriInput.parentElement);
                    hasError = true;
                }
            }

            if (hasError) {
                return;
            }

            const btn = document.getElementById('btnEdit');
            const btnText = btn.querySelector('.btn-text');
            const spinner = btn.querySelector('.spinner-border');

            // Show loading
            btn.disabled = true;
            btnText.classList.add('d-none');
            spinner.classList.remove('d-none');

            try {
                const formData = new FormData(this);
                const response = await fetch('../../app/controllers/admin/kategori_controller.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Tutup modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditKategori'));
                    modal.hide();

                    // Tampilkan notifikasi sukses
                    showAlert(result.message, 'success');

                    // Reload page setelah 1 detik
                    setTimeout(() => {
                        window.location.href = window.location.pathname;
                    }, 1000);
                } else {
                    showAlert(result.message, 'danger');
                }
            } catch (error) {
                showAlert('Terjadi kesalahan: ' + error.message, 'danger');
            } finally {
                // Hide loading
                btn.disabled = false;
                btnText.classList.remove('d-none');
                spinner.classList.add('d-none');
            }
        });

        // ================= DELETE KATEGORI =================
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', async function () {
                const kategoriId = this.getAttribute('data-id');
                const kategoriNama = this.getAttribute('data-nama');

                if (!confirm(`Yakin hapus kategori "${kategoriNama}"?\n\nCatatan: Kategori yang masih digunakan tidak dapat dihapus.`)) {
                    return;
                }

                try {
                    const url = `../../app/controllers/admin/kategori_controller.php?action=delete&id=${kategoriId}`;
                    const response = await fetch(url, {
                        method: 'GET'
                    });

                    const result = await response.json();

                    if (result.success) {
                        showAlert(result.message, 'success');
                        setTimeout(() => {
                            window.location.href = window.location.pathname;
                        }, 1000);
                    } else {
                        showAlert(result.message, 'danger');
                    }
                } catch (error) {
                    showAlert('Terjadi kesalahan: ' + error.message, 'danger');
                }
            });
        });

        // ================= RESET VALIDATION SAAT MODAL DITUTUP =================
        document.getElementById('modalTambahKategori').addEventListener('hidden.bs.modal', function () {
            const form = document.getElementById('formTambahKategori');
            form.reset();
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        });

        document.getElementById('modalEditKategori').addEventListener('hidden.bs.modal', function () {
            const form = document.getElementById('formEditKategori');
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        });
    </script>
</body>

</html>