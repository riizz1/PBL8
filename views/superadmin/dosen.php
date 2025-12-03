<?php
session_start();

// Cek hanya superadmin yang bisa akses
if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'superadmin') {

    header("Location: ../views/auth/login.php");
    header("Location: ../../views/auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Admin (Dosen)</title>
</head>
<body>
    <h2>Tambah Akun Dosen</h2>

    <form action="../app/controllers/models/add_dosen.php" method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Tambah Admin</button>
    </form>

=======
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Admin (Dosen)</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .header-blue {
            background-color: #2193b0 !important;
            color: white !important;
        }
        .card-header-blue {
            background-color: #2193b0 !important;
            color: white !important;
        }
        .btn-skyblue {
            background-color: #87CEEB !important;
            color: white !important;
            border: none !important;
        }
        .btn-skyblue:hover {
            background-color: #00BFFF !important;
            color: white !important;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg header-blue mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Superadmin Dashboard</a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header card-header-blue">
                        Tambah Akun Dosen
                    </div>
                    <div class="card-body">
                        <form action="../app/controllers/models/add_dosen.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Username:</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password:</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-skyblue w-100">Tambah Admin</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
>>>>>>> dec0de8 (membuat dashboard di superadmin dan membuat agar admin yang ada di superadmin bisa jalan)
</body>
</html>
