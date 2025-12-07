<?php
include '../../config/config.php';

$sql = "SELECT p.pengumuman_id, p.judul, p.isi, p.created_at, k.nama_kategori 
        FROM pengumuman p
        LEFT JOIN kategori k ON p.kategori_id = k.kategori_id
        ORDER BY p.created_at DESC";

$result = mysqli_query($conn, $sql);

if(!$result){
    die("SQL ERROR: " . mysqli_error($conn));
}

$pengumuman = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahasiswa - Pengumuman</title>
    <link rel="stylesheet" href="style.css">
    <style>
/* FILTER SECTION */
.filter-section {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 25px;
}

.filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.filter-title {
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

.reset-btn {
    background: #ff6b6b;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.3s;
}

.reset-btn:hover {
    background: #ff5252;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.filter-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-label {
    font-size: 14px;
    font-weight: 500;
    color: #555;
}

.filter-select {
    padding: 10px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;
    transition: border-color 0.3s;
    background: white;
}

.filter-select:focus {
    outline: none;
    border-color: #2193b0;
}

.search-box {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.search-input {
    flex: 1;
    padding: 10px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
}

.search-input:focus {
    outline: none;
    border-color: #2193b0;
}

.search-btn {
    background: #2193b0;
    color: white;
    border: none;
    padding: 10px 30px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.3s;
}

.search-btn:hover {
    background: #1a7a94;
}

/* TABLE SECTION */
.table-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
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

table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: #f8f9fa;
}

.table thead th {
    background-color: #6c757d;
    color: white;
    text-align: left;
}

th {
    padding: 15px 20px;
    font-weight: 600;
    color: white;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

td {
    padding: 15px 20px;
    border-bottom: 1px solid #f0f0f0;
    color: #333;
    font-size: 14px;
}

tbody tr {
    transition: background 0.2s;
}

tbody tr:hover {
    background: #f8f9fa;
}

.table tbody tr:nth-child(even) {
    background-color: #f1f1f1;
}

/* Kolom khusus */
.table thead th.col-kategori,
.table tbody td.col-kategori {
    background-color: #554141 !important;
    color: white;
    text-align: center;
}

.table thead th.col-aksi,
.table tbody td.col-aksi {
    background-color: #6B2C2C !important;
    color: white;
    text-align: center;
}

/* BADGES */
.badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
}

.badge-akademik {
    background: #e3f2fd;
    color: #1976d2;
}

.badge-kemahasiswaan {
    background: #f3e5f5;
    color: #7b1fa2;
}

.badge-umum {
    background: #fff3e0;
    color: #f57c00;
}

.badge-beasiswa {
    background: #e8f5e9;
    color: #388e3c;
}

.badge-urgent {
    background: #ffebee;
    color: #c62828;
}

/* ACTION BUTTON */
.action-btn {
    background: #2193b0;
    color: white;
    border: none;
    padding: 7px 15px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    transition: 0.3s;
}

.action-btn:hover {
    background: #1a7a94;
    transform: scale(1.04);
}

/* PAGINATION */
.pagination {
    padding: 20px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 2px solid #f0f0f0;
}

.page-info {
    font-size: 14px;
    color: #777;
}

.page-buttons {
    display: flex;
    gap: 10px;
}

.page-btn {
    padding: 8px 15px;
    border: 1px solid #e0e0e0;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s;
}

.page-btn:hover:not(:disabled) {
    background: #2193b0;
    color: white;
    border-color: #2193b0;
}

.page-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.page-btn.active {
    background: #2193b0;
    color: white;
    border-color: #2193b0;
}

/* Responsive */
@media (max-width: 768px) {
    .filter-grid {
        grid-template-columns: 1fr;
    }
}
</style>
</head>

<body>
    <?php include("header.php"); ?>

    <div class="container">
        <h1 class="page-title">Pengumuman</h1>

        <!-- FILTER -->
        <div class="filter-section">
            <div class="filter-header">
                <div class="filter-title">🔍 Filter Pengumuman</div>
                <button class="reset-btn" onclick="resetFilters()">Hapus Filter</button>
            </div>

            <div class="filter-grid">
                <div class="filter-item">
                    <label class="filter-label">Kategori</label>
                    <select class="filter-select" id="kategori">
                        <option value="">Semua Kategori</option>
                        <option value="akademik">Akademik</option>
                        <option value="kemahasiswaan">Kemahasiswaan</option>
                        <option value="beasiswa">Beasiswa</option>
                        <option value="umum">Umum</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>

                <div class="filter-item">
                    <label class="filter-label">Bulan</label>
                    <select class="filter-select" id="bulan">
                        <option value="">Semua Bulan</option>
                        <option value="01">Januari</option>
                        <option value="02">Februari</option>
                        <option value="03">Maret</option>
                        <option value="04">April</option>
                        <option value="05">Mei</option>
                        <option value="06">Juni</option>
                        <option value="07">Juli</option>
                        <option value="08">Agustus</option>
                        <option value="09">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>

                <div class="filter-item">
                    <label class="filter-label">Tahun</label>
                    <select class="filter-select" id="tahun">
                        <option value="">Semua Tahun</option>
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                    </select>
                </div>
            </div>

            <div class="search-box">
                <input type="text" class="search-input" id="search" placeholder="Cari berdasarkan judul pengumuman...">
                <button class="search-btn" onclick="applyFilters()">Cari</button>
            </div>
        </div>

        <!-- TABEL -->
        <div class="table-section">
            <div class="table-header">
                <div class="table-title">Daftar Pengumuman</div>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Judul Pengumuman</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="tableBody">
                        <tr>
                            <td>1</td>
                            <td>23 Okt 2025</td>
                            <td>Pengumuman Jadwal UTS Semester Ganjil 2025/2026</td>
                            <td><span class="badge badge-akademik">Akademik</span></td>
                            <td><button class="action-btn">Lihat Detail</button></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>22 Okt 2025</td>
                            <td>Pendaftaran Beasiswa PPA Tahun 2025</td>
                            <td><span class="badge badge-beasiswa">Beasiswa</span></td>
                            <td><button class="action-btn">Lihat Detail</button></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>20 Okt 2025</td>
                            <td>Lomba Karya Tulis Ilmiah Tingkat Nasional</td>
                            <td><span class="badge badge-kemahasiswaan">Kemahasiswaan</span></td>
                            <td><button class="action-btn">Lihat Detail</button></td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>18 Okt 2025</td>
                            <td>Perubahan Jadwal Kuliah Mata Kuliah Basis Data</td>
                            <td><span class="badge badge-akademik">Akademik</span></td>
                            <td><button class="action-btn">Lihat Detail</button></td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>15 Okt 2025</td>
                            <td>Webinar Nasional: Masa Depan Teknologi AI</td>
                            <td><span class="badge badge-umum">Umum</span></td>
                            <td><button class="action-btn">Lihat Detail</button></td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>12 Okt 2025</td>
                            <td>Pembayaran UKT Semester Ganjil 2025/2026</td>
                            <td><span class="badge badge-akademik">Akademik</span></td>
                            <td><button class="action-btn">Lihat Detail</button></td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>10 Okt 2025</td>
                            <td>Rekrutmen Asisten Laboratorium Komputer</td>
                            <td><span class="badge badge-kemahasiswaan">Kemahasiswaan</span></td>
                            <td><button class="action-btn">Lihat Detail</button></td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>08 Okt 2025</td>
                            <td>Seminar Proposal Tugas Akhir Batch 3</td>
                            <td><span class="badge badge-akademik">Akademik</span></td>
                            <td><button class="action-btn">Lihat Detail</button></td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>05 Okt 2025</td>
                            <td>Pendaftaran Magang Industri Semester Genap</td>
                            <td><span class="badge badge-akademik">Akademik</span></td>
                            <td><button class="action-btn">Lihat Detail</button></td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>01 Okt 2025</td>
                            <td>Libur Nasional dan Cuti Bersama Oktober 2025</td>
                            <td><span class="badge badge-umum">Umum</span></td>
                            <td><button class="action-btn">Lihat Detail</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include("footer.php"); ?>

    <!-- ============================= -->
    <!-- ===       JAVASCRIPT      === -->
    <!-- ============================= -->
    <script>
    const originalRows = [...document.querySelectorAll("#tableBody tr")];

    function applyFilters() {
        const kategori = document.getElementById("kategori").value.toLowerCase();
        const bulan = document.getElementById("bulan").value;
        const tahun = document.getElementById("tahun").value;
        const search = document.getElementById("search").value.toLowerCase();

        const tbody = document.getElementById("tableBody");
        tbody.innerHTML = "";

        let filtered = originalRows.filter(row => {
            const tanggal = row.children[1].innerText;
            const judul = row.children[2].innerText.toLowerCase();
            const kategoriRow = row.children[3].innerText.toLowerCase();

            if (kategori !== "" && !kategoriRow.includes(kategori)) return false;

            const parts = tanggal.split(" ");
            const bulanMap = {
                "Jan": "01","Feb": "02","Mar": "03","Apr": "04",
                "Mei": "05","Jun": "06","Jul": "07","Agu": "08",
                "Sep": "09","Okt": "10","Nov": "11","Des": "12"
            };

            const rowBulan = bulanMap[parts[1]];
            const rowTahun = parts[2];

            if (bulan !== "" && bulan !== rowBulan) return false;
            if (tahun !== "" && tahun !== rowTahun) return false;

            if (search !== "" && !judul.includes(search)) return false;

            return true;
        });

        filtered.forEach((row, i) => {
            const clone = row.cloneNode(true);
            clone.children[0].innerText = i + 1;
            tbody.appendChild(clone);
        });
    }

    function resetFilters() {
        document.getElementById("kategori").value = "";
        document.getElementById("bulan").value = "";
        document.getElementById("tahun").value = "";
        document.getElementById("search").value = "";
        applyFilters();
    }
    </script>

</body>
</html>
