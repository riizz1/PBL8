<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Pengumuman</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
body {
    background-color: #f5f7fa;
}

.main-content {
    padding: 40px;
}

.dashboard-box {
    background-color: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
}
</style>
</head>

<body class="dashboard-page">
  <?php
  include("header.php");
  ?>

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
  <?php
  include("footer.php");
  ?>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>