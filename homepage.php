<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman Akademik Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        /* Atur tinggi carousel agar berbentuk persegi panjang */
        .carousel-item img {
            height: 200px;
            /* ubah sesuai kebutuhan, misal 300px */
            object-fit: cover;
        }

        .card {
            overflow: hidden;
        }

        .welcome-section {
            background-color: #f8f9fa;
            padding: 2rem;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="homepage.php">
                <img src="logopolibatam.png" alt="Logo" style="height: 40px; width: auto;"
                    class="d-inline-block align-text-top">
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">

                <!-- Card 1: Carousel -->
                <div class="card shadow mb-4 border-0 overflow-hidden rounded-4">
                    <div class="card-body p-0">
                        <div id="buildingCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner rounded-4 overflow-hidden" style="height: 250px;">
                                <div class="carousel-item active">
                                    <img src="backgroundpolibatam.jpg" class="d-block w-100 h-100 object-fit-cover"
                                        alt="Gedung Kampus 1">
                                </div>
                                <div class="carousel-item">
                                    <img src="backgroundpolibatam.jpg" class="d-block w-100 h-100 object-fit-cover"
                                        alt="Gedung Kampus 2">
                                </div>
                                <div class="carousel-item">
                                    <img src="backgroundpolibatam.jpg" class="d-block w-100 h-100 object-fit-cover"
                                        alt="Gedung Kampus 3">
                                </div>
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#buildingCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#buildingCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>


                <!-- Card 2: Welcome Text -->
                <div class="card shadow text-center rounded-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Selamat Datang di website pengumuman akademik online</h5>
                        <button type="button" class="btn btn-success btn-lg">Login</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-auto">
  <div class="container">
    <p class="mb-3">&copy; 2025 Politeknik Negeri Batam. All rights reserved.</p>
    <div class="d-flex justify-content-center gap-4">
      <a href="#" class="text-white fs-4" title="Facebook">
        <i class="bi bi-facebook"></i>
      </a>
      <a href="#" class="text-white fs-4" title="Instagram">
        <i class="bi bi-instagram"></i>
      </a>
      <a href="#" class="text-white fs-4" title="Twitter">
        <i class="bi bi-twitter"></i>
      </a>
      <a href="#" class="text-white fs-4" title="YouTube">
        <i class="bi bi-youtube"></i>
      </a>
    </div>
  </div>
</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>