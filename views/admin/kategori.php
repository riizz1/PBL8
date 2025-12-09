<?php
require_once realpath(__DIR__ . '/../../config/config.php');
require_once realpath(__DIR__ . '/../../app/models/kategori_model.php');

 $kategori = new KategoriModel($config);
 $dataKategori = $kategori->getAll();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Kategori</title>
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
            background-color: #f0f0f0 !important;
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

        /* ================= MODAL STYLING ================= */
        .modal-content {
            background-color: #0000004f;
            color: white;
            border-radius: 10px;
            padding: 20px;
        }

        .modal-content input,
        .modal-content textarea {
            border: 1px solid #b0b0b0 !important;
            color: black !important;
            background-color: white !important;
        }

        .modal-content input:focus,
        .modal-content textarea:focus {
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

        /* ================= ALERT DI DALAM MODAL ================= */
        .modal-body .alert {
            margin-bottom: 15px;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
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

        <!-- Alert Messages (for page load alerts, e.g., from redirect) -->
        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-<?= $_GET['status'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
                <i class="bi bi-<?= $_GET['status'] === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
                <?= htmlspecialchars($_GET['msg'] ?? 'Operasi selesai'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($dataKategori)): ?>
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
                        Total <?= count($dataKategori); ?> kategori
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
                            <?php $no = 1;
                            foreach ($dataKategori as $row): ?>
                                <tr data-id="<?= $row['kategori_id'] ?>">
                                    <td><?= $no++ . ". " . htmlspecialchars($row['nama_kategori']); ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-sm edit-btn"
                                            onclick='editKategori(<?= json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <a href="../../app/controllers/admin/kategori_controller.php?action=delete&id=<?= $row['kategori_id'] ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin hapus kategori \'<?= htmlspecialchars($row['nama_kategori']) ?>\'?\n\nCatatan: Kategori yang masih digunakan tidak dapat dihapus.')">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <!-- Modal Tambah Kategori -->
    <div class="modal fade" id="modalTambahKategori" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title w-100 text-center">Penambahan Kategori Pengumuman</h5>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Alert akan disisipkan di sini oleh JavaScript -->
                    <form id="formTambahKategori" action="../../app/controllers/admin/kategori_controller.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kategori" class="form-control"
                                placeholder="Masukkan Nama Kategori" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="2"
                                placeholder="Masukkan Deskripsi (Opsional)"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Kategori -->
    <div class="modal fade" id="modalEditKategori" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title w-100 text-center">Edit Kategori</h5>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Alert akan disisipkan di sini oleh JavaScript -->
                    <form id="formEditKategori" action="../../app/controllers/admin/kategori_controller.php" method="POST">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="kategori_id" id="edit_kategori_id">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kategori" id="edit_nama_kategori"
                                class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="edit_deskripsi"
                                class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Simpan Perubahan</button>
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

            const container = document.querySelector('main.container');
            const existingAlert = container.querySelector('.alert');
            if (existingAlert) existingAlert.remove();

            container.insertAdjacentHTML('afterbegin', alertHtml);

            // Auto hide after 5 seconds
            setTimeout(() => {
                const alert = container.querySelector('.alert');
                if (alert) {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 150);
                }
            }, 5000);
        }

        function editKategori(data) {
            document.getElementById('edit_kategori_id').value = data.kategori_id;
            document.getElementById('edit_nama_kategori').value = data.nama_kategori;
            document.getElementById('edit_deskripsi').value = data.deskripsi || '';

            var modal = new bootstrap.Modal(document.getElementById('modalEditKategori'));
            modal.show();
        }

        // ================= TAMBAH KATEGORI =================
        document.querySelector('#modalTambahKategori form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;

            // Show loading
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

            // Hapus alert sebelumnya jika ada
            const existingAlert = this.parentElement.querySelector('.alert');
            if (existingAlert) existingAlert.remove();

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

                    // Tampilkan notifikasi sukses di luar modal
                    showAlert(result.message, 'success');

                    // Reload page setelah 1 detik
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    // Tampilkan error DI DALAM MODAL
                    const alertHtml = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            ${result.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                    
                    // Sisipkan alert di dalam modal
                    this.parentElement.insertAdjacentHTML('afterbegin', alertHtml);
                }
            } catch (error) {
                // Tampilkan error DI DALAM MODAL
                const alertHtml = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Terjadi kesalahan: ${error.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                
                // Sisipkan alert di dalam modal
                this.parentElement.insertAdjacentHTML('afterbegin', alertHtml);
            } finally {
                // Hide loading
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        });

        // ================= EDIT KATEGORI =================
        document.querySelector('#modalEditKategori form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;

            // Show loading
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

            // Hapus alert sebelumnya jika ada
            const existingAlert = this.parentElement.querySelector('.alert');
            if (existingAlert) existingAlert.remove();

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

                    // Tampilkan notifikasi sukses di luar modal
                    showAlert(result.message, 'success');

                    // Reload page setelah 1 detik
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    // Tampilkan error DI DALAM MODAL
                    const alertHtml = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            ${result.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                    
                    // Sisipkan alert di dalam modal
                    this.parentElement.insertAdjacentHTML('afterbegin', alertHtml);
                }
            } catch (error) {
                // Tampilkan error DI DALAM MODAL
                const alertHtml = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Terjadi kesalahan: ${error.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                
                // Sisipkan alert di dalam modal
                this.parentElement.insertAdjacentHTML('afterbegin', alertHtml);
            } finally {
                // Hide loading
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        });

        // ================= DELETE KATEGORI =================
        document.querySelectorAll('a[href*="action=delete"]').forEach(link => {
            link.addEventListener('click', async function(e) {
                e.preventDefault();

                const url = this.getAttribute('href');
                const kategoriNama = this.closest('tr').querySelector('td:first-child').textContent.trim();

                if (!confirm(`Yakin hapus kategori "${kategoriNama}"?\n\nCatatan: Kategori yang masih digunakan tidak dapat dihapus.`)) {
                    return;
                }

                try {
                    const response = await fetch(url, {
                        method: 'GET'
                    });

                    const result = await response.json();

                    if (result.success) {
                        showAlert(result.message, 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showAlert(result.message, 'danger');
                    }
                } catch (error) {
                    showAlert('Terjadi kesalahan: ' + error.message, 'danger');
                }
            });
        });

        // Auto hide alert on page load (for alerts from PHP GET)
        setTimeout(function() {
            const alert = document.querySelector('main.container .alert');
            if (alert) {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 150);
            }
        }, 5000);
    </script>
</body>

</html>