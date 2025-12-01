<?php
// =======================
// INCLUDE CONFIG.PHP
// =======================
include __DIR__ . '/../../config/config.php'; // perbaikan path

// Cek koneksi database
if (!$conn) {
    die("Koneksi database tidak tersedia!");
}

// =======================
// PROSES TAMBAH PENGUMUMAN
// =======================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $judul = $_POST['judul'];
    $kategori_id = $_POST['kategori_id'];
    $isi = $_POST['isi'];

    $query = "INSERT INTO pengumuman (judul, kategori_id, isi, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sis", $judul, $kategori_id, $isi);

    if ($stmt->execute()) {
        echo "<script>alert('Pengumuman berhasil ditambahkan!'); window.location.href='pengumuman.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal menambahkan pengumuman!');</script>";
    }
}

// =======================
// PROSES EDIT PENGUMUMAN
// =======================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $pengumuman_id = $_POST['pengumuman_id'];
    $judul = $_POST['judul'];
    $kategori_id = $_POST['kategori_id'];
    $isi = $_POST['isi'];

    $query = "UPDATE pengumuman SET judul=?, kategori_id=?, isi=? WHERE pengumuman_id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sisi", $judul, $kategori_id, $isi, $pengumuman_id);

    if ($stmt->execute()) {
        echo "<script>alert('Pengumuman berhasil diupdate!'); window.location.href='pengumuman.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal mengupdate pengumuman!');</script>";
    }
}

// =======================
// PROSES HAPUS PENGUMUMAN
// =======================
if (isset($_GET['hapus'])) {
    $pengumuman_id = $_GET['hapus'];
    $query = "DELETE FROM pengumuman WHERE pengumuman_id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $pengumuman_id);

    if ($stmt->execute()) {
        echo "<script>alert('Pengumuman berhasil dihapus!'); window.location.href='pengumuman.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal menghapus pengumuman!');</script>";
    }
}

// =======================
// AMBIL DATA PENGUMUMAN
// =======================
$query = "SELECT p.pengumuman_id, p.judul, p.isi, p.kategori_id, k.nama_kategori 
          FROM pengumuman p 
          LEFT JOIN kategori k ON p.kategori_id = k.kategori_id 
          ORDER BY p.created_at DESC";
$result = $conn->query($query);

// Ambil data kategori untuk dropdown
$query_kategori = "SELECT kategori_id, nama_kategori FROM kategori ORDER BY nama_kategori ASC";
$result_kategori = $conn->query($query_kategori);

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Style tetap sama seperti sebelumnya */
        .table thead .col-nama-pengumuman,
        .table thead .col-kategori,
        .table thead .col-aksi {
            background-color: #2193b0 !important;
            color: #fff !important;
            text-align: center;
        }

        .col-kategori {
            text-align: center;
        }

        .col-aksi {
            text-align: center;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }

        .btn-close {
            filter: invert(1);
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

        /* ===============================
   TOMBOL SUBMIT (BIRU)
   =============================== */
        .btn-submit {
            background-color: #0d6efd !important;
            color: white !important;
            width: 100%;
            border: none;
            border-radius: 6px;
            padding: 10px 0;
            font-size: 15px;
            font-weight: 600;
        }

        .btn-submit:hover {
            background-color: #0b5ed7 !important;
            color: white !important;
        }

        /* Menang terhadap Bootstrap di dalam modal */
        .modal .btn-submit {
            background-color: #0d6efd !important;
            color: white !important;
        }
    </style>
</head>

<body class="pengumuman-page">
    <?php include("header.php"); ?>

    <main class="container my-4">
        <h4 class="fw-bold">Pengumuman</h4>
        <button class="btn btn-secondary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
            + Tambah Pengumuman
        </button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="col-nama-pengumuman" style="width:65%">Nama Pengumuman</th>
                    <th class="col-kategori" style="width:20%">Kategori</th>
                    <th class="col-aksi" style="width:15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php $no = 1;
                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++ . ". " . htmlspecialchars($row['judul']); ?></td>
                            <td class="col-kategori"><?php echo htmlspecialchars($row['nama_kategori']); ?></td>
                            <td class="col-aksi">
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalEdit<?php echo $row['pengumuman_id']; ?>">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus pengumuman ini?') ? window.location.href='pengumuman.php?hapus=<?php echo $row['pengumuman_id']; ?>' : false;">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="modalEdit<?php echo $row['pengumuman_id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5>Edit Pengumuman</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST">
                                            <input type="hidden" name="pengumuman_id"
                                                value="<?php echo $row['pengumuman_id']; ?>">
                                            <div class="mb-3">
                                                <label class="form-label">Judul Pengumuman</label>
                                                <input type="text" class="form-control" name="judul"
                                                    value="<?php echo htmlspecialchars($row['judul']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Kategori</label>
                                                <select class="form-control" name="kategori_id" required>
                                                    <option value="">Pilih kategori</option>
                                                    <?php
                                                    $result_kategori->data_seek(0);
                                                    while ($kat = $result_kategori->fetch_assoc()):
                                                        ?>
                                                        <option value="<?php echo $kat['kategori_id']; ?>" <?php echo ($kat['kategori_id'] == $row['kategori_id']) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($kat['nama_kategori']); ?>
                                                        </option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Isi Pengumuman</label>
                                                <textarea class="form-control" name="isi" rows="3"
                                                    required><?php echo htmlspecialchars($row['isi']); ?></textarea>
                                            </div>
                                            <button type="submit" name="edit" class="btn btn-submit mt-2">Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="no-data">
                            <i class="bi bi-inbox" style="font-size:48px; display:block; margin-bottom:10px;"></i>
                            Belum ada data pengumuman. Silakan tambah pengumuman baru.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Penambahan Pengumuman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Judul Pengumuman</label>
                            <input type="text" class="form-control" name="judul" placeholder="Masukkan judul pengumuman"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-control" name="kategori_id" required>
                                <option value="">Pilih kategori</option>
                                <?php
                                $result_kategori->data_seek(0);
                                while ($kat = $result_kategori->fetch_assoc()):
                                    ?>
                                    <option value="<?php echo $kat['kategori_id']; ?>">
                                        <?php echo htmlspecialchars($kat['nama_kategori']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Isi Pengumuman</label>
                            <textarea class="form-control" name="isi" rows="3" placeholder="Masukkan isi pengumuman"
                                required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include("footer.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>