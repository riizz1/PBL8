<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar Glass Dropdown</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

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

        .nav-link i {
            font-size: 1.4rem;
            margin-bottom: 4px;
        }

        .nav-link:hover {
            color: #0d6efd !important;
        }

        /* 🔹 Dropdown default (sembunyi dulu) */
        .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(5px);
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 15px 20px;
            border-radius: 12px;
            transition: all 0.25s ease;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px 30px;
            min-width: 300px;
        }

        /* 🔹 Saat aktif (hover / klik) */
        .dropdown.show .dropdown-menu,
        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            font-weight: 500;
            color: #333;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            color: #0d6efd;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
        }

        /* 🔹 Dropdown toggle: ikon di atas teks, panah di kanan teks */
        .nav-link.dropdown-toggle {
            display: flex;
            flex-direction: column; /* ikon di atas teks */
            align-items: center;
            justify-content: center;
            gap: 4px;
            position: relative;
        }

        .nav-link .text-arrow {
            display: flex;
            flex-direction: row; /* teks & panah sejajar */
            align-items: center;
            gap: 4px;
        }

        .dropdown-arrow {
            font-size: 0.9rem;
            transition: transform 0.2s ease;
        }

        .dropdown.show .dropdown-arrow {
            transform: rotate(180deg);
        }

        .dropdown-toggle::after {
            display: none !important;
        }

        @media (max-width: 768px) {
            .navbar-nav.flex-row {
                flex-direction: column;
                text-align: center;
            }

            .dropdown-menu {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container d-flex align-items-center justify-content-between">

            <div class="d-flex align-items-center">
                <a class="navbar-brand fw-bold me-3" href="#">
                    <img src="../assets/img/logopolibatam.png" alt="Logo">
                </a>

                <ul class="navbar-nav flex-row">
                    <li class="nav-item me-3">
                        <a class="nav-link" href="#">
                            <i class="bi bi-house-door"></i>
                            Dashboard
                        </a>
                    </li>

                    <!-- 🔹 Dropdown kategori -->
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle" href="#" id="kategoriDropdown" role="button">
                            <i class="bi bi-grid-3x3-gap"></i>
                            <span class="text-arrow">
                                Kategori
                                <i class="bi bi-caret-down-fill dropdown-arrow"></i>
                            </span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="kategoriDropdown">
                            <li><a class="dropdown-item" href="#">Jadwal Akademik</a></li>
                            <li><a class="dropdown-item" href="#">Perkuliahan</a></li>
                            <li><a class="dropdown-item" href="#">Ujian</a></li>
                            <li><a class="dropdown-item" href="#">Beasiswa</a></li>
                            <li><a class="dropdown-item" href="#">Kegiatan</a></li>
                            <li><a class="dropdown-item" href="#">Magang & Karir</a></li>
                        </ul>
                    </li>

                </ul>
            </div>

            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="bi bi-bell fs-5"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="bi bi-person-circle fs-4"></i></a>
                </li>
            </ul>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const dropdown = document.querySelector('.nav-item.dropdown');
        const link = dropdown.querySelector('.nav-link');
        const menu = dropdown.querySelector('.dropdown-menu');

        // Toggle on click
        link.addEventListener('click', (e) => {
            e.preventDefault();
            dropdown.classList.toggle('show');
        });

        // Close when mouse leaves dropdown
        dropdown.addEventListener('mouseleave', () => {
            dropdown.classList.remove('show');
        });
    </script>
</body>

</html>
