<?php
// include file config (sesuaikan path jika perlu)
include '../../config/config.php';

// Pastikan ada variabel koneksi — dukung beberapa nama yang mungkin dipakai di config.php
if (isset($conn) && $conn instanceof mysqli) {
    $db = $conn;
} elseif (isset($koneksi) && $koneksi instanceof mysqli) {
    $db = $koneksi;
} else {
    // debug singkat: tunjukkan pesan yang informatif
    die("Koneksi database tidak ditemukan. Periksa file config.php (harus ada \$conn atau \$koneksi).");
}

// Ambil data pengumuman
$sql = "SELECT p.judul, p.isi, p.created_at, k.nama_kategori AS kategori
        FROM pengumuman p
        LEFT JOIN kategori k ON p.kategori_id = k.kategori_id
        ORDER BY p.created_at DESC";

$result = mysqli_query($db, $sql);
if (!$result) {
    die("SQL ERROR: " . mysqli_error($db));
}

$pengumuman = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahasiswa - Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- CSS (tidak dipindahkan, sesuai permintaan) -->
    <style>
        .main-content {
            padding: 40px;
        }

        .dashboard-box {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        /* Carousel Kampus */
        #kampusCarousel {
            width: 100%;
            max-width: 95%;
            margin: 0 auto;
            overflow: hidden;
            border-radius: 16px;
            background-color: transparent;
            aspect-ratio: 16 / 5;
        }

        #kampusCarousel .carousel-inner,
        #kampusCarousel .carousel-item {
            width: 100%;
            height: 100%;
        }

        #kampusCarousel .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            filter: invert(1);
            width: 2.5rem;
            height: 2.5rem;
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 8%;
        }

        /* Pengumuman */
        .pengumuman-box {
            background-color: #d9d9d9;
            border-radius: 10px;
            padding: 40px 20px;
            text-align: center;
            position: relative;
        }

        /* Stats Cards */
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-info h3 {
            font-size: 28px;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-info p {
            font-size: 14px;
            color: #777;
        }

        @media (max-width: 768px) {
            #kampusCarousel {
                aspect-ratio: 16 / 10;
            }

            .stats-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>

    <!-- Carousel Kampus (tetap seperti aslinya) -->
    <div id="kampusCarousel" class="carousel slide my-4" data-bs-ride="carousel">
        <div class="carousel-inner rounded-4 overflow-hidden mx-3 shadow-sm">
            <div class="carousel-item active">
                <img src="../assets/img/polibatam.jpg" class="d-block w-100" alt="Kampus">
            </div>
            <div class="carousel-item">
                <img src="../assets/img/polibatam.jpg" class="d-block w-100" alt="Kampus 2">
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#kampusCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
            <span class="visually-hidden">Previous</span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#kampusCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Pengumuman (diperbaiki loop dan aman) -->
    <div class="container mt-4">
        <h4 class="fw-bold mb-3 text-center">Pengumuman</h4>

        <div id="pengumumanCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-inner rounded-4 shadow-sm mx-auto"
                style="background-color:#f8f9fa; max-width:900px; min-height:200px;">

                <?php if (!empty($pengumuman)): ?>
                    <?php $active = true; ?>
                    <?php foreach ($pengumuman as $p): ?>
                        <div class="carousel-item <?= $active ? 'active' : '' ?>">
                            <div class="p-4 text-center">

                                <p class="fs-5 fw-bold">
                                    📢 <?= htmlspecialchars($p['judul']) ?>
                                </p>

                                <?php if (!empty($p['kategori'])): ?>
                                    <span class="badge bg-primary mb-2">
                                        <?= htmlspecialchars($p['kategori']) ?>
                                    </span><br>
                                <?php endif; ?>

                                <p class="fs-6" style="white-space: normal;">
                                    <?= nl2br(htmlspecialchars($p['isi'])) ?>
                                </p>

                                <small class="text-muted">
                                    Diposting: <?= date('d M Y', strtotime($p['created_at'])) ?>
                                </small>

                            </div>
                        </div>
                        <?php $active = false; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="carousel-item active">
                        <div class="p-4 text-center">
                            <p class="fs-5 text-muted">Tidak ada pengumuman.</p>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Navigasi -->
            <button class="carousel-control-prev" type="button" data-bs-target="#pengumumanCarousel"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-dark rounded-circle p-3"></span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#pengumumanCarousel"
                data-bs-slide="next">
                <span class="carousel-control-next-icon bg-dark rounded-circle p-3"></span>
            </button>

        </div>
    </div>

    <?php include("footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
