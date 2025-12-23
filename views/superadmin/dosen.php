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
if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'superadmin') {
    echo "<script>
        alert('Akses ditolak! Halaman ini hanya untuk superadmin.');
        location.href='/PBL8/views/auth/login.php';
    </script>";
    exit();
}

// Load controller
require_once __DIR__ . '/../../app/controllers/superadmin/dosen_controller.php';
$dosenController = new DosenController();

// Handle AJAX requests
if (isset($_POST['action'])) {
    header('Content-Type: application/json');

    switch ($_POST['action']) {
        case 'create':
            echo json_encode($dosenController->create());
            exit();

        case 'update':
            echo json_encode($dosenController->update());
            exit();

        case 'delete':
            echo json_encode($dosenController->delete());
            exit();

        case 'get':
            $id = intval($_POST['dosen_id']);
            echo json_encode($dosenController->getById($id));
            exit();
    }
}

// Pagination settings
$itemsPerPage = 10;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

// Get all dosen
$allDosen = $dosenController->index();
$totalData = count($allDosen);
$totalPages = ceil($totalData / $itemsPerPage);

// Slice data for current page
$dosenList = array_slice($allDosen, $offset, $itemsPerPage);

// Calculate display range
$startData = $totalData > 0 ? $offset + 1 : 0;
$endData = min($offset + $itemsPerPage, $totalData);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin | Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* ================= DOSEN ================= */
        .table-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-top: 20px;
            margin-bottom: 80px !important;
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
            width: 40%;
        }

        table.table thead th:nth-child(2) {
            width: 20%;
        }

        table.table thead th:nth-child(3) {
            width: 30%;
        }

        table.table thead th:nth-child(4) {
            width: 10%;
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

        /* Nama dosen rata kiri */
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
            margin-bottom: 80px !important;
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

        /* Border input pada modal */
        #modalTambahDosen .modal-content input,
        #modalTambahDosen .modal-content textarea {
            border: 1px solid #b0b0b0 !important;
            color: black !important;
            background-color: white !important;
        }

        #modalTambahDosen .modal-content input:focus,
        #modalTambahDosen .modal-content textarea:focus {
            border: 1px solid #51c8e9 !important;
            background-color: #f2f2f2 !important;
            color: black !important;
            box-shadow: 0 0 0 0.2rem rgba(81, 200, 233, 0.25) !important;
        }

        #modalEditDosen .modal-content input,
        #modalEditDosen .modal-content textarea {
            border: 1px solid #b0b0b0 !important;
            color: black !important;
            background-color: white !important;
        }

        #modalEditDosen .modal-content input:focus,
        #modalEditDosen .modal-content textarea:focus {
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

        /* Container utama */
        main.container {
            margin-bottom: 100px !important;
            padding-bottom: 50px !important;
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

<body class="dosen-page">
    <?php include("header.php"); ?>

    <!-- ================= KONTEN ================= -->
    <main class="container my-4 mb-5 pb-5">
        <div class="mb-3">
            <h4 class="fw-bold mb-1">Dosen</h4>
            <button class="btn btn-secondary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#modalTambahDosen">
                + Tambah Dosen
            </button>
        </div>

        <!-- Alert Messages -->
        <div id="alertContainer"></div>

        <?php if (empty($allDosen)): ?>
            <!-- Empty State -->
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h5>Belum Ada Data Dosen</h5>
                <p>Silakan tambahkan dosen pertama Anda</p>
            </div>
        <?php else: ?>
            <!-- Table with Data -->
            <div class="table-section">
                <div class="table-header">
                    <div class="table-title">Daftar Dosen</div>
                    <div class="entries-info">
                        Menampilkan <?= $startData; ?> - <?= $endData; ?> dari <?= $totalData; ?> dosen
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Dosen</th>
                                <th>NIDN</th>
                                <th>Email</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="dosenTableBody">
                            <?php foreach ($dosenList as $dosen): ?>
                                <tr data-id="<?= $dosen['dosen_id'] ?>">
                                    <!-- Pastikan key 'nama_lengkap' ada (ada di select model) -->
                                    <td class="col-nama"><?= htmlspecialchars($dosen['nama_lengkap']) ?></td>

                                    <!-- Pastikan key 'nidn' ada (ada di select model) -->
                                    <td class="col-nidn"><?= htmlspecialchars($dosen['nidn']) ?></td>

                                    <!-- Pastikan key 'email' ada (ada di select model) -->
                                    <td class="col-email"><?= htmlspecialchars($dosen['email']) ?></td>

                                    <!-- KODE YANG BENAR -->
                                    <td class="col-aksi">
                                        <button class="btn btn-warning btn-sm edit-btn"
                                            data-id="<?= $dosen['dosen_id'] ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditDosen">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>

                                        <button class="btn btn-danger btn-sm delete-btn"
                                            data-id="<?= $dosen['dosen_id'] ?>"
                                            data-nama="<?= htmlspecialchars($dosen['nama_lengkap']) ?>">
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

    <!-- ================= MODAL TAMBAH DOSEN ================= -->
    <div class="modal fade" id="modalTambahDosen" tabindex="-1" aria-labelledby="modalTambahDosenLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title w-100 text-center" id="modalTambahDosenLabel">
                        Penambahan Akun Dosen
                    </h5>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formTambahDosen">
                        <input type="hidden" name="action" value="create">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_lengkap" required
                                placeholder="Masukkan Nama Lengkap">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIDN <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nidn" required placeholder="Masukkan NIDN">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="username" required
                                placeholder="Masukkan Username">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" required
                                placeholder="Masukkan Password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" required placeholder="Masukkan Email">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" name="no_telepon"
                                placeholder="Masukkan No. Telepon">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="2"
                                placeholder="Masukkan Alamat"></textarea>
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

    <!-- ================= MODAL EDIT DOSEN ================= -->
    <div class="modal fade" id="modalEditDosen" tabindex="-1" aria-labelledby="modalEditDosenLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title w-100 text-center" id="modalEditDosenLabel">
                        Edit Data Dosen
                    </h5>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditDosen">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="dosen_id" id="editDosenId">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_lengkap" id="editNama" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIDN <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nidn" id="editNidn" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" id="editEmail" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" name="no_telepon" id="editTelepon">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" id="editAlamat" rows="2"></textarea>
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
        // Show Alert
        function showAlert(message, type = 'success') {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            document.getElementById('alertContainer').innerHTML = alertHtml;

            setTimeout(() => {
                const alert = document.querySelector('.alert');
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }

        // ================= TAMBAH DOSEN =================
        document.getElementById('formTambahDosen').addEventListener('submit', async function(e) {
            e.preventDefault();

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
                    showAlert(result.message, 'success');

                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalTambahDosen'));
                    modal.hide();

                    // Reset form
                    this.reset();

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

        // ================= EDIT DOSEN =================
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', async function() {
                const dosenId = this.getAttribute('data-id');

                try {
                    const formData = new FormData();
                    formData.append('action', 'get');
                    formData.append('dosen_id', dosenId);

                    const response = await fetch(window.location.pathname, {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    if (data) {
                        document.getElementById('editDosenId').value = data.dosen_id;
                        document.getElementById('editNama').value = data.nama_lengkap;
                        document.getElementById('editNidn').value = data.nidn;
                        document.getElementById('editEmail').value = data.email || '';
                        document.getElementById('editTelepon').value = data.no_telepon || '';
                        document.getElementById('editAlamat').value = data.alamat || '';
                    }
                } catch (error) {
                    showAlert('Gagal memuat data: ' + error.message, 'danger');
                }
            });
        });

        document.getElementById('formEditDosen').addEventListener('submit', async function(e) {
            e.preventDefault();

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
                    showAlert(result.message, 'success');

                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditDosen'));
                    modal.hide();

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

        // ================= DELETE DOSEN =================
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', async function() {
                const dosenId = this.getAttribute('data-id');
                const namaDosen = this.getAttribute('data-nama');

                if (!confirm(`Apakah Anda yakin ingin menghapus dosen "${namaDosen}"?`)) {
                    return;
                }

                try {
                    const formData = new FormData();
                    formData.append('action', 'delete');
                    formData.append('dosen_id', dosenId);

                    const response = await fetch(window.location.pathname, {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
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
                }
            });
        });
    </script>

</body>

</html>