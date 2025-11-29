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

.modal-content input,
.modal-content textarea {
    background-color: #3a3a3a;
    color: white;
    border: none;
}

.modal-content input:focus,
.modal-content textarea:focus {
    background-color: #444;
    color: white;
    border: 1px solid #0d6efd;
}

/* Placeholder putih untuk modal tambah kategori */
#modalTambahKategori input::placeholder,
#modalTambahKategori textarea::placeholder {
    color: #ffffff !important;
    opacity: 1 !important;
}

#modalTambahKategori input,
#modalTambahKategori textarea {
    color: #ffffff !important;
}

/* Fallback terkuat */
#modalTambahKategori .modal-content input.form-control,
#modalTambahKategori .modal-content textarea.form-control {
    color: #ffffff !important;
}

#modalTambahKategori .modal-content input.form-control::placeholder,
#modalTambahKategori .modal-content textarea.form-control::placeholder {
    color: #ffffff !important;
    opacity: 1 !important;
}
        /* Placeholder input dan textarea pada halaman kategori */
        .kategori-page ::placeholder {
            color: #ffffff !important;
            opacity: 1;
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
    </style>
</head>

<body class="kategori-page">
    <?php
    include("header.php");
    ?>

    <!-- Konten -->
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
                <tr>
                    <td class="col-nama">1. Jadwal Akademik (20 pengumuman)</td>
                    <td class="col-aksi">
                        <button class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                    </td>
                </tr>
                <tr>
                    <td class="col-nama">2. Perkuliahan (15 pengumuman)</td>
                    <td class="col-aksi">
                        <button class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                    </td>
                </tr>
                <tr>
                    <td class="col-nama">3. Ujian (12 pengumuman)</td>
                    <td class="col-aksi">
                        <button class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                    </td>
                </tr>
                <tr>
                    <td class="col-nama">4. Beasiswa (8 pengumuman)</td>
                    <td class="col-aksi">
                        <button class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                    </td>
                </tr>
                <tr>
                    <td class="col-nama">5. Kegiatan (25 pengumuman)</td>
                    <td class="col-aksi">
                        <button class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                    </td>
                </tr>
                <tr>
                    <td class="col-nama">6. Magang & Karier (10 pengumuman)</td>
                    <td class="col-aksi">
                        <button class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                    </td>
                </tr>
                <tr>
                    <td class="col-nama">7. Umum (30 pengumuman)</td>
                    <td class="col-aksi">
                        <button class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </main>

    <!-- Modal Tambah Kategori -->
    <div class="modal fade" id="modalTambahKategori" tabindex="-1" aria-labelledby="modalTambahKategoriLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="modalTambahKategoriLabel">Penambahan Kategori Pengumuman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="kategoriName" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="kategoriName"
                                placeholder="Masukkan nama kategori">
                        </div>
                        <div class="mb-3">
                            <label for="deskripsiKategori" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsiKategori" rows="3"
                                placeholder="Deskripsi kategori"></textarea>
                        </div>
                        <button type="submit" class="btn btn-light w-100">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php
    include("footer.php");
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>