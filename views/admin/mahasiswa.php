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

// Proteksi role - hanya admin/dosen yang bisa akses
if (!isset($_SESSION['role_name']) || ($_SESSION['role_name'] !== 'admin' && $_SESSION['role_name'] !== 'dosen')) {
    echo "<script>
        alert('Akses ditolak! Halaman ini hanya untuk Admin/Dosen.');
        location.href='/PBL8/views/auth/login.php';
    </script>";
    exit();
}

// Load controller ADMIN
require_once __DIR__ . '/../../app/controllers/admin/mahasiswa_controller.php';
 $mahasiswaController = new MahasiswaControllerAdmin();

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

        case 'checkNim':
            $nim = $_POST['nim'];
            $excludeId = isset($_POST['exclude_id']) ? intval($_POST['exclude_id']) : null;
            echo json_encode(['exists' => $mahasiswaController->checkNimExists($nim, $excludeId)]);
            exit();

        case 'checkUsername':
            $username = $_POST['username'];
            $excludeId = isset($_POST['exclude_id']) ? intval($_POST['exclude_id']) : null;
            echo json_encode(['exists' => $mahasiswaController->checkUsernameExists($username, $excludeId)]);
            exit();

        case 'checkEmail':
            $email = $_POST['email'];
            $excludeId = isset($_POST['exclude_id']) ? intval($_POST['exclude_id']) : null;
            echo json_encode(['exists' => $mahasiswaController->checkEmailExists($email, $excludeId)]);
            exit();
    }
}

// Pagination settings
 $itemsPerPage = 10;
 $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
 $offset = ($currentPage - 1) * $itemsPerPage;

// Get all mahasiswa (Model getAll sudah difilter otomatis oleh controller admin)
 $allMahasiswa = $mahasiswaController->index();
 $totalData = count($allMahasiswa);
 $totalPages = ceil($totalData / $itemsPerPage);

// Slice data for current page
 $mahasiswaList = array_slice($allMahasiswa, $offset, $itemsPerPage);

// Calculate display range
 $startData = $totalData > 0 ? $offset + 1 : 0;
 $endData = min($offset + $itemsPerPage, $totalData);

// Get jurusan dan prodi untuk dropdown
 $jurusanList = $mahasiswaController->getAllJurusan();
 $prodiList = $mahasiswaController->getAllProdi();
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
        /* ================= MAHASISWA ================= */
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
            width: 100%;
            border-collapse: collapse;
        }

        table.table thead th {
            background-color: #51c8e9 !important;
            color: white !important;
            text-align: center !important;
            padding: 15px 10px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        table.table thead th:first-child {
            text-align: center !important; /* Nomor rata tengah */
        }

        /* ================= TBODY STYLING ================= */
        table.table tbody td {
            padding: 15px 10px;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
            font-size: 14px;
            vertical-align: middle;
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
        }

        table.table tbody td:first-child {
            text-align: center !important; /* Nomor rata tengah */
            font-weight: bold;
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
    <main class="container my-4 mb-5 pb-5">
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
                                <th style="width: 5%;">No</th>
                                <th style="width: 25%;">Nama Mahasiswa</th>
                                <th style="width: 12%;">NIM</th>
                                <th style="width: 15%;">Jurusan</th>
                                <th style="width: 18%;">Prodi</th>
                                <th style="width: 12%;">Kelas</th>
                                <th style="width: 13%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="mahasiswaTableBody">
                            <?php 
                            // Logika Nomor: Offset + Index
                            foreach ($mahasiswaList as $index => $mahasiswa): 
                                $nomor = $offset + $index + 1;
                            ?>
                                <tr data-id="<?= $mahasiswa['mahasiswa_id'] ?>">
                                    <td><?= $nomor ?></td>
                                    <td class="col-nama"><?= htmlspecialchars($mahasiswa['nama_lengkap']) ?></td>
                                    <td class="col-nim"><?= htmlspecialchars($mahasiswa['nim']) ?></td>
                                    <td class="col-jurusan"><?= htmlspecialchars($mahasiswa['nama_jurusan']) ?></td>
                                    <td class="col-prodi"><?= htmlspecialchars($mahasiswa['nama_prodi']) ?></td>
                                    <td class="col-kelas"><?= htmlspecialchars($mahasiswa['kelas']) ?></td>
                                    <td class="col-aksi">
                                        <button class="btn btn-warning btn-sm edit-btn"
                                            data-id="<?= $mahasiswa['mahasiswa_id'] ?>" data-bs-toggle="modal"
                                            data-bs-target="#modalEditMahasiswa"><i class="bi bi-pencil-fill"></i></button>
                                        <button class="btn btn-danger btn-sm delete-btn"
                                            data-id="<?= $mahasiswa['mahasiswa_id'] ?>"
                                            data-nama="<?= htmlspecialchars($mahasiswa['nama_lengkap']) ?>"><i
                                                class="bi bi-trash-fill"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
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

    <!-- MODAL TAMBAH MAHASISWA -->
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
                        <div class="mb-3"><label class="form-label">Nama Lengkap <span class="text-danger">*</span></label><input type="text" name="nama_lengkap" class="form-control" required placeholder="Masukkan Nama Lengkap"></div>
                        <div class="mb-3"><label class="form-label">NIM <span class="text-danger">*</span></label><input type="text" name="nim" id="tambahNim" class="form-control" required placeholder="Masukkan NIM"></div>
                        <div class="mb-3"><label class="form-label">Username <span class="text-danger">*</span></label><input type="text" name="username" id="tambahUsername" class="form-control" required placeholder="Masukkan Username"></div>
                        <div class="mb-3"><label class="form-label">Password <span class="text-danger">*</span></label><input type="password" name="password" class="form-control" required placeholder="Masukkan Password"></div>
                        <!-- Dropdown Jurusan & Prodi -->
                        <div class="mb-3"><label class="form-label">Jurusan <span class="text-danger">*</span></label><select name="jurusan_id" id="tambahJurusan" class="form-select" required onchange="filterProdi('tambah')"><option value="">Pilih Jurusan</option><?php foreach ($jurusanList as $j): ?><option value="<?= $j['jurusan_id'] ?>"><?= htmlspecialchars($j['nama_jurusan']) ?></option><?php endforeach; ?></select></div>
                        <div class="mb-3"><label class="form-label">Prodi <span class="text-danger">*</span></label><select name="prodi_id" id="tambahProdi" class="form-select" required><option value="">Pilih Prodi</option></select></div>
                        <div class="mb-3"><label class="form-label">Kelas <span class="text-danger">*</span></label><input type="text" name="kelas" class="form-control" required placeholder="Contoh: IF1A-Pagi"></div>
                        <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" id="tambahEmail" class="form-control" placeholder="Masukkan Email"></div>
                        <div class="mb-3"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control" rows="2" placeholder="Masukkan Alamat"></textarea></div>
                        <button type="submit" class="btn btn-primary w-100" id="btnTambah"><span class="btn-text">Simpan</span><span class="spinner-border spinner-border-sm d-none"></span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT MAHASISWA -->
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
                        <div class="mb-3"><label class="form-label">Nama Lengkap <span class="text-danger">*</span></label><input type="text" name="nama_lengkap" id="editNama" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">NIM <span class="text-danger">*</span></label><input type="text" name="nim" id="editNim" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Username <span class="text-danger">*</span></label><input type="text" name="username" id="editUsername" class="form-control" required></div>
                        <div class="mb-3">
                            <label class="form-label">Password Baru <span class="text-muted">(Kosongkan jika tidak ingin mengubah)</span></label>
                            <div class="input-group">
                                <input type="password" name="password" id="editPassword" class="form-control" placeholder="Masukkan password baru">
                                <button class="btn btn-outline-secondary" type="button" id="toggleEditPassword"><i class="bi bi-eye" id="editPasswordIcon"></i></button>
                            </div>
                        </div>
                        <!-- Dropdown Jurusan & Prodi -->
                        <div class="mb-3"><label class="form-label">Jurusan <span class="text-danger">*</span></label><select name="jurusan_id" id="editJurusan" class="form-select" required onchange="filterProdi('edit')"><option value="">Pilih Jurusan</option><?php foreach ($jurusanList as $j): ?><option value="<?= $j['jurusan_id'] ?>"><?= htmlspecialchars($j['nama_jurusan']) ?></option><?php endforeach; ?></select></div>
                        <div class="mb-3"><label class="form-label">Prodi <span class="text-danger">*</span></label><select name="prodi_id" id="editProdi" class="form-select" required><option value="">Pilih Prodi</option></select></div>
                        <div class="mb-3"><label class="form-label">Kelas <span class="text-danger">*</span></label><input type="text" name="kelas" id="editKelas" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" id="editEmail" class="form-control"></div>
                        <div class="mb-3"><label class="form-label">Alamat</label><textarea name="alamat" id="editAlamat" class="form-control" rows="2"></textarea></div>
                        <button type="submit" class="btn btn-success w-100" id="btnEdit"><span class="btn-text">Simpan Perubahan</span><span class="spinner-border spinner-border-sm d-none"></span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include("footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Data prodi untuk Dropdown Dinamis
        const prodiData = <?= json_encode($prodiList) ?>;

        function filterProdi(type) {
            const jurusanId = document.getElementById(type + 'Jurusan').value;
            const prodiSelect = document.getElementById(type + 'Prodi');
            prodiSelect.innerHTML = '<option value="">Pilih Prodi</option>';
            if (jurusanId) {
                const filteredProdi = prodiData.filter(p => p.jurusan_id == jurusanId);
                filteredProdi.forEach(p => {
                    const option = document.createElement('option');
                    option.value = p.prodi_id;
                    option.textContent = p.nama_prodi;
                    prodiSelect.appendChild(option);
                });
            }
        }

        // Show Alert
        function showAlert(message, type = 'success') {
            const a = document.getElementById('alertContainer');
            a.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert"><i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
            setTimeout(() => {
                const e = document.querySelector('.alert');
                if (e) e.remove();
            }, 5000);
        }

        // ================= TAMBAH MAHASISWA =================
        document.getElementById('formTambahMahasiswa').addEventListener('submit', async function(e) {
            e.preventDefault();
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
                    showAlert(result.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('modalTambahMahasiswa')).hide();
                    this.reset();
                    setTimeout(() => { window.location.href = window.location.pathname; }, 1000);
                } else {
                    showAlert(result.message, 'danger');
                }
            } catch (err) {
                showAlert('Terjadi kesalahan: ' + err.message, 'danger');
            } finally {
                btn.disabled = false;
                btnText.classList.remove('d-none');
                spinner.classList.add('d-none');
            }
        });

        // ================= EDIT MAHASISWA =================
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', async function() {
                const id = this.getAttribute('data-id');
                try {
                    const formData = new FormData();
                    formData.append('action', 'get');
                    formData.append('mahasiswa_id', id);
                    const response = await fetch(window.location.pathname, {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();

                    if (data) {
                        document.getElementById('editMahasiswaId').value = data.mahasiswa_id;
                        document.getElementById('editNama').value = data.nama_lengkap;
                        document.getElementById('editNim').value = data.nim;
                        document.getElementById('editUsername').value = data.username;
                        document.getElementById('editPassword').value = '';
                        document.getElementById('editKelas').value = data.kelas;
                        document.getElementById('editEmail').value = data.email || '';
                        document.getElementById('editAlamat').value = data.alamat || '';
                        
                        // Set jurusan dulu, baru update prodi
                        document.getElementById('editJurusan').value = data.jurusan_id || '';
                        filterProdi('edit');
                        setTimeout(() => {
                            document.getElementById('editProdi').value = data.prodi_id || '';
                        }, 50);
                    }
                } catch (err) {
                    showAlert('Gagal memuat data', 'danger');
                }
            });
        });

        document.getElementById('formEditMahasiswa').addEventListener('submit', async function(e) {
            e.preventDefault();
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
                    showAlert(result.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('modalEditMahasiswa')).hide();
                    setTimeout(() => { window.location.href = window.location.pathname; }, 1000);
                } else {
                    showAlert(result.message, 'danger');
                }
            } catch (err) {
                showAlert('Terjadi kesalahan: ' + err.message, 'danger');
            } finally {
                btn.disabled = false;
                btnText.classList.remove('d-none');
                spinner.classList.add('d-none');
            }
        });

        // ================= DELETE MAHASISWA =================
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', async function() {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                if (confirm(`Apakah Anda yakin ingin menghapus mahasiswa "${nama}"?`)) {
                    try {
                        const formData = new FormData();
                        formData.append('action', 'delete');
                        formData.append('mahasiswa_id', id);
                        const response = await fetch(window.location.pathname, {
                            method: 'POST',
                            body: formData
                        });
                        const result = await response.json();

                        if (result.success) {
                            showAlert(result.message, 'success');
                            setTimeout(() => { window.location.href = window.location.pathname; }, 1000);
                        } else {
                            showAlert(result.message, 'danger');
                        }
                    } catch (err) {
                        showAlert('Terjadi kesalahan', 'danger');
                    }
                }
            });
        });

        // ================= TOGGLE PASSWORD VISIBILITY =================
        document.getElementById('toggleEditPassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('editPassword');
            const passwordIcon = document.getElementById('editPasswordIcon');

            if (passwordInput.type === 'text') {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('bi-eye-slash');
                passwordIcon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('bi-eye');
                passwordIcon.classList.add('bi-eye-slash');
            }
        });
    </script>

</body>
</html>