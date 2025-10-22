<?php
session_start();
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Capture username and hashed password from form
$username = $_POST['username'];
$password = md5($_POST['password']); // MD5 for hashing (though consider using a more secure hash like password_hash in future)

echo "Username: $username, Password: $password"; // Debugging output

// Prepare the SQL statement to check username and password
$stmt = $koneksi->prepare("SELECT * FROM user WHERE username=? AND password=?");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
$cek = $result->num_rows;
echo "Rows found: $cek"; // Debugging output

if ($cek > 0) {
    $data = $result->fetch_assoc();
    $_SESSION['username'] = $data['username'];
    $_SESSION['userid'] = $data['userid'];
    $_SESSION['status'] = 'login';
    $_SESSION['level'] = $data['level'];

    // Check if the user is admin or user based on the level from the database
    if ($data['level'] === 'admin') {
        echo "<script>
        alert('Login berhasil sebagai Admin!');
        location.href='./admin/dashboard.php';
        </script>";
    } else {
        echo "<script>
        alert('Login berhasil sebagai User!');
        location.href='../users/dashboard.php';
        </script>";
    }
} else {
    // Redirect back to login page with an error message
    echo "<script>
    alert('Username atau password salah!');
    location.href='../login.php';
    </script>";
}
?>
