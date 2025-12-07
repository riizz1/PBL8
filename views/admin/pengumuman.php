<?php
require_once __DIR__ . '/../../app/controllers/admin/pengumuman_controller.php';

$controller = new PengumumanControllerAdmin();
$data = $controller->index();

$pengumuman = $data['pengumuman'];
$kategori   = $data['kategori'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .table thead th {
            background-color: #2193b0 !important;
            color: #fff !important;
            text-align: center;
        }

        .modal-content {
            background-color: #222;
            color: white;
            border-radius: 10px;
        }

        .form-control {
            background-color: #333;
            color: white;
            border: 1px solid #555;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: none;
        }

        .btn-submit {
            background-color: #0d6efd !important;
            color: white !important;
        }
    </style>
</head>

<body>
<?php include("header.php"); ?>

<main class="container my-4">
    <h4 class="fw-bold">Pengumuman</h4>

    <!-- Tombol tambah -->
    <button class="btn btn-secondary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
        + Tambah Pengumuman
    </button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Judul</th>
                <th style="width:20%">Kategori</th>
                <th style="width:15%">Aksi</th>
            </tr>
        </thead>
        <tbody>

        <?php if (!empty($pengumuman)): ?>
            <?php foreach ($pengumuman as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['judul']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($p['nama_kategori']) ?></td>
                    <td class="text-center">
                        <!-- Tombol Edit -->
                        <button class="btn btn-warning btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEdit<?= $p['pengumuman_id'] ?>">
                            <i class="bi bi-pencil-fill"></i>
                        </button>

                        <!-- Hapus -->
                        <a href="?hapus=<?= $p['pengumuman_id'] ?>"
                           onclick="return confirm('Hapus pengumuman ini?')"
                           class="btn btn-danger btn-sm">
                            <i class="bi bi-trash-fill"></i>
                        </a>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="modalEdit<?= $p['pengumuman_id'] ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">Edit Pengumuman</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <form method="POST">
                                    <input type="hidden" name="pengumuman_id" value="<?= $p['pengumuman_id'] ?>">

                                    <div class="mb-3">
                                        <label>Judul</label>
                                        <input type="text" name="judul" class="form-control"
                                            value="<?= htmlspecialchars($p['judul']) ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Kategori</label>
                                        <select class="form-control" name="kategori_id" required>
                                            <?php foreach ($kategori as $k): ?>
                                                <option value="<?= $k['kategori_id'] ?>"
                                                    <?= ($k['kategori_id'] == $p['kategori_id']) ? "selected" : ""; ?>>
                                                    <?= htmlspecialchars($k['nama_kategori']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>Isi Pengumuman</label>
                                        <textarea name="isi" class="form-control" rows="3" required><?= 
                                            htmlspecialchars($p['isi']) ?></textarea>
                                    </div>

                                    <button type="submit" name="edit" class="btn btn-submit w-100">
                                        Update
                                    </button>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <tr>
                <td colspan="3" class="text-center py-4 text-muted">
                    <i class="bi bi-inbox" style="font-size:40px;"></i><br>
                    Belum ada pengumuman.
                </td>
            </tr>
        <?php endif; ?>

        </tbody>
    </table>
</main>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Pengumuman</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form method="POST">

                    <div class="mb-3">
                        <label>Judul</label>
                        <input type="text" name="judul" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Kategori</label>
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
                        <label>Isi Pengumuman</label>
                        <textarea name="isi" class="form-control" rows="3" required></textarea>
                    </div>

                    <button type="submit" name="tambah" class="btn btn-submit w-100">
                        Simpan
                    </button>

                </form>
            </div>

        </div>
    </div>
</div>

<?php include("footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
