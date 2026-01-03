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

// Proteksi role - hanya dosen yang bisa akses
if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'dosen') {
  echo "<script>
        alert('Akses ditolak! Halaman ini hanya untuk Dosen.');
        location.href='/PBL8/views/auth/login.php';
    </script>";
  exit();
}
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../app/controllers/admin/dashboard_controller.php';

$controller = new DashboardController($config);
$data = $controller->index();

$total_pengumuman = $data['total_pengumuman'];
$total_kategori = $data['total_kategori'];
$total_mahasiswa = $data['total_mahasiswa'];
$pengumuman_terbaru = $data['pengumuman_terbaru'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin | Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    /* ================= DASHBOARD SIMPLE & CLEAN ================= */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fa;
    }

    .main-content {
      padding: 40px;
      min-height: calc(100vh - 150px);
    }

    /* ===== HEADER ===== */
    .dashboard-header {
      margin-bottom: 35px;
    }

    .dashboard-header h2 {
      font-weight: 700;
      color: #2d3748;
      margin-bottom: 8px;
      font-size: 2rem;
    }

    .dashboard-header p {
      color: #718096;
      margin: 0;
    }

    /* ===== STAT CARD SIMPLE ===== */
    .stat-card {
      background: #ffffff;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
      border: 1px solid #e2e8f0;
      height: 100%;
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }

    .stat-icon {
      width: 50px;
      height: 50px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      margin-bottom: 15px;
    }

    .stat-icon.icon-pengumuman {
      background-color: #e3f2fd;
      color: #1976d2;
    }

    .stat-icon.icon-kategori {
      background-color: #f3e5f5;
      color: #7b1fa2;
    }

    .stat-icon.icon-mahasiswa {
      background-color: #e8f5e9;
      color: #388e3c;
    }

    .stat-number {
      font-size: 2rem;
      font-weight: 700;
      color: #2d3748;
      margin: 10px 0 5px 0;
    }

    .stat-label {
      font-size: 0.9rem;
      color: #718096;
      font-weight: 500;
    }

    /* ===== QUICK ACCESS ===== */
    .quick-access-card {
      background: #ffffff;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      border: 1px solid #e2e8f0;
      margin-bottom: 30px;
    }

    .quick-access-card h5 {
      font-weight: 700;
      color: #2d3748;
      margin-bottom: 20px;
      font-size: 1.1rem;
    }

    .quick-btn {
      background: #ffffff;
      border: 2px solid #e2e8f0;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      transition: all 0.3s ease;
      text-decoration: none;
      display: block;
      color: #2d3748;
    }

    .quick-btn:hover {
      border-color: #cbd5e0;
      background-color: #f7fafc;
      transform: translateY(-3px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      color: #2d3748;
    }

    .quick-btn i {
      font-size: 2rem;
      margin-bottom: 10px;
      display: block;
    }

    .quick-btn .btn-pengumuman i {
      color: #1976d2;
    }

    .quick-btn .btn-kategori i {
      color: #7b1fa2;
    }

    .quick-btn .btn-mahasiswa i {
      color: #388e3c;
    }

    .quick-btn span {
      font-weight: 600;
      font-size: 0.95rem;
    }

    /* ===== PENGUMUMAN TERBARU ===== */
    .announcement-card {
      background: #ffffff;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      border: 1px solid #e2e8f0;
    }

    .announcement-card h5 {
      font-weight: 700;
      color: #2d3748;
      margin-bottom: 20px;
      font-size: 1.1rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .announcement-item {
      padding: 18px;
      border-radius: 10px;
      border: 1px solid #e2e8f0;
      margin-bottom: 12px;
      transition: all 0.3s ease;
      background: #fff;
    }

    .announcement-item:hover {
      border-color: #cbd5e0;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      transform: translateX(3px);
    }

    .announcement-item:last-child {
      margin-bottom: 0;
    }

    .announcement-title {
      font-weight: 600;
      color: #2d3748;
      margin-bottom: 5px;
      font-size: 0.95rem;
    }

    .announcement-meta {
      font-size: 0.8rem;
      color: #718096;
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .announcement-badge {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 12px;
      font-size: 0.75rem;
      font-weight: 600;
      background-color: #e2e8f0;
      color: #4a5568;
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
      text-align: center;
      padding: 40px 20px;
      color: #a0aec0;
    }

    .empty-state i {
      font-size: 3rem;
      margin-bottom: 15px;
      opacity: 0.5;
    }

    .empty-state p {
      margin: 0;
      font-size: 0.9rem;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
      .main-content {
        padding: 20px;
      }

      .dashboard-header h2 {
        font-size: 1.5rem;
      }

      .stat-number {
        font-size: 1.5rem;
      }

      .quick-btn i {
        font-size: 1.5rem;
      }
    }
  </style>
</head>

<body>

  <?php include "header.php"; ?>

  <div class="main-content">

    <!-- ===== HEADER ===== -->
    <div class="dashboard-header">
      <h2><i class="bi bi-speedometer2 me-2"></i>Dashboard Admin</h2>
      <p>Selamat datang, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
    </div>

    <!-- ===== STATISTIK ===== -->
    <div class="row g-3 mb-4">
      <div class="col-lg-4 col-md-6">
        <div class="stat-card">
          <div class="stat-icon icon-pengumuman">
            <i class="bi bi-megaphone"></i>
          </div>
          <div class="stat-number"><?= number_format($total_pengumuman) ?></div>
          <div class="stat-label">Total Pengumuman</div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="stat-card">
          <div class="stat-icon icon-kategori">
            <i class="bi bi-tags"></i>
          </div>
          <div class="stat-number"><?= number_format($total_kategori) ?></div>
          <div class="stat-label">Total Kategori</div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="stat-card">
          <div class="stat-icon icon-mahasiswa">
            <i class="bi bi-people"></i>
          </div>
          <div class="stat-number"><?= number_format($total_mahasiswa) ?></div>
          <div class="stat-label">Total Mahasiswa</div>
        </div>
      </div>
    </div>

    <!-- ===== QUICK ACCESS ===== -->
    <div class="quick-access-card">
      <h5>Akses Cepat</h5>
      <div class="row g-3">
        <div class="col-lg-4 col-md-6">
          <a href="pengumuman.php" class="quick-btn">
            <div class="btn-pengumuman">
              <i class="bi bi-megaphone"></i>
              <span>Kelola Pengumuman</span>
            </div>
          </a>
        </div>
        <div class="col-lg-4 col-md-6">
          <a href="kategori.php" class="quick-btn">
            <div class="btn-kategori">
              <i class="bi bi-tags"></i>
              <span>Kelola Kategori</span>
            </div>
          </a>
        </div>
        <div class="col-lg-4 col-md-6">
          <a href="mahasiswa.php" class="quick-btn">
            <div class="btn-mahasiswa">
              <i class="bi bi-people"></i>
              <span>Kelola Mahasiswa</span>
            </div>
          </a>
        </div>
      </div>
    </div>

    <!-- ===== PENGUMUMAN TERBARU ===== -->
    <div class="announcement-card">
      <h5>
        <span><i class="bi bi-clock-history me-2"></i>Pengumuman Terbaru</span>
        <a href="pengumuman.php" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
      </h5>

      <?php
      $count = 0;
      while ($row = mysqli_fetch_assoc($pengumuman_terbaru)) {
        $count++;
        if ($count > 5)
          break;
      ?>
        <div class="announcement-item">
          <div class="announcement-title">
            <?= htmlspecialchars($row['judul']) ?>
          </div>
          <div class="announcement-meta">
            <span><i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($row['created_at'])) ?></span>
            <span class="announcement-badge">
              <i class="bi bi-tag me-1"></i><?= htmlspecialchars($row['nama_kategori'] ?? 'Umum') ?>
            </span>
          </div>
        </div>
      <?php } ?>

      <?php if ($count == 0) { ?>
        <div class="empty-state">
          <i class="bi bi-inbox"></i>
          <p>Belum ada pengumuman</p>
        </div>
      <?php } ?>
    </div>

  </div>

  <?php include "footer.php"; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>