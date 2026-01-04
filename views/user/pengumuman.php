<?php
session_start();

// Proteksi halaman - harus login dulu
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
    echo "<script>
        alert('Anda harus login terlebih dahulu!');
        location.href='/PBL8/views/auth/login.php';
    </script>";
    exit();
}

// Proteksi role - hanya mahasiswa yang bisa akses
if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'mahasiswa') {
    echo "<script>
        alert('Akses ditolak! Halaman ini hanya untuk mahasiswa.');
        location.href='/PBL8/views/auth/login.php';
    </script>";
    exit();
}

require_once __DIR__ . '/../../app/controllers/user/pengumuman_controller.php';
$controller = new PengumumanControllerUser();
$data = $controller->index();

$allPengumuman = $data['pengumuman'] ?? [];
$kategoriList = $data['kategori'] ?? [];
$bulanList = $data['bulanList'] ?? [];
$tahunList = $data['tahunList'] ?? [];

$kategoriDipilih = $data['kategoriDipilih'] ?? null;
$bulanDipilih = $data['bulanDipilih'] ?? null;
$tahunDipilih = $data['tahunDipilih'] ?? null;

// ================= PAGINATION LOGIC =================
$itemsPerPage = 10;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

$totalData = count($allPengumuman);
$totalPages = ceil($totalData / $itemsPerPage);

$pengumuman = array_slice($allPengumuman, $offset, $itemsPerPage);

$startData = $totalData > 0 ? $offset + 1 : 0;
$endData = min($offset + $itemsPerPage, $totalData);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahasiswa | Pengumuman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" sizes="32x32" href="/PBL8/public/assets/img/hat.svg">

    <style>
        /* ================= PENGUMUMAN ================= */
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
            text-decoration: none;
            display: inline-block;
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
            border-color: #51c8e9;
        }

        .search-btn {
            background: #51c8e9;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
            margin-top: 15px;
        }

        .search-btn:hover {
            background: #3ab0d9;
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

        .pengumuman-table {
            width: 100%;
            border-collapse: collapse;
        }

        .pengumuman-table thead {
            background: #51c8e9;
        }

        .pengumuman-table thead th {
            padding: 15px 20px;
            font-weight: 600;
            color: white;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: left;
        }

        .pengumuman-table tbody td {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
            font-size: 14px;
        }

        .pengumuman-table tbody tr {
            transition: background 0.2s;
        }

        .pengumuman-table tbody tr:hover {
            background: #e8f8fd;
        }

        .pengumuman-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .pengumuman-table tbody tr:nth-child(even):hover {
            background-color: #e8f8fd;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px !important;
            font-weight: 400 !important;
            display: inline-block;
            color: #333 !important;
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
            background: #51c8e9;
            color: white;
            border: none;
            padding: 7px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            transition: 0.3s;
        }

        .action-btn:hover {
            background: #3ab0d9;
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
            margin-left: 10px;
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
            text-decoration: none;
            color: #333;
        }

        .page-btn:hover:not(:disabled) {
            background: #51c8e9;
            color: white;
            border-color: #51c8e9;
        }

        .page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .page-btn.active {
            background: #51c8e9;
            color: white;
            border-color: #51c8e9;
        }

        .page-dots {
            padding: 8px 5px;
            color: #999;
            font-weight: bold;
        }

        /* MODAL CUSTOM STYLING */
        .modal-header {
            background: linear-gradient(135deg, #51c8e9 0%, #3ab0d9 100%);
            color: white;
            border-bottom: none;
        }

        .modal-title {
            font-weight: 700;
        }

        .modal-body {
            padding: 25px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .info-item i {
            color: #51c8e9;
            font-size: 18px;
        }

        .info-label {
            font-weight: 600;
            color: #666;
            min-width: 100px;
        }

        .info-value {
            color: #333;
        }

        .divider {
            height: 2px;
            background: linear-gradient(to right, #51c8e9, transparent);
            margin: 20px 0;
        }

        .content-label {
            font-size: 14px;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
            display: block;
        }

        .content-text {
            font-size: 15px;
            line-height: 1.8;
            color: #333;
            text-align: justify;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .filter-grid {
                grid-template-columns: 1fr;
            }

            .table-wrapper {
                overflow-x: scroll;
            }

            .pagination {
                flex-direction: column;
                gap: 15px;
            }

            .page-buttons {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</head>

<body>

    <?php include("header.php"); ?>

    <div class="page-container full-width">
        <h1 class="page-title">Pengumuman</h1>

        <!-- FILTER FORM -->
        <form method="GET" class="filter-section">
            <div class="filter-header">
                <div class="filter-title">🔍 Filter Pengumuman</div>
                <a href="pengumuman.php" class="reset-btn">Hapus Filter</a>
            </div>

            <div class="filter-grid">
                <!-- KATEGORI -->
                <div class="filter-item">
                    <label class="filter-label">Kategori</label>
                    <select name="kategori" class="filter-select">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($kategoriList as $kat): ?>
                            <option value="<?= htmlspecialchars($kat['nama_kategori']); ?>"
                                <?= ($kategoriDipilih === $kat['nama_kategori']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars(ucfirst($kat['nama_kategori'])); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- BULAN -->
                <div class="filter-item">
                    <label class="filter-label">Bulan</label>
                    <select name="bulan" class="filter-select">
                        <option value="">Semua Bulan</option>
                        <?php foreach ($bulanList as $b): ?>
                            <option value="<?= $b['bulan']; ?>" <?= ($bulanDipilih == $b['bulan']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($b['nama_bulan']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- TAHUN -->
                <div class="filter-item">
                    <label class="filter-label">Tahun</label>
                    <select name="tahun" class="filter-select">
                        <option value="">Semua Tahun</option>
                        <?php foreach ($tahunList as $t): ?>
                            <option value="<?= $t['tahun']; ?>" <?= ($tahunDipilih == $t['tahun']) ? 'selected' : '' ?>>
                                <?= $t['tahun']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <button type="submit" class="search-btn">Terapkan Filter</button>
        </form>

        <!-- TABLE SECTION -->
        <div class="table-section">
            <div class="table-header">
                <div class="table-title">Daftar Pengumuman</div>
                <div class="entries-info">
                    Menampilkan <?= $startData; ?> - <?= $endData; ?> dari <?= $totalData; ?> pengumuman
                </div>
            </div>

            <div class="table-wrapper">
                <table class="pengumuman-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($pengumuman)): ?>
                            <tr>
                                <td colspan="5" style="text-align:center;">Tidak ada pengumuman ditemukan.</td>
                            </tr>
                        <?php else: ?>
                            <?php $no = $startData; ?>
                            <?php foreach ($pengumuman as $row): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= date('d M Y', strtotime($row['created_at'])); ?></td>
                                    <td><?= htmlspecialchars($row['judul']); ?></td>
                                    <td>
                                        <span class="badge badge-<?= strtolower($row['nama_kategori']); ?>">
                                            <?= htmlspecialchars(ucfirst($row['nama_kategori'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button onclick="openModal(<?= $row['pengumuman_id']; ?>)" class="action-btn">
                                            Lihat Detail
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <?php if ($totalData > 0): ?>
                <div class="pagination">
                    <div class="page-info">
                        Halaman <?= $currentPage; ?> dari <?= $totalPages; ?>
                    </div>

                    <div class="page-buttons">
                        <?php
                        $queryParams = [];
                        if (!empty($kategoriDipilih))
                            $queryParams[] = 'kategori=' . urlencode($kategoriDipilih);
                        if (!empty($bulanDipilih))
                            $queryParams[] = 'bulan=' . $bulanDipilih;
                        if (!empty($tahunDipilih))
                            $queryParams[] = 'tahun=' . $tahunDipilih;
                        $baseQuery = !empty($queryParams) ? '&' . implode('&', $queryParams) : '';
                        ?>

                        <?php if ($currentPage > 1): ?>
                            <a href="?page=<?= $currentPage - 1; ?><?= $baseQuery; ?>" class="page-btn">← Sebelumnya</a>
                        <?php else: ?>
                            <button class="page-btn" disabled>← Sebelumnya</button>
                        <?php endif; ?>

                        <?php
                        $range = 2;
                        $start = max(1, $currentPage - $range);
                        $end = min($totalPages, $currentPage + $range);

                        if ($start > 1) {
                            echo '<a href="?page=1' . $baseQuery . '" class="page-btn">1</a>';
                            if ($start > 2)
                                echo '<span class="page-dots">...</span>';
                        }

                        for ($i = $start; $i <= $end; $i++) {
                            $active = ($i == $currentPage) ? 'active' : '';
                            echo '<a href="?page=' . $i . $baseQuery . '" class="page-btn ' . $active . '">' . $i . '</a>';
                        }

                        if ($end < $totalPages) {
                            if ($end < $totalPages - 1)
                                echo '<span class="page-dots">...</span>';
                            echo '<a href="?page=' . $totalPages . $baseQuery . '" class="page-btn">' . $totalPages . '</a>';
                        }
                        ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?page=<?= $currentPage + 1; ?><?= $baseQuery; ?>" class="page-btn">Selanjutnya →</a>
                        <?php else: ?>
                            <button class="page-btn" disabled>Selanjutnya →</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- BOOTSTRAP MODAL -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Loading...</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3">Memuat data...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const modalInstance = new bootstrap.Modal(document.getElementById('detailModal'));

        function openModal(id) {
            modalInstance.show();

            document.getElementById('modalTitle').textContent = 'Loading...';
            document.getElementById('modalBody').innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3">Memuat data...</p>
                </div>
            `;

            fetch('get_detail.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const p = data.pengumuman;
                        document.getElementById('modalTitle').textContent = p.judul;
                        document.getElementById('modalBody').innerHTML = `
                            <div class="info-item">
                                <i class="bi bi-calendar-event"></i>
                                <span class="info-label">Tanggal:</span>
                                <span class="info-value">${p.tanggal_lengkap}</span>
                            </div>
                            <div class="info-item">
                                <i class="bi bi-folder"></i>
                                <span class="info-label">Kategori:</span>
                                <span class="info-value badge badge-${p.kategori.toLowerCase()}">${p.kategori}</span>
                            </div>
                            <div class="info-item">
                                <i class="bi bi-person-circle"></i>
                                <span class="info-label">Diupload oleh:</span>
                                <span class="info-value">${p.nama_admin}</span>
                            </div>
                            <div class="divider"></div>
                            <span class="content-label"><i class="bi bi-file-text"></i> Isi Pengumuman</span>
                            <div class="content-text">${p.isi}</div>
                        `;
                    } else {
                        document.getElementById('modalBody').innerHTML = `
                            <div class="alert alert-danger" role="alert">
                                <i class="bi bi-exclamation-triangle"></i> ${data.message || 'Data tidak ditemukan'}
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('modalBody').innerHTML = `
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> Terjadi kesalahan saat memuat data
                        </div>
                    `;
                });
        }
    </script>

    <?php include("footer.php"); ?>

</body>

</html>