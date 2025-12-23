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
    /* ================= DASHBOARD ================= */
    :root {
      --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --success-gradient: linear-gradient(135deg, #43cea2 0%, #185a9d 100%);
    }

    body {
      background: #ffffff;
      /* Diubah menjadi putih */
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .main-content {
      padding: 40px;
      animation: fadeIn 0.6s ease-in;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
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

    /* ===== STAT CARD ===== */
    .stat-card {
      border-radius: 16px;
      padding: 20px;
      color: #fff;
      position: relative;
      overflow: hidden;
      transition: all .4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      cursor: pointer;
      border: none;
      box-shadow: 0 10px 30px rgba(0, 0, 0, .12);
    }

    .stat-card:hover {
      transform: translateY(-10px) scale(1.02);
      box-shadow: 0 20px 40px rgba(0, 0, 0, .2);
    }

    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(255, 255, 255, .1);
      opacity: 0;
      transition: opacity .3s ease;
    }

    .stat-card:hover::before {
      opacity: 1;
    }

    .stat-card::after {
      content: '';
      position: absolute;
      top: -50px;
      right: -50px;
      width: 150px;
      height: 150px;
      background: rgba(255, 255, 255, .15);
      border-radius: 50%;
      transition: all .5s ease;
    }

    .stat-card:hover::after {
      transform: scale(1.5);
    }

    .stat-icon {
      width: 50px;
      height: 50px;
      background: rgba(255, 255, 255, .2);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      backdrop-filter: blur(10px);
    }

    .stat-number {
      font-size: 1.8rem;
      font-weight: 700;
      margin: 10px 0 5px 0;
      line-height: 1;
    }

    .stat-label {
      font-size: 0.85rem;
      opacity: 0.95;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .stat-trend {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 0.75rem;
      margin-top: 8px;
      padding: 3px 8px;
      background: rgba(255, 255, 255, .25);
      border-radius: 20px;
      backdrop-filter: blur(10px);
    }

    /* Warna Gradients */
    .bg-pengumuman {
      background: var(--primary-gradient);
    }

    .bg-kategori {
      background: var(--success-gradient);
    }

    /* ===== PENGUMUMAN TERBARU ===== */
    .dashboard-box {
      background: #fff;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, .08);
      animation: slideUp 0.6s ease-out 0.2s both;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .dashboard-box h5 {
      font-weight: 700;
      color: #2d3748;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .dashboard-box h5::before {
      content: '';
      width: 4px;
      height: 24px;
      background: var(--primary-gradient);
      border-radius: 4px;
    }

    .announcement-item {
      padding: 18px;
      border-radius: 12px;
      border: 1px solid #e2e8f0;
      margin-bottom: 12px;
      transition: all .3s ease;
      background: #fff;
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .announcement-item:hover {
      border-color: #cbd5e0;
      box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
      transform: translateX(5px);
    }

    .announcement-icon {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
      flex-shrink: 0;
    }

    .icon-beasiswa {
      background: linear-gradient(135deg, #43cea220, #185a9d20);
      color: #43cea2;
    }

    .icon-ujian {
      background: linear-gradient(135deg, #667eea20, #764ba220);
      color: #667eea;
    }

    .icon-lainnya {
      background: linear-gradient(135deg, #a8a8a820, #71717120);
      color: #6b7280;
    }

    .announcement-content {
      flex: 1;
    }

    .announcement-date {
      font-size: 0.8rem;
      color: #718096;
      margin-bottom: 5px;
    }

    .announcement-title {
      font-weight: 600;
      color: #2d3748;
      margin: 0;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
      .main-content {
        padding: 20px;
      }

      .stat-number {
        font-size: 2rem;
      }

      .dashboard-header h2 {
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
    </div>

    <!-- ===== STATISTIK ===== -->
    <div class="row g-4 mb-4">

      <div class="col-md-6">
        <div class="stat-card bg-pengumuman">
          <div class="stat-icon">
            <i class="bi bi-megaphone-fill"></i>
          </div>
          <div class="stat-number"><?= $total_pengumuman ?></div>
          <div class="stat-label">Total Pengumuman</div>
          <div class="stat-trend">
            <i class="bi bi-arrow-up-short"></i>
            <span>Aktif</span>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="stat-card bg-kategori">
          <div class="stat-icon">
            <i class="bi bi-tags-fill"></i>
          </div>
          <div class="stat-number"><?= $total_kategori ?></div>
          <div class="stat-label">Total Kategori</div>
          <div class="stat-trend">
            <i class="bi bi-arrow-up-short"></i>
            <span>Tersedia</span>
          </div>
        </div>
      </div>

    </div>

    <!-- ===== PENGUMUMAN TERBARU ===== -->
    <div class="dashboard-box">
      <h5>
        Pengumuman Terbaru
        <span class="badge bg-primary ms-auto" style="font-size: 0.75rem;">
          <?= mysqli_num_rows($pengumuman_terbaru) ?> Items
        </span>
      </h5>

      <?php
      $count = 0;
      while ($row = mysqli_fetch_assoc($pengumuman_terbaru)) {
        $count++;
        if ($count > 5)
          break; // Batasi hanya 5 pengumuman
      ?>
        <div class="announcement-item">
          <div class="announcement-icon 
          <?php
          if ($row['kategori_id'] == 2)
            echo 'icon-ujian';
          else if ($row['kategori_id'] == 1)
            echo 'icon-beasiswa';
          else
            echo 'icon-lainnya';
          ?>">
            <i class="
            <?php
            if ($row['kategori_id'] == 2)
              echo 'bi bi-calendar-event';
            else if ($row['kategori_id'] == 1)
              echo 'bi bi-award';
            else
              echo 'bi bi-megaphone';
            ?>"></i>
          </div>

          <div class="announcement-content">
            <div class="announcement-date">
              <i class="bi bi-clock me-1"></i>
              <?= date('d M Y, H:i', strtotime($row['created_at'])) ?>
            </div>
            <div class="announcement-title">
              <?= htmlspecialchars($row['judul']) ?>
            </div>
          </div>
        </div>
      <?php } ?>

      <?php if ($count == 0) { ?>
        <div class="text-center py-5">
          <i class="bi bi-inbox" style="font-size: 3rem; color: #cbd5e0;"></i>
          <p class="text-muted mt-3">Belum ada pengumuman terbaru</p>
        </div>
      <?php } ?>
    </div>

  </div>

  <?php include "footer.php"; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Animasi counter untuk statistik
    document.addEventListener('DOMContentLoaded', function() {
      const statNumbers = document.querySelectorAll('.stat-number');

      statNumbers.forEach(stat => {
        const target = parseInt(stat.textContent);
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
          current += increment;
          if (current >= target) {
            stat.textContent = target;
            clearInterval(timer);
          } else {
            stat.textContent = Math.floor(current);
          }
        }, 20);
      });
    });
  </script>

</body>

</html>