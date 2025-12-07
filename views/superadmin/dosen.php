<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Superadmin - Dosen</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header-blue {
            background-color: #2193b0 !important;
            color: white !important;
        }

        .header-blue .navbar-brand {
            color: white !important;
            /* teks navbar putih */
        }

        .card-header-blue {
            background-color: #2193b0 !important;
            color: white !important;
        }

        .btn-skyblue {
            background-color: #1bbcf7ff !important;
            /* biru langit */
            color: white !important;
            border: none !important;
        }

        footer {
            width: 100%;
            text-align: center;
        }

        footer p {
            color: white;
            /* teks putih */
            margin-bottom: 1rem;
        }

        footer .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            /* ikon putih */
            transition: color 0.3s ease, transform 0.3s ease;
        }

        footer .social-icons a:hover {
            color: #0d6efd;
            /* biru saat hover */
            transform: scale(1.1);
        }

        footer .footer-icon {
            width: 24px;
            height: 24px;
            stroke: currentColor;
            /* mengikuti warna parent */
        }
    </style>

</head>

<body>
    <?php include("header.php"); ?>
    <div class="container my-4">
        <div class="row">
            <!-- Form tambah/edit mahasiswa -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header card-header-blue">
                        Tambah / Edit Mahasiswa
                    </div>

                    <div class="card-body">
                        <form id="dosenForm">
                            <input type="hidden" id="dosenIndex">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Mahasiswa</label>
                                <input type="text" class="form-control" id="nama" required>
                            </div>
                            <div class="mb-3">
                                <label for="nim" class="form-label">NIM</label>
                                <input type="text" class="form-control" id="nim" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                            <button type="submit" class="btn btn-skyblue w-100">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabel daftar dosen -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header card-header-blue">
                        Daftar Mahasiswa
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="dosenTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NIM</th>
                                    <th>Email</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data dosen muncul di sini -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <?php
   include('footer.php')
   ?>
    <script src="https://unpkg.com/lucide@latest/dist/lucide.min.js"></script>
    <script>
        lucide.createIcons();
    </script>


    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>

</html>