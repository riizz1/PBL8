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
        #modalTambahMahasiswa .modal-content textarea,
        #modalEditMahasiswa .modal-content input,
        #modalEditMahasiswa .modal-content textarea {
            border: 1px solid #b0b0b0 !important;
            color: black !important;
            background-color: white !important;
            transition: all 0.3s ease;
        }

        /* Saat fokus tetap abu rokok lebih gelap */
        #modalTambahMahasiswa .modal-content input:focus,
        #modalTambahMahasiswa .modal-content textarea:focus,
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

        /* ================= TOGGLE PASSWORD ================= */
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
            color: #6c757d;
            transition: color 0.3s;
        }

        .password-toggle:hover {
            color: #495057;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .form-control {
            padding-right: 2.5rem !important;
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

        /* Style untuk tombol toggle password */
        #togglePassword {
            border: none;
            background: none;
            padding: 0 15px;
            color: #6c757d;
            z-index: 10;
        }

        #togglePassword:hover {
            color: #2193b0;
        }

        #togglePassword:focus {
            outline: none;
            box-shadow: none;
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
                        <div class="mb-3 form-group">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_lengkap" required placeholder="Masukkan Nama Lengkap">
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">NIM <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nim" id="tambahNim" required placeholder="Masukkan NIM">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="username" id="tambahUsername" required placeholder="Masukkan Username">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="password-wrapper">
                                <input type="password" class="form-control" name="password" id="tambahPassword" required placeholder="Masukkan Password">
                                <button type="button" class="password-toggle" onclick="togglePassword('tambahPassword', this)">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Prodi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="prodi" required placeholder="Masukkan Prodi">
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="tambahEmail" placeholder="Masukkan Email">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3 form-group">
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
                        <div class="mb-3 form-group">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_lengkap" id="editNama" required>
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">NIM <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nim" id="editNim" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Prodi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="prodi" id="editProdi" required>
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="editEmail">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3 form-group">
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
        // Fungsi untuk validasi NIM
        async function validateNim(nim, excludeId = null, feedbackElement, inputElement) {
            if (!nim) {
                return true; // Biarkan HTML5 validation yang handle required
            }

            try {
                const formData = new FormData();
                formData.append('action', 'checkNim');
                formData.append('nim', nim);
                if (excludeId) formData.append('exclude_id', excludeId);

                const response = await fetch(window.location.pathname, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.exists) {
                    feedbackElement.textContent = 'NIM sudah terdaftar';
                    inputElement.classList.add('is-invalid');
                    return false;
                } else {
                    feedbackElement.textContent = '';
                    inputElement.classList.remove('is-invalid');
                    return true;
                }
            } catch (error) {
                console.error('Error validating NIM:', error);
                return false;
            }
        }

        // Fungsi untuk validasi Username
        async function validateUsername(username, excludeId = null, feedbackElement, inputElement) {
            if (!username) {
                return true; // Biarkan HTML5 validation yang handle required
            }

            try {
                const formData = new FormData();
                formData.append('action', 'checkUsername');
                formData.append('username', username);
                if (excludeId) formData.append('exclude_id', excludeId);

                const response = await fetch(window.location.pathname, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.exists) {
                    feedbackElement.textContent = 'Username sudah digunakan';
                    inputElement.classList.add('is-invalid');
                    return false;
                } else {
                    feedbackElement.textContent = '';
                    inputElement.classList.remove('is-invalid');
                    return true;
                }
            } catch (error) {
                console.error('Error validating username:', error);
                return false;
            }
        }

        // Fungsi untuk validasi Email
        async function validateEmail(email, excludeId = null, feedbackElement, inputElement) {
            if (!email) {
                feedbackElement.textContent = '';
                inputElement.classList.remove('is-invalid');
                return true; // Email opsional
            }

            // Validasi format email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                feedbackElement.textContent = 'Format email tidak valid';
                inputElement.classList.add('is-invalid');
                return false;
            }

            try {
                const formData = new FormData();
                formData.append('action', 'checkEmail');
                formData.append('email', email);
                if (excludeId) formData.append('exclude_id', excludeId);

                const response = await fetch(window.location.pathname, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.exists) {
                    feedbackElement.textContent = 'Email sudah digunakan';
                    inputElement.classList.add('is-invalid');
                    return false;
                } else {
                    feedbackElement.textContent = '';
                    inputElement.classList.remove('is-invalid');
                    return true;
                }
            } catch (error) {
                console.error('Error validating email:', error);
                return false;
            }
        }

        // ================= EVENT LISTENERS UNTUK FORM TAMBAH =================
        document.getElementById('tambahNim').addEventListener('blur', async function() {
            const feedbackElement = this.nextElementSibling;
            const isValid = await validateNim(this.value, null, feedbackElement, this);

            if (!isValid) {
                shakeElement(this.parentElement);
            }
        });

        document.getElementById('tambahUsername').addEventListener('blur', async function() {
            const feedbackElement = this.nextElementSibling;
            const isValid = await validateUsername(this.value, null, feedbackElement, this);

            if (!isValid) {
                shakeElement(this.parentElement);
            }
        });

        document.getElementById('tambahEmail').addEventListener('blur', async function() {
            const feedbackElement = this.nextElementSibling;
            const isValid = await validateEmail(this.value, null, feedbackElement, this);

            if (!isValid) {
                shakeElement(this.parentElement);
            }
        });

        // ================= TAMBAH MAHASISWA =================
        document.querySelector('#modalTambahMahasiswa form').addEventListener('submit', async function(e) {
            e.preventDefault();

            let hasError = false;
            const form = this;

            // Validasi duplikasi untuk field yang memerlukan (NIM, Username, Email)
            const nimInput = document.getElementById('tambahNim');
            const usernameInput = document.getElementById('tambahUsername');
            const emailInput = document.getElementById('tambahEmail');

            const nimFeedback = nimInput.nextElementSibling;
            const usernameFeedback = usernameInput.nextElementSibling;
            const emailFeedback = emailInput.nextElementSibling;

            // Cek HTML5 validation dulu
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const isNimValid = await validateNim(nimInput.value, null, nimFeedback, nimInput);
            const isUsernameValid = await validateUsername(usernameInput.value, null, usernameFeedback, usernameInput);
            const isEmailValid = await validateEmail(emailInput.value, null, emailFeedback, emailInput);

            if (!isNimValid) {
                shakeElement(nimInput.parentElement);
                hasError = true;
            }
            if (!isUsernameValid) {
                shakeElement(usernameInput.parentElement);
                hasError = true;
            }
            if (!isEmailValid) {
                shakeElement(emailInput.parentElement);
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
                const response = await fetch(window.location.pathname, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Tutup modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalTambahMahasiswa'));
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

                        // Simpan nilai asli untuk validasi
                        document.getElementById('editNim').setAttribute('data-original', data.nim);
                        document.getElementById('editEmail').setAttribute('data-original', data.email || '');

                        // Reset semua validasi
                        document.querySelectorAll('#modalEditMahasiswa .is-invalid').forEach(el => {
                            el.classList.remove('is-invalid');
                        });
                    }
                } catch (error) {
                    showAlert('Gagal memuat data: ' + error.message, 'danger');
                }
            });
        });

        // ================= EVENT LISTENERS UNTUK FORM EDIT =================
        document.getElementById('editNim').addEventListener('blur', async function() {
            const feedbackElement = this.nextElementSibling;
            const originalValue = this.getAttribute('data-original');
            const excludeId = document.getElementById('editMahasiswaId').value;

            // Jika nilai tidak berubah, tidak perlu validasi
            if (this.value === originalValue) {
                feedbackElement.textContent = '';
                this.classList.remove('is-invalid');
                return;
            }

            const isValid = await validateNim(this.value, excludeId, feedbackElement, this);

            if (!isValid) {
                shakeElement(this.parentElement);
            }
        });

        document.getElementById('editEmail').addEventListener('blur', async function() {
            const feedbackElement = this.nextElementSibling;
            const originalValue = this.getAttribute('data-original');
            const excludeId = document.getElementById('editMahasiswaId').value;

            // Jika nilai tidak berubah, tidak perlu validasi
            if (this.value === originalValue) {
                feedbackElement.textContent = '';
                this.classList.remove('is-invalid');
                return;
            }

            const isValid = await validateEmail(this.value, excludeId, feedbackElement, this);

            if (!isValid) {
                shakeElement(this.parentElement);
            }
        });

        document.querySelector('#modalEditMahasiswa form').addEventListener('submit', async function(e) {
            e.preventDefault();

            let hasError = false;
            const form = this;

            // Cek HTML5 validation dulu
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Validasi duplikasi hanya untuk field yang berubah
            const nimInput = document.getElementById('editNim');
            const emailInput = document.getElementById('editEmail');

            const nimFeedback = nimInput.nextElementSibling;
            const emailFeedback = emailInput.nextElementSibling;

            const originalNim = nimInput.getAttribute('data-original');
            const originalEmail = emailInput.getAttribute('data-original');
            const excludeId = document.getElementById('editMahasiswaId').value;

            // Validasi NIM hanya jika berubah
            if (nimInput.value !== originalNim) {
                const isNimValid = await validateNim(nimInput.value, excludeId, nimFeedback, nimInput);
                if (!isNimValid) {
                    shakeElement(nimInput.parentElement);
                    hasError = true;
                }
            }

            // Validasi Email hanya jika berubah
            if (emailInput.value !== originalEmail) {
                const isEmailValid = await validateEmail(emailInput.value, excludeId, emailFeedback, emailInput);
                if (!isEmailValid) {
                    shakeElement(emailInput.parentElement);
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
                const response = await fetch(window.location.pathname, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Tutup modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditMahasiswa'));
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

        // ================= RESET VALIDATION SAAT MODAL DITUTUP =================
        document.getElementById('modalTambahMahasiswa').addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('formTambahMahasiswa');
            form.reset();
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        });

        document.getElementById('modalEditMahasiswa').addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('formEditMahasiswa');
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        });

        // ================= TOGGLE PASSWORD VISIBILITY =================
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('bi-eye');
                eyeIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('bi-eye-slash');
                eyeIcon.classList.add('bi-eye');
            }
        });
        
        // ================= TOGGLE PASSWORD VISIBILITY =================
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        }
    </script>

</body>

</html>