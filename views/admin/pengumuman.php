<?php
require_once __DIR__ . '/../../app/controllers/admin/pengumuman_controller.php';

 $controller = new PengumumanControllerAdmin();
 $data = $controller->index();

 $pengumuman = $data['pengumuman'];
 $kategori = $data['kategori'];

// Handle AJAX requests
if (isset($_POST['action'])) {
    header('Content-Type: application/json');

    switch ($_POST['action']) {
        case 'create':
            echo json_encode($controller->tambah($_POST));
            exit();

        case 'update':
            echo json_encode($controller->edit($_POST));
            exit();

        case 'delete':
            echo json_encode($controller->hapus($_POST['pengumuman_id']));
            exit();

        case 'get':
            $id = intval($_POST['pengumuman_id']);
            echo json_encode($controller->getById($id));
            exit();
    }
}

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
        /* ================= TABLE SECTION ================= */
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

        /* ================= HEADER TABEL BIRU ================= */
        table.table {
            margin-bottom: 0;
        }

        table.table thead th {
            background-color: #2193b0 !important;
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
            width: 25%;
        }

        table.table thead th:nth-child(2) {
            width: 40%;
        }

        table.table thead th:nth-child(3) {
            width: 20%;
        }

        table.table thead th:nth-child(4) {
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
            background-color: #f0f0f0 !important;
        }

        /* Judul rata kiri */
        table.table tbody td:first-child {
            text-align: left !important;
        }

        /* Isi text wrap */
        td.isi-cell {
            white-space: pre-wrap;
            word-wrap: break-word;
            max-width: 400px;
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
            background: #2193b0;
            color: white;
            border-color: #2193b0;
        }

        .page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .page-btn.active {
            background: #2193b0;
            color: white;
            border-color: #2193b0;
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

        /* Border input pada modal Tambah Pengumuman jadi abu rokok */
        #modalTambahPengumuman .modal-content input,
        #modalTambahPengumuman .modal-content textarea,
        #modalTambahPengumuman .modal-content select,
        #modalEditPengumuman .modal-content input,
        #modalEditPengumuman .modal-content textarea,
        #modalEditPengumuman .modal-content select {
            border: 1px solid #b0b0b0 !important;
            color: black !important;
            background-color: white !important;
            transition: all 0.3s ease;
        }

        /* Saat fokus tetap abu rokok lebih gelap */
        #modalTambahPengumuman .modal-content input:focus,
        #modalTambahPengumuman .modal-content textarea:focus,
        #modalTambahPengumuman .modal-content select:focus,
        #modalEditPengumuman .modal-content input:focus,
        #modalEditPengumuman .modal-content textarea:focus,
        #modalEditPengumuman .modal-content select:focus {
            border: 1px solid #8f8f8f !important;
            background-color: #f2f2f2 !important;
            color: black !important;
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
            padding-right: 2.5rem !important;
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
            <h4 class="fw-bold mb-1">Pengumuman</h4>
            <button class="btn btn-secondary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#modalTambahPengumuman">
                + Tambah Pengumuman
            </button>
        </div>

        <!-- Alert Messages -->
        <div id="alertContainer"></div>

        <?php if (empty($allPengumuman)): ?>
            <!-- Empty State -->
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h5>Belum Ada Pengumuman</h5>
                <p>Silakan tambahkan pengumuman pertama Anda</p>
            </div>
        <?php else: ?>
            <!-- Table with Data -->
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
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="pengumumanTableBody">
                            <?php foreach ($pengumumanList as $p): ?>
                                <tr data-id="<?= $p['pengumuman_id'] ?>">
                                    <td class="col-judul"><?= htmlspecialchars($p['judul']) ?></td>
                                    <td class="col-isi isi-cell"><?= nl2br(htmlspecialchars($p['isi'])) ?></td>
                                    <td class="col-kategori text-center"><?= htmlspecialchars($p['nama_kategori']) ?></td>
                                    <td class="col-aksi text-center">
                                        <button class="btn btn-warning btn-sm edit-btn"
                                            data-id="<?= $p['pengumuman_id'] ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditPengumuman">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm delete-btn"
                                            data-id="<?= $p['pengumuman_id'] ?>"
                                            data-judul="<?= htmlspecialchars($p['judul']) ?>">
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
                                if ($start > 2) echo '<span class="page-dots">...</span>';
                            }

                            for ($i = $start; $i <= $end; $i++) {
                                $active = ($i == $currentPage) ? 'active' : '';
                                echo '<a href="?page=' . $i . '" class="page-btn ' . $active . '">' . $i . '</a>';
                            }

                            if ($end < $totalPages) {
                                if ($end < $totalPages - 1) echo '<span class="page-dots">...</span>';
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

    <!-- ================= MODAL TAMBAH PENGUMUMAN ================= -->
    <div class="modal fade" id="modalTambahPengumuman" tabindex="-1" aria-labelledby="modalTambahPengumumanLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title w-100 text-center" id="modalTambahPengumumanLabel">
                        Penambahan Pengumuman
                    </h5>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formTambahPengumuman">
                        <input type="hidden" name="action" value="create">
                        <div class="mb-3 form-group">
                            <label class="form-label">Judul <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="judul" required placeholder="Masukkan Judul">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori_id" class="form-control" required>
                                <option value="">Pilih kategori</option>
                                <?php foreach ($kategori as $k): ?>
                                    <option value="<?= $k['kategori_id'] ?>">
                                        <?= htmlspecialchars($k['nama_kategori']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Isi Pengumuman <span class="text-danger">*</span></label>
                            <textarea name="isi" class="form-control" rows="4" required placeholder="Masukkan Isi Pengumuman"></textarea>
                            <div class="invalid-feedback"></div>
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

    <!-- ================= MODAL EDIT PENGUMUMAN ================= -->
    <div class="modal fade" id="modalEditPengumuman" tabindex="-1" aria-labelledby="modalEditPengumumanLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title w-100 text-center" id="modalEditPengumumanLabel">
                        Edit Pengumuman
                    </h5>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditPengumuman">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="pengumuman_id" id="editPengumumanId">
                        <div class="mb-3 form-group">
                            <label class="form-label">Judul <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="judul" id="editJudul" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori_id" class="form-control" id="editKategori" required>
                                <option value="">Pilih kategori</option>
                                <?php foreach ($kategori as $k): ?>
                                    <option value="<?= $k['kategori_id'] ?>">
                                        <?= htmlspecialchars($k['nama_kategori']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Isi Pengumuman <span class="text-danger">*</span></label>
                            <textarea name="isi" class="form-control" id="editIsi" rows="4" required></textarea>
                            <div class="invalid-feedback"></div>
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

        // ================= TAMBAH PENGUMUMAN =================
        document.querySelector('#modalTambahPengumuman form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = this;

            // Cek HTML5 validation dulu
            if (!form.checkValidity()) {
                form.reportValidity();
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
                const response = await fetch(window.location.pathname, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Tutup modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalTambahPengumuman'));
                    modal.hide();

                    // Reset form
                    this.reset();
                    // Hapus semua class is-invalid
                    this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

                    // Tampilkan notifikasi sukses di luar modal
                    showAlert(result.message, 'success');

                    // Reload page after 1 second
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

        // ================= EDIT PENGUMUMAN =================
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', async function() {
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

                        // Reset semua validasi
                        document.querySelectorAll('#modalEditPengumuman .is-invalid').forEach(el => {
                            el.classList.remove('is-invalid');
                        });
                    }
                } catch (error) {
                    showAlert('Gagal memuat data: ' + error.message, 'danger');
                }
            });
        });

        document.querySelector('#modalEditPengumuman form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = this;

            // Cek HTML5 validation dulu
            if (!form.checkValidity()) {
                form.reportValidity();
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
                const response = await fetch(window.location.pathname, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Tutup modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditPengumuman'));
                    modal.hide();

                    // Tampilkan notifikasi sukses di luar modal
                    showAlert(result.message, 'success');

                    // Reload page after 1 second
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

        // ================= DELETE PENGUMUMAN =================
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', async function() {
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

                        // Reload page after 1 second to maintain pagination
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
        document.getElementById('modalTambahPengumuman').addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('formTambahPengumuman');
            form.reset();
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        });

        document.getElementById('modalEditPengumuman').addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('formEditPengumuman');
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        });
    </script>
</body>

</html>