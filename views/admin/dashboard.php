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

  // Hitung total pengumuman
  $total_pengumuman = mysqli_fetch_assoc(
    mysqli_query($config, "SELECT COUNT(*) AS total FROM pengumuman")
  )['total'];

  // Hitung beasiswa
  $total_beasiswa = mysqli_fetch_assoc(
    mysqli_query($config, "SELECT COUNT(*) AS total FROM pengumuman WHERE kategori='beasiswa'")
  )['total'];

  // Hitung ujian
  $total_ujian = mysqli_fetch_assoc(
    mysqli_query($config, "SELECT COUNT(*) AS total FROM pengumuman WHERE kategori='ujian'")
  )['total'];
  ?>

  <!-- Main Content -->
  <div class="main-content">
    <h2 class="fw-bold mb-4">Dashboard</h2>

    <!-- Statistik -->
    <div class="row g-3 mb-4">
      <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
          <div class="dashboard-box text-center">
            <i class="bi bi-megaphone-fill fs-2 text-primary"></i>
            <h6 class="mt-2">Total Pengumuman</h6>
            <h3 class="fw-bold text-primary"><?php echo $total_pengumuman; ?></h3>
          </div>
        </div>

        <div class="col-md-3 col-sm-6">
          <div class="dashboard-box text-center">
            <i class="bi bi-award-fill fs-2 text-info"></i>
            <h6 class="mt-2">Beasiswa Diposting</h6>
            <h3 class="fw-bold text-info"><?php echo $total_beasiswa; ?></h3>
          </div>
        </div>

        <div class="col-md-3 col-sm-6">
          <div class="dashboard-box text-center">
            <i class="bi bi-journal-text fs-2 text-warning"></i>
            <h6 class="mt-2">Ujian Diposting</h6>
            <h3 class="fw-bold text-warning"><?php echo $total_ujian; ?></h3>
          </div>
        </div>
      </div>

    </div>

    <!-- Pengumuman Terbaru -->
    <ul class="list-group">
      <?php while ($row = mysqli_fetch_assoc($pengumuman_terbaru)) { ?>
        <li class="list-group-item">
          <i class="
                <?php
                if ($row['kategori'] == 'ujian')
                  echo 'bi bi-calendar-event text-primary';
                else if ($row['kategori'] == 'beasiswa')
                  echo 'bi bi-award text-success';
                else
                  echo 'bi bi-building text-warning';
                ?>
            me-2"></i>
          [<?php echo date('d M', strtotime($row['tanggal'])); ?>]
          <?php echo $row['judul']; ?>
        </li>
      <?php } ?>
    </ul>


    <!-- Footer -->
    <?php
    include("footer.php");
    ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>