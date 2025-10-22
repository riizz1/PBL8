<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Pengumuman</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f5f7fa;
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
    }

    /* Navbar */
    .navbar {
      background-color: #d9d9d9;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 8px 40px;
    }

    .navbar-left {
      display: flex;
      align-items: center;
      gap: 40px;
    }

    .navbar-brand img {
      height: 40px;
    }

    /* Menu */
    .nav-menu {
      display: flex;
      gap: 30px;
      align-items: center;
    }

    .nav-item {
      text-align: center;
    }

    .nav-item a {
      text-decoration: none;
      color: #333;
      font-size: 0.9rem;
      transition: color 0.3s;
    }

    .nav-item a:hover,
    .nav-item.active a {
      color: #0d6efd;
    }

    .nav-item i {
      display: block;
      font-size: 1.3rem;
      margin-bottom: 3px;
    }

    /* Profil kanan */
    .user-icon {
      font-size: 1.8rem;
      color: #555;
      cursor: pointer;
    }

    /* Konten utama */
    .main-content {
      padding: 40px;
    }

    .dashboard-box {
      background-color: #fff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .footer {
      background-color: #222;
      color: #fff;
      text-align: center;
      padding: 15px 0;
      margin-top: 50px;
    }

    .social-icons i {
      font-size: 1.3rem;
      margin: 0 10px;
      color: white;
      cursor: pointer;
    }

    .social-icons i:hover {
      color: #0d6efd;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
  <div class="navbar-left">
    <div class="navbar-brand">
      <img src="logopolibatam.png" alt="Logo">
    </div>

    <div class="nav-menu">
      <div class="nav-item active">
        <a href="#"><i class="bi bi-house-door"></i>Dashboard</a>
      </div>
      <div class="nav-item">
        <a href="#"><i class="bi bi-megaphone"></i>Pengumuman</a>
      </div>
      <div class="nav-item">
        <a href="#"><i class="bi bi-star"></i>Kategori</a>
      </div>
      <div class="nav-item">
        <a href="#"><i class="bi bi-people"></i>Mahasiswa</a>
      </div>
      <div class="nav-item">
        <a href="#"><i class="bi bi-file-earmark-bar-graph"></i>Laporan</a>
      </div>
    </div>
  </div>

  <div class="user-icon">
    <i class="bi bi-person-circle"></i>
  </div>
</nav>

<!-- Main Content -->
<div class="main-content">
  <h2 class="fw-bold mb-4">Dashboard</h2>

  <!-- Statistik -->
  <div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
      <div class="dashboard-box text-center">
        <i class="bi bi-megaphone-fill fs-2 text-primary"></i>
        <h6 class="mt-2">Total Pengumuman</h6>
        <h3 class="fw-bold text-primary">120</h3>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="dashboard-box text-center">
        <i class="bi bi-broadcast-pin fs-2 text-success"></i>
        <h6 class="mt-2">Pengumuman Aktif</h6>
        <h3 class="fw-bold text-success">45</h3>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="dashboard-box text-center">
        <i class="bi bi-award-fill fs-2 text-info"></i>
        <h6 class="mt-2">Beasiswa Diposting</h6>
        <h3 class="fw-bold text-info">8</h3>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="dashboard-box text-center">
        <i class="bi bi-journal-text fs-2 text-warning"></i>
        <h6 class="mt-2">Ujian Diposting</h6>
        <h3 class="fw-bold text-warning">12</h3>
      </div>
    </div>
  </div>

  <!-- Pengumuman Terbaru -->
  <div class="dashboard-box">
    <h5 class="fw-bold mb-3"><i class="bi bi-bell-fill text-danger me-2"></i>Pengumuman Terbaru</h5>
    <ul class="list-group">
      <li class="list-group-item">
        <i class="bi bi-calendar-event text-primary me-2"></i>[20 Sept] Jadwal UAS Semester Ganjil
      </li>
      <li class="list-group-item">
        <i class="bi bi-award text-success me-2"></i>[19 Sept] Pendaftaran Beasiswa Unggulan
      </li>
      <li class="list-group-item">
        <i class="bi bi-building text-warning me-2"></i>[18 Sept] Perubahan Ruangan Kelas B201
      </li>
    </ul>
  </div>
</div>

<!-- Footer -->
<div class="footer">
  <p>© 2025 PBL IFPagi1-8. All rights reserved</p>
  <div class="social-icons">
    <i class="bi bi-facebook"></i>
    <i class="bi bi-instagram"></i>
    <i class="bi bi-twitter"></i>
    <i class="bi bi-youtube"></i>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
