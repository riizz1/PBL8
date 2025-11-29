<?php
// Data Statistik
$total_pengumuman = 120;
$total_mahasiswa = 1250;
$dibaca = 4320;
$rata_rata = 3.5;
$kategori = [
    ["Jadwal Akademik", 20, "1.200 kali"],
    ["Perkuliahan", 15, "900 kali"],
    ["Ujian", 12, "1.500 kali"],
    ["Beasiswa", 8, "2.100 kali"],
    ["Kegiatan", 25, "1.000 kali"],
    ["Magang & Karier", 10, "750 kali"],
    ["Umum", 30, "2.500 kali"]
];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - Polibatam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <style>
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f4f4f4;
}

.table thead th {
    background-color: #6c757d;
    color: white;
}

.table tbody tr:nth-child(even) {
    background-color: #f1f1f1;
}

.stat-box {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
</style>
</head>

<body class="laporan-page">
    <?php
    include("header.php");
    ?>

    <!-- Main Content -->
    <div class="container my-5">
        <h1 class="fw-bold mb-4">Laporan</h1>

        <!-- Filter -->
        <div class="mb-5">
            <h6 class="fw-bold">Filter Laporan</h6>
            <div class="p-3 bg-white border rounded d-flex align-items-center gap-2">
                <label class="fw-semibold mb-0">Periode:</label>
                <select class="form-select w-auto" id="bulan">
                    <option value="">Bulan</option>
                    <option>Januari</option>
                    <option>Februari</option>
                    <option>Maret</option>
                </select>
                <select class="form-select w-auto" id="tahun">
                    <option value="">Tahun</option>
                    <option>2024</option>
                    <option>2025</option>
                </select>
                <button class="btn btn-dark" id="tampilkan"><i class="fas fa-search"></i> Tampilkan</button>
            </div>
        </div>

        <!-- Statistik Umum -->
        <div class="mb-5">
            <h6 class="fw-bold">Statistik Umum</h6>
            <div class="stat-box">
                <ol class="mb-0">
                    <li>Total Pengumuman: <strong><?= $total_pengumuman ?></strong></li>
                    <li>Total Mahasiswa Aktif: <strong><?= number_format($total_mahasiswa, 0, ',', '.') ?></strong></li>
                    <li>Pengumuman Dibaca: <strong><?= number_format($dibaca, 0, ',', '.') ?> kali</strong></li>
                    <li>Rata-rata Baca/Mhs: <strong><?= $rata_rata ?> kali</strong></li>
                </ol>
            </div>
        </div>

        <!-- Laporan Per Kategori -->
        <div class="mb-5">
            <h6 class="fw-bold">Laporan Per Kategori</h6>
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th>Jumlah Pengumuman</th>
                            <th>Dibaca</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kategori as $i => $row): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= $row[0] ?></td>
                                <td><?= $row[1] ?></td>
                                <td><?= $row[2] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php
    include("footer.php")
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('tampilkan').addEventListener('click', function () {
            const bulan = document.getElementById('bulan').value;
            const tahun = document.getElementById('tahun').value;
            if (!bulan || !tahun) {
                alert('Silakan pilih bulan dan tahun terlebih dahulu!');
            } else {
                alert(`Menampilkan laporan periode ${bulan} ${tahun}`);
            }
        });
    </script>

</body>

</html>