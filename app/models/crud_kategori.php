<?php
include 'koneksi.php';

// ===================== HANDLE TAMBAH =====================
if(isset($_POST['action']) && $_POST['action'] == 'tambah') {
    $nama = $_POST['kategori_nama'];
    $stmt = $koneksi->prepare("INSERT INTO kategori (kategori_nama) VALUES (?)");
    $stmt->bind_param("s", $nama);
    $stmt->execute();
    $stmt->close();
    header("Location: kategori.php");
    exit;
}

// ===================== HANDLE EDIT =====================
if(isset($_POST['action']) && $_POST['action'] == 'edit') {
    $id = $_POST['kategori_id'];
    $nama = $_POST['kategori_nama'];
    $stmt = $koneksi->prepare("UPDATE kategori SET kategori_nama = ? WHERE kategori_id = ?");
    $stmt->bind_param("si", $nama, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: kategori.php");
    exit;
}

// ===================== HANDLE HAPUS =====================
if(isset($_GET['hapus_id'])) {
    $id = $_GET['hapus_id'];
    $stmt = $koneksi->prepare("DELETE FROM kategori WHERE kategori_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: kategori.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manajemen Kategori</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <h3 class="mb-4">Kategori Pengumuman</h3>

    <!-- Tombol Tambah -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Kategori</button>

    <!-- Tabel -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Kategori ID</th>
                <th>Nama Kategori</th>
                <th style="width: 15%">Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $query = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY kategori_id ASC");
        while($row = mysqli_fetch_assoc($query)):
        ?>
            <tr>
                <td><?= $row['kategori_id'] ?></td>
                <td><?= htmlspecialchars($row['kategori_nama']) ?></td>
                <td>
                    <!-- Edit Button -->
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['kategori_id'] ?>">
                        <i class="bi bi-pencil-fill"></i>
                    </button>
                    <!-- Hapus Button -->
                    <a href="?hapus_id=<?= $row['kategori_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kategori ini?');">
                        <i class="bi bi-trash-fill"></i>
                    </a>
                </td>
            </tr>

            <!-- Modal Edit -->
            <div class="modal fade" id="modalEdit<?= $row['kategori_id'] ?>" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form method="POST">
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Kategori</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <input type="hidden" name="kategori_id" value="<?= $row['kategori_id'] ?>">
                      <input type="hidden" name="action" value="edit">
                      <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" name="kategori_nama" class="form-control" value="<?= htmlspecialchars($row['kategori_nama']) ?>" required>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-success w-100">Simpan Perubahan</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Kategori</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="action" value="tambah">
          <div class="mb-3">
            <label class="form-label">Nama Kategori</label>
            <input type="text" name="kategori_nama" class="form-control" placeholder="Masukkan nama kategori" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary w-100">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
