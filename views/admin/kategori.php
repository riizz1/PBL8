<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Pengumuman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .col-nama {
            background-color: #7a7171 !important;
            color: white;
        }

        .col-aksi {
            background-color: #6B2C2C !important;
            color: white;
            text-align: center;
        }

        /* Modal kategori */
        .modal-content {
            background-color: #2b2b2b;
            color: white;
            border-radius: 10px;
            padding: 20px;
        }

        /* ============================
   PRIORITAS TERTINGGI BORDER ABU ROKOK
   ============================ */
        #modalTambahKategori .modal-content input.form-control,
        #modalTambahKategori .modal-content textarea.form-control {
            border: 1px solid #b0b0b0 !important;
            /* ABU ROKOK */
            background-color: #2b2b2b !important;
            color: #ffffff !important;
        }

        /* Fokus tetap abu rokok */
        #modalTambahKategori .modal-content input.form-control:focus,
        #modalTambahKategori .modal-content textarea.form-control:focus {
            border: 1px solid #8f8f8f !important;
            /* ABU ROKOK GELAP */
            background-color: #444 !important;
            color: white !important;
        }

        /* Placeholder */
        #modalTambahKategori .modal-content input.form-control::placeholder,
        #modalTambahKategori .modal-content textarea.form-control::placeholder {
            color: #d0d0d0 !important;
        }


        .col-aksi {
            background-color: #6B2C2C !important;
            color: white;
            text-align: center;
        }

        /* Modal kategori */
        .modal-content {
            background-color: #2b2b2b;
            color: white;
            border-radius: 10px;
            padding: 20px;
        }

        /* ============================
   PRIORITAS TERTINGGI BORDER ABU ROKOK
   ============================ */
        #modalTambahKategori .modal-content input.form-control,
        #modalTambahKategori .modal-content textarea.form-control {
            border: 1px solid #b0b0b0 !important;
            /* ABU ROKOK */
            background-color: #2b2b2b !important;
            color: #ffffff !important;
        }

        /* Fokus tetap abu rokok */
        #modalTambahKategori .modal-content input.form-control:focus,
        #modalTambahKategori .modal-content textarea.form-control:focus {
            border: 1px solid #8f8f8f !important;
            /* ABU ROKOK GELAP */
            background-color: #444 !important;
            color: white !important;
        }

        /* Placeholder */
        #modalTambahKategori .modal-content input.form-control::placeholder,
        #modalTambahKategori .modal-content textarea.form-control::placeholder {
            color: #d0d0d0 !important;
        }


        /* Warna teks input & textarea juga jadi putih */
        .kategori-page input.form-control,
        .kategori-page textarea.form-control {
            color: #ffffff !important;
        }

        /* Kalau background input masih terang, gelapkan */
        .kategori-page input.form-control,
        .kategori-page textarea.form-control {
            background-color: #2b2b2b !important;
            border: 1px solid #555;
        }

        /* Kolom Nama Kategori (bagian <th> di tabel) */
        .table thead .col-nama {
            background-color: #2193b0 !important;
            /* biru muda */
        }

        /* Kolom Nama Kategori (bagian <td> di dalam body) */
        .table tbody .col-nama {
            background-color: #8bb7dbff !important;
            /* biru muda sedikit lebih terang */
        }

        /* Kolom Aksi (header) */
        .table thead .col-aksi {
            background-color: #2193b0 !important;
            /* biru muda */
        }

        /* Baris ganjil – putih */
        .table tbody tr:nth-child(odd) .col-nama {
            background-color: #ffffff !important;
            color: #000000d8;
        }

        /* Baris genap – hitam */
        .table tbody tr:nth-child(even) .col-nama {
            background-color: #0000004f !important;
            color: #fff;
        }

        /* Kolom Aksi – Baris ganjil (putih) */
        .table tbody tr:nth-child(odd) .col-aksi {
            background-color: #ffffff !important;
            color: #000;
        }

        /* Kolom Aksi – Baris genap (hitam) */
        .table tbody tr:nth-child(even) .col-aksi {
            background-color: #0000004f !important;
            color: #fff;
        }

        .btn-submit {
            background-color: #0d6efd !important;
            color: #fff !important;
            border: none !important;
        }

        .btn-submit:hover {
            background-color: #0b5ed7 !important;
        }
    </style>

</head>

<body class="kategori-page">

    <?php include("header.php"); ?>

    <main class="container my-4">
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
                <?php if (!empty($dataKategori)) : ?>
                    <?php $no = 1;
                    foreach ($dataKategori as $row) : ?>
                        <tr>
                            <td class="col-nama">
                                <?= $no++ . ". " . htmlspecialchars($row['nama_kategori']); ?>
                            </td>
                            <td class="col-aksi">
                                <a href="#" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
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
                    <form action="/PBL8/app/controllers/models/crud_kategori.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="nama_kategori" class="form-control" placeholder="Masukkan nama kategori">
                        </div>
                         <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <input type="text" name="deskripsi" class="form-control" placeholder="Masukkan deskripsi">
                        </div>
                        <button type="submit" class="btn btn-light w-100">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include("footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>