<?php
session_start();

// Cek hanya superadmin yang bisa akses
if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'superadmin') {
    header("Location: ../views/auth/login.php");
    exit();
}

// Load controller
require_once __DIR__ . '/../../app/controllers/superadmin/dashboard_controller.php';

$dashboardController = new DashboardController();

// Get data dari controller
$stats = $dashboardController->getStatistics();
$categoryData = $dashboardController->getAnnouncementsByCategory();
$recentAnnouncements = $dashboardController->getRecentAnnouncements(5);
$monthlyTrend = $dashboardController->getMonthlyTrend();
$recentUsers = $dashboardController->getRecentAdmin(5);

// Prepare data untuk Chart.js
$categoryLabels = array_column($categoryData, 'nama_kategori');
$categoryValues = array_column($categoryData, 'jumlah_pengumuman');

$trendLabels = array_column($monthlyTrend, 'bulan');
$trendValues = array_column($monthlyTrend, 'jumlah');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin Dashboard | Polibatam</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .stat-card {
            border-radius: 10px;
            padding: 20px;
            color: white;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        
        .stat-card i {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        
        .stat-card h3 {
            font-size: 2rem;
            font-weight: bold;
            margin: 10px 0 5px 0;
        }
        
        .stat-card p {
            margin: 0;
            opacity: 0.9;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .bg-gradient-success {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .bg-gradient-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .bg-gradient-warning {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 2px solid #f0f0f0;
            font-weight: 600;
            color: #333;
        }
        
        .table thead th {
            background-color: #667eea;
            color: white;
            border: none;
        }
        
        .badge-custom {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.85rem;
        }
        
        .quick-action-btn {
            border-radius: 8px;
            padding: 15px;
            transition: all 0.3s ease;
        }
        
        .quick-action-btn:hover {
            transform: scale(1.05);
        }
        
        .activity-item {
            padding: 10px;
            border-left: 3px solid #667eea;
            margin-bottom: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .activity-item:hover {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container-fluid my-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Dashboard Superadmin</h2>
                <p class="text-muted mb-0">Selamat datang, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
            </div>
            <div class="text-muted">
                <i class="fas fa-calendar-alt me-2"></i>
                <?= date('d F Y') ?>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card bg-gradient-primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1">Total Dosen</p>
                            <h3><?= number_format($stats['total_dosen']) ?></h3>
                        </div>
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="stat-card bg-gradient-success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1">Total Mahasiswa</p>
                            <h3><?= number_format($stats['total_mahasiswa']) ?></h3>
                        </div>
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="stat-card bg-gradient-info">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1">Total Pengumuman</p>
                            <h3><?= number_format($stats['total_pengumuman']) ?></h3>
                        </div>
                        <i class="fas fa-bullhorn"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="stat-card bg-gradient-warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1">Total Kategori</p>
                            <h3><?= number_format($stats['total_kategori']) ?></h3>
                        </div>
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-bolt me-2"></i>Quick Actions
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="kelola_dosen.php" class="btn btn-primary w-100 quick-action-btn">
                            <i class="fas fa-user-plus fa-2x mb-2 d-block"></i>
                            Tambah Dosen
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="kelola_mahasiswa.php" class="btn btn-success w-100 quick-action-btn">
                            <i class="fas fa-users fa-2x mb-2 d-block"></i>
                            Kelola Mahasiswa
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="kelola_pengumuman.php" class="btn btn-info w-100 quick-action-btn">
                            <i class="fas fa-clipboard-list fa-2x mb-2 d-block"></i>
                            Kelola Pengumuman
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="kelola_kategori.php" class="btn btn-warning w-100 quick-action-btn">
                            <i class="fas fa-folder fa-2x mb-2 d-block"></i>
                            Kelola Kategori
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <!-- Category Chart -->
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-chart-pie me-2"></i>Pengumuman Per Kategori
                    </div>
                    <div class="card-body">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Trend Chart -->
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-chart-line me-2"></i>Trend Pengumuman (6 Bulan Terakhir)
                    </div>
                    <div class="card-body">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Row -->
        <div class="row">
            <!-- Category Report Table -->
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-table me-2"></i>Laporan Per Kategori
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kategori</th>
                                        <th class="text-center">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($categoryData)): ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">
                                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                                <p class="mb-0">Belum ada data kategori</p>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach($categoryData as $index => $category): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td>
                                                    <i class="fas fa-tag text-primary me-2"></i>
                                                    <?= htmlspecialchars($category['nama_kategori']) ?>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary badge-custom">
                                                        <?= $category['jumlah_pengumuman'] ?> Pengumuman
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-clock me-2"></i>Pengumuman Terbaru</span>
                        <a href="kelola_pengumuman.php" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        <?php if(empty($recentAnnouncements)): ?>
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <p class="mb-0">Belum ada pengumuman</p>
                            </div>
                        <?php else: ?>
                            <?php foreach($recentAnnouncements as $announcement): ?>
                                <div class="activity-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?= htmlspecialchars($announcement['judul']) ?></h6>
                                            <small class="text-muted">
                                                <i class="fas fa-tag me-1"></i>
                                                <?= htmlspecialchars($announcement['nama_kategori'] ?? 'Umum') ?>
                                            </small>
                                        </div>
                                        <small class="text-muted ms-2">
                                            <?= date('d M Y', strtotime($announcement['created_at'])) ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Users Card -->
                <div class="card mt-3">
                    <div class="card-header">
                        <i class="fas fa-users me-2"></i>User Terbaru
                    </div>
                    <div class="card-body">
                        <?php if(empty($recentUsers)): ?>
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-user-slash fa-2x mb-2"></i>
                                <p class="mb-0">Belum ada user baru</p>
                            </div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach($recentUsers as $user): ?>
                                    <div class="list-group-item px-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-user-circle text-primary me-2"></i>
                                                <strong><?= htmlspecialchars($user['username']) ?></strong>
                                                <span class="badge bg-secondary ms-2">
                                                    <?= htmlspecialchars($user['role_name']) ?>
                                                </span>
                                            </div>
                                            <small class="text-muted">
                                                <?= date('d M Y', strtotime($user['created_at'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart.js Implementation -->
    <script>
        // Category Pie Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($categoryLabels) ?>,
                datasets: [{
                    label: 'Jumlah Pengumuman',
                    data: <?= json_encode($categoryValues) ?>,
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(245, 87, 108, 0.8)',
                        'rgba(79, 172, 254, 0.8)',
                        'rgba(250, 112, 154, 0.8)',
                        'rgba(118, 75, 162, 0.8)',
                        'rgba(254, 225, 64, 0.8)',
                        'rgba(0, 242, 254, 0.8)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: false
                    }
                }
            }
        });

        // Trend Line Chart
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        const trendChart = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode($trendLabels) ?>,
                datasets: [{
                    label: 'Jumlah Pengumuman',
                    data: <?= json_encode($trendValues) ?>,
                    backgroundColor: 'rgba(102, 126, 234, 0.2)',
                    borderColor: 'rgba(102, 126, 234, 1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: 'rgba(102, 126, 234, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>