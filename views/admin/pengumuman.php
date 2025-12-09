<?php
require_once __DIR__ . '/../../app/controllers/admin/pengumuman_controller.php';

$controller = new PengumumanControllerAdmin();
$data = $controller->index();

$pengumuman = $data['pengumuman'];
$kategori = $data['kategori'];

/* ==========================
   PROSES TAMBAH
=========================== */
if (isset($_POST['tambah'])) {
    $controller->tambah($_POST);
    header("Location: pengumuman.php");
    exit;
}

/* ==========================
   PROSES EDIT
=========================== */
if (isset($_POST['edit'])) {
    $controller->edit($_POST);
    header("Location: pengumuman.php");
    exit;
}

/* ==========================
   PROSES HAPUS
=========================== */
if (isset($_GET['hapus'])) {
    $controller->hapus($_GET['hapus']);
    header("Location: pengumuman.php");
    exit;
}
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

        /* ================= MODAL STYLING ================= */
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
        }

        .modal-content input:focus,
        .modal-content textarea:focus,
        .modal-content select:focus {
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
            <h4 class="fw-bold mb-1">Pengumuman</h4>
            <button class="btn btn-secondary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
                + Tambah Pengumuman
            </button>
        </div>

        <?php if (empty($pengumuman)): ?>
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
                        Total <?= count($pengumuman); ?> pengumuman
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 25%">Judul</th>
                                <th style="width: 40%">Isi</th>
                                <th style="width: 20%">Kategori</th>
                                <th style="width: 15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pengumuman as $p): ?>
                                <tr>
                                    <td><?= htmlspecialchars($p['judul']) ?></td>
                                    <td class="isi-cell"><?= nl2br(htmlspecialchars($p['isi'])) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($p['nama_kategori']) ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalEdit<?= $p['pengumuman_id'] ?>">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <a href="?hapus=<?= $p['pengumuman_id'] ?>" 
                                           onclick="return confirm('Hapus pengumuman ini?')"
                                           class="btn btn-danger btn-sm">
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

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title w-100 text-center">Penambahan Pengumuman</h5>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Judul <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control" placeholder="Masukkan Judul" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori_id" class="form-control" required>
                                <option value="">Pilih kategori</option>
                                <?php foreach ($kategori as $k): ?>
                                    <option value="<?= $k['kategori_id'] ?>">
                                        <?= htmlspecialchars($k['nama_kategori']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Isi Pengumuman <span class="text-danger">*</span></label>
                            <textarea name="isi" class="form-control" rows="4" placeholder="Masukkan Isi Pengumuman" required></textarea>
                        </div>
                        <button type="submit" name="tambah" class="btn btn-primary w-100">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <?php foreach ($pengumuman as $p): ?>
        <div class="modal fade" id="modalEdit<?= $p['pengumuman_id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title w-100 text-center">Edit Pengumuman</h5>
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                            <input type="hidden" name="pengumuman_id" value="<?= $p['pengumuman_id'] ?>">
                            <div class="mb-3">
                                <label class="form-label">Judul <span class="text-danger">*</span></label>
                                <input type="text" name="judul" class="form-control"
                                    value="<?= htmlspecialchars($p['judul']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select class="form-control" name="kategori_id" required>
                                    <?php foreach ($kategori as $k): ?>
                                        <option value="<?= $k['kategori_id'] ?>" 
                                            <?= ($k['kategori_id'] == $p['kategori_id']) ? "selected" : "" ?>>
                                            <?= htmlspecialchars($k['nama_kategori']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Isi Pengumuman <span class="text-danger">*</span></label>
                                <textarea name="isi" class="form-control" rows="4" required><?= htmlspecialchars($p['isi']) ?></textarea>
                            </div>
                            <button type="submit" name="edit" class="btn btn-success w-100">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <?php include("footer.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>