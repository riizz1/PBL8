<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Navbar Admin</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>

  <style>
    .navbar {
      position: sticky;
      top: 0;
      z-index: 1000;
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(12px) saturate(180%);
      -webkit-backdrop-filter: blur(12px) saturate(180%);
      border-bottom: 1px solid rgba(255, 255, 255, 0.3);
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      padding: 0 5px;
      transition: background 0.3s ease, backdrop-filter 0.3s ease;
    }

    .navbar-brand img {
      height: 55px;
    }

    .nav-link {
      color: #333 !important;
      display: flex;
      flex-direction: column;
      align-items: center;
      font-weight: 600;
      font-size: 0.9rem;
      transition: color 0.3s ease;
    }

    .nav-link svg {
      width: 24px;
      height: 24px;
      margin-bottom: 4px;
    }

    .nav-link:hover {
      color: #0d6efd !important;
    }

    /* Dropdown custom */
    .dropdown-menu {
      right: 0;
      left: auto;
      max-width: 90vw;
      word-wrap: break-word;
      background: rgba(0, 0, 0, 0.6);
      border: none;
      transition: opacity 0.2s ease, transform 0.2s ease;
    }

    .dropdown-item {
      color: #fff;
      transition: background 0.2s;
    }

    .dropdown-item:hover {
      background-color: #dc3545;
      color: #fff;
    }

    /* Smooth dropdown show/hide */
    .dropdown-menu.show {
      display: block;
      opacity: 1;
      transform: translateY(0);
    }

    /* Tombol hamburger */
    .navbar-toggler {
      border: none;
      outline: none;
    }

    .navbar-toggler-icon {
      filter: invert(1);
    }

    /* Saat collapse */
    @media (max-width: 991.98px) {
      .navbar-collapse {
        position: relative;
      }

      .navbar-collapse .navbar-nav.me-auto {
        align-items: flex-start !important;
      }

      /* Bell & Profile tetap kanan atas */
      .navbar-collapse .right-icons {
        position: absolute;
        top: 10px;
        right: 15px;
        display: flex;
        gap: 10px;
      }

      .navbar-collapse.show {
        padding-top: 50px;
      }

      .nav-link {
        flex-direction: row;
        gap: 8px;
        justify-content: flex-start;
      }

      .nav-link svg {
        margin-bottom: 0;
      }
    }
  </style>
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container d-flex align-items-center justify-content-between">

      <!-- Brand -->
      <a class="navbar-brand fw-bold me-3" href="#">
        <img src="../assets/img/logopolibatam.png" alt="Logo">
      </a>

      <!-- Tombol hamburger -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
        aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Isi navbar -->
      <div class="collapse navbar-collapse" id="navbarContent">

        <!-- Menu utama -->
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-flex align-items-center">
          <li class="nav-item me-3">
            <a class="nav-link" href="#">
              <i data-lucide="home"></i>
              Dashboard
            </a>
          </li>
          <li class="nav-item me-3">
            <a class="nav-link" href="#">
              <i data-lucide="megaphone"></i>
              Pengumuman
            </a>
          </li>
          <li class="nav-item me-3">
            <a class="nav-link" href="#">
              <i data-lucide="folder-open"></i>
              Kategori
            </a>
          </li>
          <li class="nav-item me-3">
            <a class="nav-link" href="#">
              <i data-lucide="users"></i>
              Mahasiswa
            </a>
          </li>
          <li class="nav-item me-3">
            <a class="nav-link" href="#">
              <i data-lucide="file-text"></i>
              Laporan
            </a>
          </li>
        </ul>

        <!-- Icon kanan -->
        <ul class="navbar-nav align-items-center right-icons">
          <li class="nav-item">
            <a class="nav-link" href="#"><i data-lucide="bell"></i></a>
          </li>

          <!-- Dropdown Profile -->
          <li class="nav-item dropdown">
            <a class="nav-link" href="#" id="profileDropdown" role="button">
              <i data-lucide="user"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
              <li><a class="dropdown-item" href="#">Log Out</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Toggle dropdown manual
    const profileLink = document.getElementById('profileDropdown');
    const dropdownMenu = profileLink.nextElementSibling;

    profileLink.addEventListener('click', function (e) {
      e.preventDefault();
      dropdownMenu.classList.toggle('show');
    });

    document.addEventListener('click', function (e) {
      if (!profileLink.contains(e.target) && !dropdownMenu.contains(e.target)) {
        dropdownMenu.classList.remove('show');
      }
    });

    // Aktifkan Lucide Icons
    lucide.createIcons();
  </script>
</body>

</html>
