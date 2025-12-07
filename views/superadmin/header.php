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
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      padding: 0 1px;
      /* disamakan dengan admin */
      padding-top: 10px;
      padding-bottom: 10px;
      transition: all 0.3s ease;
    }

    .navbar-brand img {
      height: 55px;
      transition: transform 0.3s ease;
    }

    .navbar-brand img:hover {
      transform: scale(1.05);
    }

    .nav-link {
      color: #333 !important;
      display: flex;
      flex-direction: column;
      align-items: center;
      font-weight: 600;
      font-size: 0.9rem;
      transition: all 0.3s ease;
      position: relative;
      padding: 12px 14px;
      border-radius: 8px;
    }

    .nav-link svg {
      width: 24px;
      height: 24px;
      margin-bottom: 4px;
      transition: transform 0.3s ease;
    }

    .nav-link:hover {
      color: #667eea !important;
      transform: translateY(-2px);
      background: rgba(102, 126, 234, 0.1);
    }

    .nav-link:hover svg {
      transform: scale(1.1);
    }

    /* Active state */
    .nav-link.active {
      color: #fff !important;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
      position: relative;
    }

    .nav-link.active::before {
      content: '';
      position: absolute;
      bottom: -2px;
      left: 50%;
      transform: translateX(-50%);
      width: 40%;
      height: 3px;
      background: #fff;
      border-radius: 2px;
      animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
      from {
        width: 0%;
        opacity: 0;
      }

      to {
        width: 40%;
        opacity: 1;
      }
    }

    .nav-link.active svg {
      filter: brightness(0) invert(1);
      transform: scale(1.1);
    }

    /* Dropdown custom */
    .dropdown-menu {
      right: 0;
      left: auto;
      max-width: 90vw;
      word-wrap: break-word;
      background: rgba(0, 0, 0, 0.85);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 8px;
      transition: opacity 0.2s ease, transform 0.2s ease;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }

    .dropdown-item {
      color: #fff;
      transition: all 0.2s ease;
      padding: 10px 20px;
      border-radius: 6px;
      margin: 4px 8px;
    }

    .dropdown-item:hover {
      background-color: #dc3545;
      color: #fff;
      transform: translateX(5px);
    }

    .dropdown-menu.show {
      display: block;
      opacity: 1;
      transform: translateY(0);
    }

    /* Bell icon */
    .nav-link[href="#"]:has([data-lucide="bell"]) {
      position: relative;
    }

    .nav-link[href="#"]:has([data-lucide="bell"])::after {
      content: '';
      position: absolute;
      top: 8px;
      right: 8px;
      width: 8px;
      height: 8px;
      background: #dc3545;
      border-radius: 50%;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {

      0%,
      100% {
        opacity: 1;
        transform: scale(1);
      }

      50% {
        opacity: 0.5;
        transform: scale(1.2);
      }
    }

    /* Profile dropdown icon */
    #profileDropdown {
      cursor: pointer;
    }

    #profileDropdown svg {
      transition: transform 0.3s ease;
    }

    #profileDropdown:hover svg {
      transform: rotate(10deg) scale(1.1);
    }

    /* Tombol hamburger */
    .navbar-toggler {
      border: none;
      outline: none;
      transition: transform 0.3s ease;
    }

    .navbar-toggler:hover {
      transform: scale(1.1);
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

      .nav-link.active::before {
        left: 0;
        transform: translateX(0);
        width: 3px;
        height: 100%;
        bottom: 0;
      }
    }
  </style>

</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container d-flex align-items-center justify-content-between">

      <!-- Brand -->
      <a class="navbar-brand fw-bold me-3" href="dashboard.php">
        <img src="../../public/assets/img/logopolibatam.png" alt="Logo">
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
            <a class="nav-link" href="dashboard.php" data-page="dashboard">
              <i data-lucide="home"></i>
              Dashboard
            </a>
          </li>
          <li class="nav-item me-3">
            <a class="nav-link" href="dosen.php" data-page="dosen">
              <i data-lucide="megaphone"></i>
              Dosen
            </a>
          </li>
        </ul>

        <!-- Icon kanan -->
        <ul class="navbar-nav align-items-center right-icons">
          <li class="nav-item">
            <a class="nav-link" href="#" title="Notifikasi"><i data-lucide="bell"></i></a>
          </li>

          <!-- Dropdown Profile -->
          <li class="nav-item dropdown">
            <a class="nav-link" href="#" id="profileDropdown" role="button" title="Profile">
              <i data-lucide="user"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
              <li><a class="dropdown-item" href="../../app/controllers/auth/logout.php">Log Out</a></li>
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

    // ===== ACTIVE STATE OTOMATIS =====
    // Deteksi halaman saat ini dan beri highlight
    function setActivePage() {
      // Dapatkan nama file halaman saat ini (misal: dashboard.php, pengumuman.php)
      const currentPage = window.location.pathname.split('/').pop().replace('.php', '');

      // Hapus semua class active
      document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
      });

      // Tambahkan class active ke link yang sesuai
      const activeLink = document.querySelector(`.nav-link[data-page="${currentPage}"]`);
      if (activeLink) {
        activeLink.classList.add('active');
      }
    }

    // Jalankan saat halaman dimuat
    setActivePage();

    // Optional: Update active state saat navigasi (untuk SPA)
    window.addEventListener('popstate', setActivePage);
  </script>
</body>

</html>