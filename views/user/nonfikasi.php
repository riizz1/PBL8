<?php
include '../../config/config.php';

// Ambil notifikasi dari database (contoh tabel 'notifikasi')
$sql = "SELECT * FROM notifikasi ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
$notifications = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Notifikasi</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php include("header.php"); ?>

<div class="container mt-4">
    <h2>Notifikasi</h2>
    <?php if(count($notifications) > 0): ?>
        <ul class="list-group">
            <?php foreach($notifications as $n): ?>
                <li class="list-group-item">
                    <?= htmlspecialchars($n['message']) ?>
                    <span class="text-muted float-end"><?= date('d M Y H:i', strtotime($n['created_at'])) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Tidak ada notifikasi baru.</p>
    <?php endif; ?>
</div>

<?php include("footer.php"); ?>
</body>
</html>
