<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mahasiswa - Dashboard</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f5f5f5;
      font-family: 'Segoe UI', sans-serif;
    }

     /* Tambahan styling */
  #kampusCarousel {
    max-width: 95%; /* agar tidak menyentuh tepi layar */
    margin: 0 auto; /* center horizontal */
  }

  .carousel-inner img {
    object-fit: cover;
    height: 420px; /* tinggi proporsional */
  }

  .carousel-control-prev-icon,
  .carousel-control-next-icon {
    filter: invert(1); /* ubah arrow jadi putih agar kontras */
    width: 2.5rem;
    height: 2.5rem;
  }

  .carousel-control-prev,
  .carousel-control-next {
    width: 8%; /* arrow tetap di dalam area gambar */
  }

    .pengumuman-box {
      background-color: #d9d9d9;
      border-radius: 10px;
      padding: 40px 20px;
      text-align: center;
      position: relative;
    }

    .arrow-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background-color: transparent;
      border: none;
      font-size: 1.5rem;
    }

    .arrow-left {
      left: 10px;
    }

    .arrow-right {
      right: 10px;
    }

    .footer {
      background-color: #222;
      color: #fff;
      padding: 20px 0;
      text-align: center;
      margin-top: 40px;
    }

    .social-icons i {
      font-size: 1.3rem;
      margin: 0 10px;
      color: white;
    }

    .social-icons i:hover {
      color: #0d6efd;
    }
  </style>
</head>

<body>

  <?php include 'header.php'; ?>

  <!-- 🔹 Carousel -->
<div id="kampusCarousel" class="carousel slide my-4" data-bs-ride="carousel">
  <div class="carousel-inner rounded-4 overflow-hidden mx-3 shadow-sm">
    <div class="carousel-item active">
      <img src="../assets/img/polibatam.jpg" class="d-block w-100" alt="Kampus">
    </div>
    <div class="carousel-item">
      <img src="../assets/img/polibatam.jpg" class="d-block w-100" alt="Kampus 2">
    </div>
  </div>

  <!-- Tombol panah kiri dan kanan -->
  <button class="carousel-control-prev" type="button" data-bs-target="#kampusCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#kampusCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>


  <!-- 🔹 Pengumuman Section -->
  <div class="container mt-4">
    <h4 class="fw-bold mb-3">Pengumuman</h4>
    <div class="pengumuman-box">
      <button class="arrow-btn arrow-left"><i class="bi bi-chevron-left"></i></button>
      <p>Tidak ada pengumuman terbaru saat ini.</p>
      <button class="arrow-btn arrow-right"><i class="bi bi-chevron-right"></i></button>
    </div>
  </div>

  <!-- 🔹 Footer -->
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