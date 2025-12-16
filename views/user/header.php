<?php
// Pastikan session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Ambil nama dari session
$namaUser = $_SESSION['nama_lengkap'] ?? $_SESSION['username'] ?? 'User';

// Koneksi ke database
require_once '../../config/database.php';

// =============================================================================
// PERBAIKAN 1: Menambahkan fungsi formatTime() versi PHP
// =============================================================================
function formatTime($timestamp)
{
  try {
    $date = new DateTime($timestamp);
    $now = new DateTime();
    $interval = $now->diff($date);

    if ($interval->y > 0) {
      return $interval->y . ' tahun yang lalu';
    } elseif ($interval->m > 0) {
      return $interval->m . ' bulan yang lalu';
    } elseif ($interval->d > 0) {
      return $interval->d . ' hari yang lalu';
    } elseif ($interval->h > 0) {
      return $interval->h . ' jam yang lalu';
    } elseif ($interval->i > 0) {
      return $interval->i . ' menit yang lalu';
    } else {
      return 'Baru saja';
    }
  } catch (Exception $e) {
    return 'Format waktu tidak valid';
  }
}

// =============================================================================
// PERBAIKAN 2: Mengamankan Query dengan Prepared Statements
// =============================================================================
function getNotifications($userId, $limit = 5)
{
  global $koneksi;

  $query = "SELECT * FROM notifications 
                WHERE user_id = ? 
                ORDER BY is_read ASC, created_at DESC 
                LIMIT ?";

  $stmt = mysqli_prepare($koneksi, $query);
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ii", $userId, $limit);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $notifications = [];
    while ($row = mysqli_fetch_assoc($result)) {
      $notifications[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $notifications;
  }
  return [];
}

function countUnreadNotifications($userId)
{
  global $koneksi;

  $query = "SELECT COUNT(*) as count FROM notifications 
                WHERE user_id = ? AND is_read = 0";

  $stmt = mysqli_prepare($koneksi, $query);
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $row['count'] ?? 0;
  }
  return 0;
}

// Ambil user ID dari session (asumsi ada user_id di session)
$userId = $_SESSION['user_id'] ?? 0;

// Ambil notifikasi dan hitung yang belum dibaca
$notifications = getNotifications($userId);
$unreadCount = countUnreadNotifications($userId);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Navbar User</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    /* ... (CSS kamu tidak berubah, jadi saya biarkan seperti semula) ... */
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

    .notification-dropdown {
      width: 350px;
      max-height: 400px;
      overflow-y: auto;
      padding: 0;
    }

    .notification-header {
      padding: 10px 15px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .notification-item {
      padding: 12px 15px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.05);
      transition: background-color 0.2s;
      cursor: pointer;
    }

    .notification-item:hover {
      background-color: rgba(255, 255, 255, 0.05);
    }

    .notification-item.unread {
      background-color: rgba(102, 126, 234, 0.1);
    }

    .notification-title {
      font-weight: 600;
      margin-bottom: 4px;
      color: #fff;
    }

    .notification-message {
      font-size: 0.85rem;
      color: rgba(255, 255, 255, 0.7);
      margin-bottom: 4px;
    }

    .notification-time {
      font-size: 0.75rem;
      color: rgba(255, 255, 255, 0.5);
    }

    .notification-footer {
      padding: 10px 15px;
      text-align: center;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .notification-footer a {
      color: #667eea;
      font-size: 0.85rem;
      text-decoration: none;
    }

    .notification-footer a:hover {
      text-decoration: underline;
    }

    .nav-link[href="#notifications"] {
      position: relative;
    }

    .notification-badge {
      position: absolute;
      top: 8px;
      right: 8px;
      background: #dc3545;
      color: white;
      border-radius: 50%;
      width: 18px;
      height: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.7rem;
      font-weight: bold;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% {
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
      }

      70% {
        box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
      }

      100% {
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
      }
    }

    #profileDropdown {
      cursor: pointer;
    }

    #profileDropdown svg {
      transition: transform 0.3s ease;
    }

    #profileDropdown:hover svg {
      transform: rotate(10deg) scale(1.1);
    }

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

      .navbar-nav .text-dark {
        white-space: nowrap;
        margin-right: 10px;
      }

      .navbar-nav .text-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 700;
      }

      .notification-dropdown {
        width: calc(100vw - 30px);
        max-width: 350px;
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
            <a class="nav-link" href="pengumuman.php" data-page="pengumuman">
              <i data-lucide="megaphone"></i>
              Pengumuman
            </a>
          </li>
        </ul>
        <!-- Icon kanan -->
        <ul class="navbar-nav align-items-center right-icons">
          <!-- Selamat Datang Text -->
          <li class="nav-item me-3 d-none d-lg-block">
            <span class="text-dark fw-semibold" style="font-size: 0.95rem;">
              Selamat Datang, <span class="text-primary"><?= htmlspecialchars($namaUser); ?></span>
            </span>
          </li>
          <!-- Notification Dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link" href="#notifications" id="notificationDropdown" role="button" title="Notifikasi">
              <i data-lucide="bell"></i>
              <?php if ($unreadCount > 0): ?>
                <span class="notification-badge"><?= $unreadCount > 99 ? '99+' : $unreadCount ?></span>
              <?php endif; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown">
              <div class="notification-header">
                <h6 class="mb-0 text-white">Notifikasi</h6>
                <button class="btn btn-sm btn-link text-white p-0" id="markAllRead">Tandai semua dibaca</button>
              </div>
              <div id="notificationList">
                <?php if (empty($notifications)): ?>
                  <div class="notification-item">
                    <p class="text-center text-muted mb-0">Tidak ada notifikasi</p>
                  </div>
                <?php else: ?>
                  <?php foreach ($notifications as $notif): ?>
                    <div class="notification-item <?= $notif['is_read'] == 0 ? 'unread' : '' ?>"
                      data-id="<?= $notif['id'] ?>">
                      <div class="notification-title"><?= htmlspecialchars($notif['title']) ?></div>
                      <div class="notification-message"><?= htmlspecialchars($notif['message']) ?></div>
                      <div class="notification-time"><?= formatTime($notif['created_at']) ?></div>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
              <div class="notification-footer">
                <a href="notifications.php">Lihat semua notifikasi</a>
              </div>
            </ul>
          </li>
          <!-- Dropdown Profile -->
          <li class="nav-item dropdown">
            <a class="nav-link" href="#" id="profileDropdown" role="button" title="Profile">
              <i data-lucide="user"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
              <li><a class="dropdown-item" href="/PBL8/app/controllers/auth/logout.php">Log Out</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // =============================================================================
    // PERBAIKAN 3: Menambahkan BASE_URL untuk path AJAX yang dinamis
    // =============================================================================
    const BASE_URL = '<?php echo rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . '/'; ?>';

    // Toggle dropdown manual
    const profileLink = document.getElementById('profileDropdown');
    const profileDropdown = profileLink.nextElementSibling;
    profileLink.addEventListener('click', function (e) {
      e.preventDefault();
      // Tutup dropdown notifikasi jika terbuka
      const notifDropdown = document.getElementById('notificationDropdown').nextElementSibling;
      if (notifDropdown.classList.contains('show')) {
        notifDropdown.classList.remove('show');
      }
      profileDropdown.classList.toggle('show');
    });

    const notificationLink = document.getElementById('notificationDropdown');
    const notificationDropdown = notificationLink.nextElementSibling;
    notificationLink.addEventListener('click', function (e) {
      e.preventDefault();
      // Tutup dropdown profil jika terbuka
      if (profileDropdown.classList.contains('show')) {
        profileDropdown.classList.remove('show');
      }
      notificationDropdown.classList.toggle('show');
      loadNotifications();
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function (e) {
      if (!profileLink.contains(e.target) && !profileDropdown.contains(e.target)) {
        profileDropdown.classList.remove('show');
      }
      if (!notificationLink.contains(e.target) && !notificationDropdown.contains(e.target)) {
        notificationDropdown.classList.remove('show');
      }
    });

    function markAsRead(notificationId) {
      // PERBAIKAN: Menggunakan BASE_URL
      fetch(BASE_URL + 'app/controllers/notifications/mark_read.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ notification_id: notificationId })
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const notificationItem = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
            if (notificationItem) {
              notificationItem.classList.remove('unread');
            }
            updateNotificationBadge(data.unread_count);
          }
        })
        .catch(error => console.error('Error marking notification as read:', error));
    }

    document.getElementById('markAllRead').addEventListener('click', function () {
      // PERBAIKAN: Menggunakan BASE_URL
      fetch(BASE_URL + 'app/controllers/notifications/mark_all_read.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            document.querySelectorAll('.notification-item.unread').forEach(item => {
              item.classList.remove('unread');
            });
            updateNotificationBadge(0);
          }
        })
        .catch(error => console.error('Error marking all notifications as read:', error));
    });

    function loadNotifications() {
      // PERBAIKAN: Menggunakan BASE_URL
      fetch(BASE_URL + 'app/controllers/notifications/get_notifications.php')
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const notificationList = document.getElementById('notificationList');
            if (data.notifications.length === 0) {
              notificationList.innerHTML = '<div class="notification-item"><p class="text-center text-muted mb-0">Tidak ada notifikasi</p></div>';
            } else {
              let html = '';
              data.notifications.forEach(notif => {
                html += `
                            <div class="notification-item ${notif.is_read == 0 ? 'unread' : ''}" data-id="${notif.id}">
                                <div class="notification-title">${notif.title}</div>
                                <div class="notification-message">${notif.message}</div>
                                <div class="notification-time">${formatTime(notif.created_at)}</div>
                            </div>
                        `;
              });
              notificationList.innerHTML = html;
            }
            updateNotificationBadge(data.unread_count);
            document.querySelectorAll('.notification-item').forEach(item => {
              item.addEventListener('click', function () {
                const notificationId = this.getAttribute('data-id');
                if (notificationId && this.classList.contains('unread')) {
                  markAsRead(notificationId);
                }
              });
            });
          }
        })
        .catch(error => console.error('Error loading notifications:', error));
    }

    function updateNotificationBadge(count) {
      const badge = document.querySelector('.notification-badge');
      if (count > 0) {
        if (!badge) {
          const newBadge = document.createElement('span');
          newBadge.className = 'notification-badge';
          newBadge.textContent = count > 99 ? '99+' : count;
          document.getElementById('notificationDropdown').appendChild(newBadge);
        } else {
          badge.textContent = count > 99 ? '99+' : count;
        }
      } else if (badge) {
        badge.remove();
      }
    }

    // Format time function (untuk notifikasi yang dimuat via AJAX)
    function formatTime(timestamp) {
      const date = new Date(timestamp);
      const now = new Date();
      const diff = Math.floor((now - date) / 1000);
      if (diff < 60) return 'Baru saja';
      else if (diff < 3600) return Math.floor(diff / 60) + ' menit yang lalu';
      else if (diff < 86400) return Math.floor(diff / 3600) + ' jam yang lalu';
      else if (diff < 604800) return Math.floor(diff / 86400) + ' hari yang lalu';
      else return date.toLocaleDateString('id-ID');
    }

    // Aktifkan Lucide Icons
    lucide.createIcons();

    // ===== ACTIVE STATE OTOMATIS =====
    function setActivePage() {
      const currentPage = window.location.pathname.split('/').pop().replace('.php', '');
      document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
      const activeLink = document.querySelector(`.nav-link[data-page="${currentPage}"]`);
      if (activeLink) activeLink.classList.add('active');
    }
    setActivePage();
    window.addEventListener('popstate', setActivePage);

    // Check for new notifications periodically
    setInterval(() => {
      // PERBAIKAN: Menggunakan BASE_URL
      fetch(BASE_URL + 'app/controllers/notifications/check_new.php')
        .then(response => response.json())
        .then(data => {
          if (data.success && data.new_count > 0) {
            updateNotificationBadge(data.unread_count);
            if ('Notification' in window && Notification.permission === 'granted') {
              data.new_notifications.forEach(notif => {
                new Notification(notif.title, {
                  body: notif.message,
                  icon: '/public/assets/img/logopolibatam.png'
                });
              });
            }
          }
        })
        .catch(error => console.error('Error checking for new notifications:', error));
    }, 60000);

    if ('Notification' in window && Notification.permission === 'default') {
      Notification.requestPermission().then(permission => {
        if (permission === 'granted') console.log('Notification permission granted');
      });
    }
  </script>
</body>

</html>