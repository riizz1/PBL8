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
    <link rel="stylesheet" href="style.css">
    <style>
        /* ====== STYLE TABEL ====== */
        .col-nama {
            background-color: #7a7171 !important;
            color: white;
        }

        .col-aksi {
            background-color: #6B2C2C !important;
            color: white;
            text-align: center;
        }

        .table thead .col-nama,
        .table thead .col-aksi {
            background-color: #2193b0 !important;
        }

        .table tbody tr:nth-child(odd) .col-nama,
        .table tbody tr:nth-child(odd) .col-aksi {
            background-color: #ffffff !important;
            color: #000;
        }

        .table tbody tr:nth-child(even) .col-nama,
        .table tbody tr:nth-child(even) .col-aksi {
            background-color: #0000004f !important;
            color: #fff;
        }

        /* ====== MODAL FIX (PUTIH CLEAN UI) ====== */
        .modal-content {
            background-color: #ffffff !important;
            color: #000000 !important;
            border-radius: 10px;
            padding: 20px;
        }

        /* Input Style */
        .modal input.form-control,
        .modal textarea.form-control {
            background-color: #ffffff !important;
            border: 1px solid #d1d1d1 !important;
            color: #000 !important;
            border-radius: 6px;
        }

        /* Placeholder */
        .modal input::placeholder,
        .modal textarea::placeholder {
            color: #9c9c9c !important;
        }

        /* Focus Effect */
        .modal input.form-control:focus,
        .modal textarea.form-control:focus {
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, .25) !important;
        }

        /* Modal Title */
        .modal .modal-title {
            font-weight: 600;
        }

        /* Submit Button */
        .modal button[type="submit"] {
            background-color: #0d6efd;
            border-radius: 6px;
            color: white;
            border: none;
            font-weight: 500;
        }

        .modal button[type="submit"]:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>

<body>
    <?php include("header.php"); ?>

    <main class="container my-4">

        <!-- ✅ NOTIFIKASI STATUS -->
        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-<?= $_GET['status'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show"
                role="alert">
                <?= htmlspecialchars($_GET['msg'] ?? 'Operasi selesai'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <h4 class="fw-bold mb-1">Kategori</h4>
            <button class="btn btn-secondary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
                + Tambah Kategori
            </button>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="col-nama" style="width: 85%">Nama Kategori</th>
                    <th class="col-aksi" style="width: 15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($dataKategori)): ?>
                    <?php $no = 1;
                    foreach ($dataKategori as $row): ?>
                        <tr>
                            <td class="col-nama"><?= $no++ . ". " . htmlspecialchars($row['nama_kategori']); ?></td>
                            <td class="col-aksi">
                                <!-- Tombol Edit -->
                                <button class="btn btn-warning btn-sm" 
                                        onclick='editKategori(<?= json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                
                                <!-- Tombol Hapus -->
                                <a href="/PBL8/app/controllers/admin/kategori_controller.php?action=delete&id=<?= $row['kategori_id'] ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin hapus kategori \'<?= htmlspecialchars($row['nama_kategori']) ?>\'?')">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" class="text-center">Belum ada kategori</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

    <!-- Modal Tambah Kategori -->
    <div class="modal fade" id="modalTambahKategori" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Penambahan Kategori Pengumuman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="/PBL8/app/controllers/kategori_controller.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="nama_kategori" class="form-control"
                                placeholder="Masukkan Nama Kategori" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <input type="text" name="deskripsi" class="form-control" placeholder="Masukkan Deskripsi">
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
                    <h5 class="modal-title">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="/PBL8/app/controllers/kategori_controller.php" method="POST">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="kategori_id" id="edit_kategori_id">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="nama_kategori" id="edit_nama_kategori" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <input type="text" name="deskripsi" id="edit_deskripsi" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include("footer.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editKategori(data) {
            document.getElementById('edit_kategori_id').value = data.kategori_id;
            document.getElementById('edit_nama_kategori').value = data.nama_kategori;
            document.getElementById('edit_deskripsi').value = data.deskripsi || '';
            
            var modal = new bootstrap.Modal(document.getElementById('modalEditKategori'));
            modal.show();
        }
    </script>
</body>

</html>