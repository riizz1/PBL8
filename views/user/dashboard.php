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

require_once __DIR__ . '/../../app/controllers/user/dashboard_controller.php';
$controller = new DashboardControllerUser();
$data = $controller->index();

$pengumumanTerbaru = $data['pengumumanTerbaru'] ?? [];
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mahasiswa - Dashboard</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <link rel="icon" type="image/png" sizes="32x32" href="/PBL8/public/assets/img/hat.svg">

  <style>
    /* ================= DASHBOARD ================= */
    .main-content {
      padding: 40px;
    }

    .dashboard-box {
      background-color: #fff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    /* FIXED CAROUSEL */
    #kampusCarousel {
      width: 100%;
      max-width: 95%;
      margin: 0 auto;
      overflow: hidden;
      border-radius: 16px;
      background-color: transparent;
      aspect-ratio: 16 / 5;
    }

    #kampusCarousel .carousel-inner {
      width: 100%;
      height: 100%;
    }

    #kampusCarousel .carousel-item {
      width: 100%;
      height: 100%;
    }

    #kampusCarousel .carousel-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
      display: block;
      background-color: transparent;
    }

    /* Tombol navigasi carousel */
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

    /* Pengumuman Section */
    .pengumuman-section {
      text-align: center;
      margin-top: 40px;
      margin-bottom: 40px;
    }

    .pengumuman-title {
      font-size: 28px;
      font-weight: 700;
      color: #333;
      margin-bottom: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    /* Carousel Pengumuman Custom */
    #pengumumanCarousel .carousel-item {
      min-height: 250px;
    }

    .pengumuman-content {
      padding: 35px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      min-height: 250px;
    }

    .pengumuman-badge {
      display: inline-block;
      padding: 6px 16px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 12px;
    }

    .pengumuman-judul {
      font-size: 22px;
      font-weight: 700;
      color: #333;
      margin-bottom: 12px;
      line-height: 1.4;
    }

    .pengumuman-isi {
      font-size: 15px;
      color: #555;
      line-height: 1.6;
      margin-bottom: 12px;
      max-width: 700px;
      text-align: center;
    }

    .pengumuman-date {
      font-size: 14px;
      color: #777;
      display: flex;
      align-items: center;
      gap: 5px;
      justify-content: center;
      margin-bottom: 15px;
    }

    .btn-lihat-semua {
      background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
      color: white;
      border: none;
      padding: 10px 25px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.3s;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      margin-top: 5px;
    }

    .btn-lihat-semua:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(33, 147, 176, 0.4);
      color: white;
    }

    /* Badge colors */
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

    /* Empty state */
    .empty-pengumuman {
      text-align: center;
      padding: 50px;
      color: #999;
    }

    .empty-pengumuman i {
      font-size: 48px;
      margin-bottom: 15px;
      opacity: 0.5;
    }

    /* Responsive */
    @media (max-width: 768px) {
      #kampusCarousel {
        aspect-ratio: 16 / 10;
      }

      .pengumuman-title {
        font-size: 22px;
      }

      .pengumuman-judul {
        font-size: 18px;
      }

      .pengumuman-content {
        padding: 25px 20px;
        min-height: 200px;
      }

      .pengumuman-isi {
        font-size: 14px;
      }
    }
  </style>

</head>

<body>

  <?php include 'header.php'; ?>

  <!-- 🔹 Carousel Kampus -->
  <div id="kampusCarousel" class="carousel slide my-4" data-bs-ride="carousel">
    <div class="carousel-inner rounded-4 overflow-hidden mx-3 shadow-sm">
      <div class="carousel-item active">
        <img src="/PBL8/public/assets/img/polibatam.jpg" class="d-block w-100" alt="Kampus 1">
      </div>
      <div class="carousel-item">
        <img src="/PBL8/public/assets/img/polibatam.jpg" class="d-block w-100" alt="Kampus 2">
      </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#kampusCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#kampusCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>

  <!-- 🔹 Pengumuman Terbaru -->
  <div class="page-container pengumuman-section">
    <h4 class="pengumuman-title">
      <i class="bi bi-megaphone-fill" style="color: #2193b0;"></i>
      Pengumuman Terbaru
    </h4>

    <div id="pengumumanCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
      <div class="carousel-inner position-relative rounded-4 shadow-sm mx-auto"
        style="background-color:#ffffff; max-width:900px;">

        <?php if (empty($pengumumanTerbaru)): ?>
          <!-- Jika tidak ada pengumuman -->
          <div class="carousel-item active">
            <div class="empty-pengumuman">
              <i class="bi bi-megaphone"></i>
              <p class="m-0 fs-5 fw-semibold">Belum ada pengumuman terbaru</p>
              <small class="text-muted">Pengumuman akan muncul di sini</small>
            </div>
          </div>
        <?php else: ?>
          <!-- Loop pengumuman dari database -->
          <?php foreach ($pengumumanTerbaru as $index => $pengumuman): ?>
            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
              <div class="pengumuman-content">
                <span class="pengumuman-badge badge-<?= strtolower($pengumuman['nama_kategori'] ?? 'umum'); ?>">
                  <?= htmlspecialchars($pengumuman['nama_kategori'] ?? 'Umum'); ?>
                </span>

                <div class="pengumuman-judul">
                  <?= htmlspecialchars($pengumuman['judul']); ?>
                </div>

                <div class="pengumuman-isi">
                  <?php
                  $isi = strip_tags($pengumuman['isi']);
                  $isi = strlen($isi) > 150 ? substr($isi, 0, 150) . '...' : $isi;
                  echo htmlspecialchars($isi);
                  ?>
                </div>

                <div class="pengumuman-date">
                  <i class="bi bi-calendar-event"></i>
                  <?= date('d F Y', strtotime($pengumuman['created_at'])); ?>
                </div>

                <a href="pengumuman.php" class="btn-lihat-semua">
                  Lihat Semua Pengumuman
                  <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

        <!-- Tombol navigasi -->
        <?php if (count($pengumumanTerbaru) > 1): ?>
          <button class="carousel-control-prev" type="button" data-bs-target="#pengumumanCarousel" data-bs-slide="prev"
            style="width: 5%;">
            <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
            <span class="visually-hidden">Sebelumnya</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#pengumumanCarousel" data-bs-slide="next"
            style="width: 5%;">
            <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
            <span class="visually-hidden">Berikutnya</span>
          </button>
        <?php endif; ?>
      </div>

      <!-- Indicators -->
      <?php if (count($pengumumanTerbaru) > 1): ?>
        <div class="carousel-indicators" style="position: relative; margin-top: 15px;">
          <?php foreach ($pengumumanTerbaru as $index => $pengumuman): ?>
            <button type="button" data-bs-target="#pengumumanCarousel" data-bs-slide-to="<?= $index; ?>"
              class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
              aria-label="Slide <?= $index + 1; ?>" style="background-color: #2193b0;"></button>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- 🔹 Footer -->
  <?php include("footer.php"); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>