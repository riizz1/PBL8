<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mahasiswa - Dashboard</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
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

/* Pengumuman box di dashboard */
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
    cursor: pointer;
}

.arrow-left {
    left: 10px;
}

.arrow-right {
    right: 10px;
}

/* STATS CARDS */
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

.stat-box {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.gap-3 {
    gap: 1rem;
}

/* Responsive */
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

  <!-- 🔹 Carousel Kampus -->
  <div id="kampusCarousel" class="carousel slide my-4" data-bs-ride="carousel">
    <div class="carousel-inner rounded-4 overflow-hidden mx-3 shadow-sm">
      <div class="carousel-item active">
          <img src="/pbl8/PBL8/public/assets/img/polibatam.jpg" class="d-block w-100" alt="Kampus 2">
      </div>
      <div class="carousel-item">
        <img src="/pbl8/PBL8/public/assets/img/polibatam.jpg" class="d-block w-100" alt="Kampus 2">
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

  <!-- 🔹 Pengumuman -->
  <div class="container mt-4">
    <h4 class="fw-bold mb-3 text-center">Pengumuman</h4>

    <div id="pengumumanCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
      <div class="carousel-inner position-relative rounded-4 shadow-sm mx-auto" style="background-color:#f8f9fa; max-width:900px; min-height:130px;">

        <!-- Slide 1 -->
        <div class="carousel-item active">
          <div class="d-flex align-items-center justify-content-center text-center p-4" style="height:130px;">
            <p class="m-0 fs-5 fw-semibold" style="white-space: normal; line-height: 1.5;">
              📢 <strong>Beasiswa PPA 2025</strong> kini dibuka hingga <strong>10 November 2025</strong>.<br>
              Segera daftar melalui portal kemahasiswaan Polibatam.
            </p>
          </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item">
          <div class="d-flex align-items-center justify-content-center text-center p-4" style="height:130px;">
            <p class="m-0 fs-5 fw-semibold" style="white-space: normal; line-height: 1.5;">
              📅 <strong>Pengisian KRS</strong> dimulai tanggal <strong>28 Oktober – 3 November 2025</strong>.<br>
              Pastikan seluruh mata kuliah sesuai bimbingan dosen PA.
            </p>
          </div>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item">
          <div class="d-flex align-items-center justify-content-center text-center p-4" style="height:130px;">
            <p class="m-0 fs-5 fw-semibold" style="white-space: normal; line-height: 1.5;">
              🎓 <strong>Pendaftaran Wisuda</strong> angkatan 2025 telah dibuka!<br>
              Cek syarat dan formulir di laman akademik Polibatam.
            </p>
          </div>
        </div>

        <!-- Slide 4 -->
        <div class="carousel-item">
          <div class="d-flex align-items-center justify-content-center text-center p-4" style="height:130px;">
            <p class="m-0 fs-5 fw-semibold" style="white-space: normal; line-height: 1.5;">
              🧠 <strong>Seminar AI dan IoT</strong> akan diadakan pada 30 Oktober 2025 di Aula Utama.<br>
              Gratis untuk 100 peserta pertama!
            </p>
          </div>
        </div>

        <!-- Tombol navigasi dalam kotak -->
        <button class="carousel-control-prev" type="button" data-bs-target="#pengumumanCarousel" data-bs-slide="prev" style="width: 5%;">
          <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
          <span class="visually-hidden">Sebelumnya</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#pengumumanCarousel" data-bs-slide="next" style="width: 5%;">
          <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
          <span class="visually-hidden">Berikutnya</span>
        </button>
      </div>
    </div>
  </div>

  <!-- 🔹 Footer -->
<?php
 include("footer.php")
?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
