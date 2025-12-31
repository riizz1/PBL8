<?php
class AuthHelper
{
    private $conn;

    public function __construct($config)
    {
        $this->conn = $config;
    }

    /**
     * Cek apakah username sudah dipakai (di SEMUA tabel: mahasiswa, admin)
     * 
     * @param string $username Username yang mau dicek
     * @param int|null $excludeId ID yang mau dikecualikan (untuk update)
     * @param string|null $excludeTable Tabel yang mau dikecualikan (mahasiswa/admin)
     * @return bool True jika username sudah ada
     */
    public function isUsernameExists($username, $excludeId = null, $excludeTable = null)
    {
        $username = trim($username);

        // 1. Cek di tabel mahasiswa
        if ($excludeTable !== 'mahasiswa') {
            $query = "SELECT COUNT(*) as count FROM mahasiswa WHERE username = ?";
            if ($excludeTable === 'mahasiswa' && $excludeId) {
                $query .= " AND mahasiswa_id != ?";
            }

            $stmt = $this->conn->prepare($query);
            if ($excludeTable === 'mahasiswa' && $excludeId) {
                $stmt->bind_param('si', $username, $excludeId);
            } else {
                $stmt->bind_param('s', $username);
            }
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            if ($result['count'] > 0) {
                return true; // Username sudah ada di mahasiswa
            }
        }

        // 2. Cek di tabel admin (dosen & superadmin)
        if ($excludeTable !== 'admin') {
            $query = "SELECT COUNT(*) as count FROM admin WHERE username = ?";
            if ($excludeTable === 'admin' && $excludeId) {
                $query .= " AND user_id != ?";
            }

            $stmt = $this->conn->prepare($query);
            if ($excludeTable === 'admin' && $excludeId) {
                $stmt->bind_param('si', $username, $excludeId);
            } else {
                $stmt->bind_param('s', $username);
            }
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            if ($result['count'] > 0) {
                return true; // Username sudah ada di admin
            }
        }

        return false; // Username belum dipakai
    }

    /**
     * Generate username unik dengan prefix berdasarkan role
     */
    public function generateUniqueUsername($baseUsername, $role = 'mahasiswa')
    {
        $baseUsername = preg_replace('/[^a-zA-Z0-9]/', '', $baseUsername); // Bersihkan
        $baseUsername = strtolower($baseUsername);

        $prefix = '';
        switch ($role) {
            case 'mahasiswa':
                $prefix = 'mhs_';
                break;
            case 'dosen':
                $prefix = 'dsn_';
                break;
            case 'superadmin':
                $prefix = 'adm_';
                break;
            default:
                $prefix = 'usr_';
        }

        $username = $prefix . $baseUsername;

        // Kalau masih duplicate, tambah angka
        $counter = 1;
        while ($this->isUsernameExists($username)) {
            $username = $prefix . $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }

    /**
     * Login universal (cek di semua tabel)
     */
    public function login($username, $password)
    {
        $username = trim($username);

        // 1. Coba cek di tabel MAHASISWA
        $stmt = $this->conn->prepare("
            SELECT m.mahasiswa_id as user_id, m.username, m.password, m.nama_lengkap, 
                   m.email, m.role_id, r.role_name 
            FROM mahasiswa m 
            JOIN roles r ON m.role_id = r.role_id 
            WHERE m.username = ?
        ");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return [
                    'success' => true,
                    'user_id' => $user['user_id'],
                    'username' => $user['username'],
                    'nama_lengkap' => $user['nama_lengkap'],
                    'email' => $user['email'],
                    'role_id' => $user['role_id'],
                    'role_name' => $user['role_name'],
                    'user_table' => 'mahasiswa'
                ];
            } else {
                return ['success' => false, 'message' => 'Password salah'];
            }
        }

        // 2. Coba cek di tabel ADMIN (dosen & superadmin)
        $stmt = $this->conn->prepare("
            SELECT a.user_id, a.username, a.password, a.nama_lengkap, 
                   a.email, a.role_id, r.role_name 
            FROM admin a 
            JOIN roles r ON a.role_id = r.role_id 
            WHERE a.username = ?
        ");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return [
                    'success' => true,
                    'user_id' => $user['user_id'],
                    'username' => $user['username'],
                    'nama_lengkap' => $user['nama_lengkap'],
                    'email' => $user['email'],
                    'role_id' => $user['role_id'],
                    'role_name' => $user['role_name'],
                    'user_table' => 'admin'
                ];
            } else {
                return ['success' => false, 'message' => 'Password salah'];
            }
        }

        return ['success' => false, 'message' => 'Username tidak ditemukan'];
    }

    /**
     * Get redirect path berdasarkan role
     */
    /**
     * Get redirect path berdasarkan role
     */
    public function getRedirectPath($roleName)
    {
        switch (strtolower($roleName)) {
            case 'superadmin':
                return '/PBL8/views/superadmin/dashboard.php';
            case 'dosen':
                return '/PBL8/views/admin/dashboard.php';
            case 'mahasiswa':
                return '/PBL8/views/user/dashboard.php';
            default:
                return '/PBL8/views/auth/login.php';
        }
    }
}
?>