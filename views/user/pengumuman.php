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


require_once __DIR__ . '/../../app/controllers/user/pengumuman_controller.php';
$controller = new PengumumanControllerUser();
$data = $controller->index();

$pengumuman = $data['pengumuman'] ?? [];
$kategoriList = $data['kategori'] ?? [];
$bulanList = $data['bulanList'] ?? [];
$tahunList = $data['tahunList'] ?? [];

$kategoriDipilih = $data['kategoriDipilih'] ?? null;
$bulanDipilih = $data['bulanDipilih'] ?? null;
$tahunDipilih = $data['tahunDipilih'] ?? null;

$currentPage = $data['currentPage'] ?? 1;
$totalPages = $data['totalPages'] ?? 1;
$totalData = $data['totalData'] ?? 0;
$startData = $data['startData'] ?? 0;
$endData = $data['endData'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahasiswa | Pengumuman</title>
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
            margin-top: 15px;
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

        .pengumuman-table {
            width: 100%;
            border-collapse: collapse;
        }

        .pengumuman-table thead {
            background: #6c757d;
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
            background: #f8f9fa;
        }

        .pengumuman-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .pengumuman-table tbody tr:nth-child(even):hover {
            background-color: #f0f0f0;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            color: #777 !important;
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
            text-decoration: none;
            color: #333;
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

        .page-dots {
            padding: 8px 5px;
            color: #999;
            font-weight: bold;
        }

        /* MODAL STYLES */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-content {
            background-color: white;
            margin: 3% auto;
            width: 90%;
            max-width: 900px;
            border-radius: 12px;
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.3s;
            max-height: 90vh;
            overflow-y: auto;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
            padding: 25px 30px;
            color: white;
            border-radius: 12px 12px 0 0;
            position: relative;
        }

        .modal-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
            padding-right: 60px;
            line-height: 1.3;
        }

        .modal-meta {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            font-size: 13px;
            opacity: 0.95;
            padding-right: 60px;
        }

        .modal-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .modal-kategori-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            background: white;
            color: #2193b0;
        }

        .close-modal {
            position: absolute;
            right: 15px;
            top: 15px;
            color: white;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transition: background 0.2s;
        }

        .close-modal:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .modal-body {
            padding: 30px;
        }

        .modal-label {
            font-size: 14px;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
            display: block;
        }

        .modal-text {
            font-size: 15px;
            line-height: 1.8;
            color: #333;
            text-align: justify;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .modal-divider {
            height: 2px;
            background: linear-gradient(to right, #2193b0, transparent);
            margin: 25px 0;
        }

        .modal-footer {
            padding: 20px 30px;
            background: #f8f9fa;
            border-radius: 0 0 12px 12px;
            text-align: right;
        }

        .modal-btn-print {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .modal-btn-print:hover {
            background: #218838;
            transform: translateY(-2px);
        }

        /* Loading spinner */
        .modal-loading {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #2193b0;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .filter-grid {
                grid-template-columns: 1fr;
            }

            .table-wrapper {
                overflow-x: scroll;
            }

            .modal-content {
                width: 95%;
                margin: 5% auto;
            }

            .modal-header {
                padding: 20px;
            }

            .modal-title {
                font-size: 20px;
            }

            .modal-body {
                padding: 20px;
            }

            .modal-meta {
                flex-direction: column;
                gap: 10px;
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
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <div class="page-info">
                        Halaman <?= $currentPage; ?> dari <?= $totalPages; ?>
                    </div>

                    <div class="page-buttons">
                        <!-- Previous Button -->
                        <?php if ($currentPage > 1): ?>
                            <a href="?kategori=<?= urlencode($kategoriDipilih); ?>&bulan=<?= $bulanDipilih; ?>&tahun=<?= $tahunDipilih; ?>&page=<?= $currentPage - 1; ?>"
                                class="page-btn">
                                ← Sebelumnya
                            </a>
                        <?php else: ?>
                            <button class="page-btn" disabled>← Sebelumnya</button>
                        <?php endif; ?>

                        <!-- Page Numbers -->
                        <?php
                        $range = 2;
                        $start = max(1, $currentPage - $range);
                        $end = min($totalPages, $currentPage + $range);

                        if ($start > 1) {
                            echo '<a href="?kategori=' . urlencode($kategoriDipilih) . '&bulan=' . $bulanDipilih . '&tahun=' . $tahunDipilih . '&page=1" class="page-btn">1</a>';
                            if ($start > 2)
                                echo '<span class="page-dots">...</span>';
                        }

                        for ($i = $start; $i <= $end; $i++) {
                            $active = ($i == $currentPage) ? 'active' : '';
                            echo '<a href="?kategori=' . urlencode($kategoriDipilih) . '&bulan=' . $bulanDipilih . '&tahun=' . $tahunDipilih . '&page=' . $i . '" class="page-btn ' . $active . '">' . $i . '</a>';
                        }

                        if ($end < $totalPages) {
                            if ($end < $totalPages - 1)
                                echo '<span class="page-dots">...</span>';
                            echo '<a href="?kategori=' . urlencode($kategoriDipilih) . '&bulan=' . $bulanDipilih . '&tahun=' . $tahunDipilih . '&page=' . $totalPages . '" class="page-btn">' . $totalPages . '</a>';
                        }
                        ?>

                        <!-- Next Button -->
                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?kategori=<?= urlencode($kategoriDipilih); ?>&bulan=<?= $bulanDipilih; ?>&tahun=<?= $tahunDipilih; ?>&page=<?= $currentPage + 1; ?>"
                                class="page-btn">
                                Selanjutnya →
                            </a>
                        <?php else: ?>
                            <button class="page-btn" disabled>Selanjutnya →</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- MODAL DETAIL -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close-modal" onclick="closeModal()">&times;</span>
                <h2 class="modal-title" id="modalTitle">Loading...</h2>
                <div class="modal-meta" id="modalMeta"></div>
            </div>
            <div class="modal-body" id="modalBody">
                <div class="modal-loading">
                    <div class="spinner"></div>
                    <p>Memuat data...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="printModal()" class="modal-btn-print">🖨️ Cetak</button>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            const modal = document.getElementById('detailModal');
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';

            document.getElementById('modalTitle').textContent = 'Loading...';
            document.getElementById('modalMeta').innerHTML = '';
            document.getElementById('modalBody').innerHTML = '<div class="modal-loading"><div class="spinner"></div><p>Memuat data...</p></div>';

            fetch('get_detail.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const p = data.pengumuman;
                        document.getElementById('modalTitle').textContent = p.judul;
                        document.getElementById('modalMeta').innerHTML = `
                            <div class="modal-meta-item">
                                <span>📅</span>
                                <span>${p.tanggal_lengkap}</span>
                            </div>
                            <div class="modal-meta-item">
                                <span>📂</span>
                                <span class="modal-kategori-badge">${p.kategori}</span>
                            </div>
                        `;
                        document.getElementById('modalBody').innerHTML = `
                            <span class="modal-label">📋 Isi Pengumuman</span>
                            <div class="modal-divider"></div>
                            <div class="modal-text">${p.isi}</div>
                        `;
                    } else {
                        document.getElementById('modalBody').innerHTML = '<p style="text-align:center; padding:40px; color:#ff6b6b;">Data tidak ditemukan.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('modalBody').innerHTML = '<p style="text-align:center; padding:40px; color:#ff6b6b;">Terjadi kesalahan saat memuat data.</p>';
                });
        }

        function closeModal() {
            document.getElementById('detailModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('detailModal')) {
                closeModal();
            }
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') closeModal();
        });

        function printModal() {
            const modalContent = document.querySelector('.modal-content').cloneNode(true);
            modalContent.querySelector('.close-modal').remove();
            modalContent.querySelector('.modal-footer').remove();

            const printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print Pengumuman</title>');
            printWindow.document.write('<style>body{font-family:Arial,sans-serif;padding:20px}.modal-header{background:#2193b0;color:white;padding:20px;margin-bottom:20px}.modal-title{font-size:24px;margin-bottom:10px}.modal-meta{display:flex;gap:15px;font-size:14px}.modal-body{padding:20px}.modal-text{line-height:1.8;white-space:pre-wrap}.modal-divider{height:2px;background:#2193b0;margin:15px 0}</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(modalContent.innerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>

    <?php include("footer.php"); ?>

</body>

</html>