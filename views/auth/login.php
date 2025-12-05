<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <?php
    include("../../public/header.php");
    ?>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5 col-lg-4">
                <div class="login-card">
                    <div class="text-center mb-4">
                        <div class="logo-circle">
                            <svg width="50" height="50" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 6C13.93 6 15.5 7.57 15.5 9.5C15.5 11.43 13.93 13 12 13C10.07 13 8.5 11.43 8.5 9.5C8.5 7.57 10.07 6 12 6ZM12 20C9.97 20 7.57 19.18 5.86 17.12C8.55 15.8 9.68 15.5 12 15.5C14.32 15.5 15.45 15.8 18.14 17.12C16.43 19.18 14.03 20 12 20Z"
                                    fill="white" />
                            </svg>
                        </div>
                        <h2 class="mt-3 mb-1">Selamat Datang</h2>
                        <p class="text-muted">Silakan login ke akun Anda</p>
                    </div>

                    <form action="/PBL8/app/controllers/auth/login_aksi.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" class="form-control form-control-lg" id="username"
                                placeholder="Masukkan Username" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg" id="password"
                                placeholder="Masukkan Password" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">Masuk</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <?php
    include("../../public/footer.php");
    ?>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="jslogin.js"></script>

</html>