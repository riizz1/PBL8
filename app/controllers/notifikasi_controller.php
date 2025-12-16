<?php
// Pastikan session dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

// Koneksi ke database
require_once '../../../config/database.php'; // Sesuaikan path ke file database kamu

 $userId = $_SESSION['user_id'];

// Gunakan prepared statements untuk keamanan
 $query = "SELECT id, title, message, created_at, is_read 
          FROM notifications 
          WHERE user_id = ? 
          ORDER BY is_read ASC, created_at DESC 
          LIMIT 10";

 $stmt = mysqli_prepare($koneksi, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $notifications = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $notifications[] = $row;
    }

    // Hitung notifikasi yang belum dibaca
    $countQuery = "SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0";
    $countStmt = mysqli_prepare($koneksi, $countQuery);
    if ($countStmt) {
        mysqli_stmt_bind_param($countStmt, "i", $userId);
        mysqli_stmt_execute($countStmt);
        $countResult = mysqli_stmt_get_result($countStmt);
        $unreadCount = mysqli_fetch_assoc($countResult)['count'];
        mysqli_stmt_close($countStmt);
    } else {
        $unreadCount = 0;
    }

    mysqli_stmt_close($stmt);

    // Kembalikan response dalam format JSON
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'notifications' => $notifications,
        'unread_count' => (int)$unreadCount
    ]);

} else {
    // Jika query gagal
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Database query failed.']);
}
?>