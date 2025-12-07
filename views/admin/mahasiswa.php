<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* ================= HEADER TABEL BIRU ================= */
        table.table thead th {
            background-color: #2193b0 !important;
            color: white !important;
            text-align: center !important;
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

        /* Zebra stripe baris tabel */
        table.table tbody tr:nth-child(odd) td {
            background-color: #ffffff !important;
            /* putih */
        }

        table.table tbody tr:nth-child(even) td {
            background-color: #0000004f !important;
            /* abu-abu */
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

        /* Nama mahasiswa rata kiri */
        table.table tbody td:first-child {
            text-align: left !important;
        }


        /* ================= MODAL GELAP ================= */
        .modal-content {
            background-color: #0000004f;
            color: white;
            border-radius: 10px;
            padding: 20px;
        }

        /* Border input pada modal Tambah Mahasiswa jadi abu rokok */
        #modalTambahMahasiswa .modal-content input {
            border: 1px solid #b0b0b0 !important;
            color: black !important;
        }

        /* Saat fokus tetap abu rokok lebih gelap */
        #modalTambahMahasiswa .modal-content input:focus {
            border: 1px solid #8f8f8f !important;
            background-color: #f2f2f2 !important;
            color: black !important;
        }


        .btn-close {
            filter: invert(1);
        }

        input::placeholder,
        textarea::placeholder {
            color: #ffffff;
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

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Mahasiswa</th>
                    <th>NIM</th>
                    <th>Prodi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="col-nama">Fariz Zikri Pohan</td>
                    <td class="col-nim">123456789</td>
                    <td class="col-prodi">Informatika</td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-btn" data-nama="Fariz Zikri Pohan"
                            data-nim="123456789" data-prodi="Informatika" data-bs-toggle="modal"
                            data-bs-target="#modalEditMahasiswa">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                    </td>
                </tr>
                <tr>
                    <td class="col-nama">Edo Cristian</td>
                    <td class="col-nim">3312501028</td>
                    <td class="col-prodi">Informatika</td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-btn" data-nama="Edo Cristian" data-nim="3312501028"
                            data-prodi="Informatika" data-bs-toggle="modal" data-bs-target="#modalEditMahasiswa">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                    </td>
                </tr>

                <tr>
                    <td class="col-nama">Citra Anggun Batubara</td>
                    <td class="col-nim">3312501030</td>
                    <td class="col-prodi">Informatika</td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-btn" data-nama="Citra Anggun Batubara"
                            data-nim="3312501030" data-prodi="Informatika" data-bs-toggle="modal"
                            data-bs-target="#modalEditMahasiswa">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                    </td>
                </tr>

            </tbody>
        </table>
    </main>

    <!-- ================= MODAL TAMBAH MAHASISWA ================= -->
    <div class="modal fade" id="modalTambahMahasiswa" tabindex="-1" aria-labelledby="modalTambahMahasiswaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title w-100 text-center" id="modalTambahMahasiswaLabel">
                        Penambahan Akun Mahasiswa
                    </h5>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formTambahMahasiswa">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" placeholder="Masukkan Nama">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIM</label>
                            <input type="text" class="form-control" placeholder="Masukkan NIM">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Prodi</label>
                            <input type="text" class="form-control" placeholder="Masukkan Prodi">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= MODAL EDIT MAHASISWA ================= -->
    <div class="modal fade" id="modalEditMahasiswa" tabindex="-1" aria-labelledby="modalEditMahasiswaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title w-100 text-center" id="modalEditMahasiswaLabel">
                        Edit Data Mahasiswa
                    </h5>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditMahasiswa">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" id="editNama">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIM</label>
                            <input type="text" class="form-control" id="editNim">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Prodi</label>
                            <input type="text" class="form-control" id="editProdi">
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
        let rowToEdit = null;

        // ================= JS EDIT MODAL =================
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {

                // Simpan baris <tr> yang sedang diedit
                rowToEdit = button.closest('tr');

                // Ambil data dari atribut tombol
                document.getElementById('editNama').value = button.dataset.nama;
                document.getElementById('editNim').value = button.dataset.nim;
                document.getElementById('editProdi').value = button.dataset.prodi;
            });
        });

        document.getElementById('formEditMahasiswa').addEventListener('submit', e => {
            e.preventDefault();

            // Ambil nilai baru
            const newNama = document.getElementById('editNama').value;
            const newNim = document.getElementById('editNim').value;
            const newProdi = document.getElementById('editProdi').value;

            // Update kolom di baris yang diedit
            rowToEdit.querySelector('.col-nama').textContent = newNama;
            rowToEdit.querySelector('.col-nim').textContent = newNim;
            rowToEdit.querySelector('.col-prodi').textContent = newProdi;

=======
>>>>>>> b6950b5 (update citra)
            alert('Perubahan berhasil disimpan!');

            // Tutup modal
            const modalEl = document.getElementById('modalEditMahasiswa');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
        });

    </script>

</body>

</html>