<?php
// TEMPORARY: Disable session check untuk testing
// Uncomment baris di bawah setelah sistem login sudah jalan
/*
session_start();

// Cek login dan role
if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'dosen') {
    header("Location: ../auth/login.php");
    exit();
}
*/

// Load controller
require_once __DIR__ . '/../../app/controllers/admin/mahasiswa_controller.php';
$mahasiswaController = new MahasiswaController();

// Handle AJAX requests
if (isset($_POST['action'])) {
    header('Content-Type: application/json');

    switch ($_POST['action']) {
        case 'create':
            echo json_encode($mahasiswaController->create());
            exit();

        case 'update':
            echo json_encode($mahasiswaController->update());
            exit();

        case 'delete':
            echo json_encode($mahasiswaController->delete());
            exit();

        case 'get':
            $id = intval($_POST['mahasiswa_id']);
            echo json_encode($mahasiswaController->getById($id));
            exit();
    }
}

// Pagination settings
$itemsPerPage = 10;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

// Get all mahasiswa
$allMahasiswa = $mahasiswaController->index();
$totalData = count($allMahasiswa);
$totalPages = ceil($totalData / $itemsPerPage);

// Slice data for current page
$mahasiswaList = array_slice($allMahasiswa, $offset, $itemsPerPage);

// Calculate display range
$startData = $totalData > 0 ? $offset + 1 : 0;
$endData = min($offset + $itemsPerPage, $totalData);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Mahasiswa</title>
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
            width: 50%;
        }

        table.table thead th:nth-child(2) {
            width: 20%;
        }

        table.table thead th:nth-child(3) {
            width: 20%;
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
            background-color: #f0f0f0 !important;
        }

        /* Nama mahasiswa rata kiri */
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
            /* Tambahkan ini */
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

        /* Border input pada modal Tambah Mahasiswa jadi abu rokok */
        #modalTambahMahasiswa .modal-content input,
        #modalTambahMahasiswa .modal-content textarea {
            border: 1px solid #b0b0b0 !important;
            color: black !important;
            background-color: white !important;
        }

        /* Saat fokus tetap abu rokok lebih gelap */
        #modalTambahMahasiswa .modal-content input:focus,
        #modalTambahMahasiswa .modal-content textarea:focus {
            border: 1px solid #8f8f8f !important;
            background-color: #f2f2f2 !important;
            color: black !important;
        }

        #modalEditMahasiswa .modal-content input,
        #modalEditMahasiswa .modal-content textarea {
            border: 1px solid #b0b0b0 !important;
            color: black !important;
            background-color: white !important;
        }

        #modalEditMahasiswa .modal-content input:focus,
        #modalEditMahasiswa .modal-content textarea:focus {
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

<body class="mahasiswa-page">
    <?php include("header.php"); ?>

    <!-- ================= KONTEN ================= -->
    <main class="container my-4">
        <div class="mb-3">
            <h4 class="fw-bold mb-1">Mahasiswa</h4>
            <button class="btn btn-secondary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#modalTambahMahasiswa">
                + Tambah Mahasiswa
            </button>
        </div>

        <!-- Alert Messages -->
        <div id="alertContainer"></div>

        <?php if (empty($allMahasiswa)): ?>
            <!-- Empty State -->
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h5>Belum Ada Data Mahasiswa</h5>
                <p>Silakan tambahkan mahasiswa pertama Anda</p>
            </div>
        <?php else: ?>
            <!-- Table with Data -->
            <div class="table-section">
                <div class="table-header">
                    <div class="table-title">Daftar Mahasiswa</div>
                    <div class="entries-info">
                        Menampilkan <?= $startData; ?> - <?= $endData; ?> dari <?= $totalData; ?> mahasiswa
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Mahasiswa</th>
                                <th>NIM</th>
                                <th>Prodi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="mahasiswaTableBody">
                            <?php foreach ($mahasiswaList as $mahasiswa): ?>
                                <tr data-id="<?= $mahasiswa['mahasiswa_id'] ?>">
                                    <td class="col-nama"><?= htmlspecialchars($mahasiswa['nama_lengkap']) ?></td>
                                    <td class="col-nim"><?= htmlspecialchars($mahasiswa['nim']) ?></td>
                                    <td class="col-prodi"><?= htmlspecialchars($mahasiswa['prodi']) ?></td>
                                    <td class="col-aksi">
                                        <button class="btn btn-warning btn-sm edit-btn"
                                            data-id="<?= $mahasiswa['mahasiswa_id'] ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditMahasiswa">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm delete-btn"
                                            data-id="<?= $mahasiswa['mahasiswa_id'] ?>"
                                            data-nama="<?= htmlspecialchars($mahasiswa['nama_lengkap']) ?>">
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

    <!-- ================= MODAL TAMBAH MAHASISWA ================= -->
    <div class="modal fade" id="modalTambahMahasiswa" tabindex="-1" aria-labelledby="modalTambahMahasiswaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title w-100 text-center" id="modalTambahMahasiswaLabel">
                        Penambahan Akun Mahasiswa
                    </h5>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formTambahMahasiswa">
                        <input type="hidden" name="action" value="create">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_lengkap" required placeholder="Masukkan Nama Lengkap">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIM <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nim" required placeholder="Masukkan NIM">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="username" required placeholder="Masukkan Username">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" required placeholder="Masukkan Password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Prodi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="prodi" required placeholder="Masukkan Prodi">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Masukkan Email">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="2" placeholder="Masukkan Alamat"></textarea>
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

    <!-- ================= MODAL EDIT MAHASISWA ================= -->
    <div class="modal fade" id="modalEditMahasiswa" tabindex="-1" aria-labelledby="modalEditMahasiswaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title w-100 text-center" id="modalEditMahasiswaLabel">
                        Edit Data Mahasiswa
                    </h5>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditMahasiswa">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="mahasiswa_id" id="editMahasiswaId">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_lengkap" id="editNama" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIM <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nim" id="editNim" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Prodi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="prodi" id="editProdi" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="editEmail">
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

            // Auto hide after 5 seconds
            setTimeout(() => {
                const alert = document.querySelector('.alert');
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }

        // ================= TAMBAH MAHASISWA =================
        document.getElementById('formTambahMahasiswa').addEventListener('submit', async function(e) {
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
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalTambahMahasiswa'));
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

        // ================= EDIT MAHASISWA =================
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', async function() {
                const mahasiswaId = this.getAttribute('data-id');

                try {
                    const formData = new FormData();
                    formData.append('action', 'get');
                    formData.append('mahasiswa_id', mahasiswaId);

                    const response = await fetch(window.location.pathname, {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    if (data) {
                        document.getElementById('editMahasiswaId').value = data.mahasiswa_id;
                        document.getElementById('editNama').value = data.nama_lengkap;
                        document.getElementById('editNim').value = data.nim;
                        document.getElementById('editProdi').value = data.prodi;
                        document.getElementById('editEmail').value = data.email || '';
                        document.getElementById('editAlamat').value = data.alamat || '';
                    }
                } catch (error) {
                    showAlert('Gagal memuat data: ' + error.message, 'danger');
                }
            });
        });

        document.getElementById('formEditMahasiswa').addEventListener('submit', async function(e) {
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
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditMahasiswa'));
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

        // ================= DELETE MAHASISWA =================
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', async function() {
                const mahasiswaId = this.getAttribute('data-id');
                const namaMahasiswa = this.getAttribute('data-nama');

                if (!confirm(`Apakah Anda yakin ingin menghapus mahasiswa "${namaMahasiswa}"?`)) {
                    return;
                }

                try {
                    const formData = new FormData();
                    formData.append('action', 'delete');
                    formData.append('mahasiswa_id', mahasiswaId);

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
    </script>

</body>

</html>