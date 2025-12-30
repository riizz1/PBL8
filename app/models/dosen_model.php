<?php
require_once __DIR__ . '/../../config/config.php';

class DosenModel
{
    private $db;

    public function __construct()
    {
        global $config;
        $this->db = $config;
    }

    public function getAll()
    {
        $sql = "SELECT 
                user_id AS dosen_id,
                nama_lengkap,
                nidn,
                username,
                email,
                no_telepon,
                alamat
            FROM admin
            WHERE role_id = 2
              AND username NOT IN ('admin', 'superadmin')
            ORDER BY nama_lengkap ASC";

        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }


    public function getById($id)
    {
        $sql = "SELECT user_id as dosen_id, nama_lengkap, nidn, username, email, no_telepon, alamat 
                FROM admin 
                WHERE user_id = ? AND role_id = 2";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($data)
    {
        // Insert data lengkap ke tabel admin
        // Password sudah di-hash di Controller, jadi langsung masuk
        $sql = "INSERT INTO admin 
                (nama_lengkap, nidn, username, password, email, no_telepon, alamat, role_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 2)";

        $stmt = $this->db->prepare($sql);

        // Binding parameter: s=string, i=integer (2 adalah role_id dosen)
        $stmt->bind_param(
            "sssssss",
            $data['nama_lengkap'],
            $data['nidn'],
            $data['username'],
            $data['password'],
            $data['email'],
            $data['no_telepon'],
            $data['alamat']
        );

        return $stmt->execute();
    }

    public function update($id, $data)
    {
        // Update data (Tidak mengubah password untuk keamanan di mode ini)
        $sql = "UPDATE admin SET 
                    nama_lengkap = ?, 
                    nidn = ?, 
                    email = ?, 
                    no_telepon = ?, 
                    alamat = ?
                WHERE user_id = ? AND role_id = 2";

        $stmt = $this->db->prepare($sql);

        $stmt->bind_param(
            "sssssi",
            $data['nama_lengkap'],
            $data['nidn'],
            $data['email'],
            $data['no_telepon'],
            $data['alamat'],
            $id
        );

        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM admin WHERE user_id = ? AND role_id = 2";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /* ===== VALIDASI ===== */

    // Cek apakah username sudah dipakai
    public function usernameExists($username, $excludeId = null)
    {
        $sql = "SELECT user_id FROM admin WHERE username = ?";
        $params = ["s", $username];
        $types = "s";

        if ($excludeId) {
            $sql .= " AND user_id != ?";
            $types .= "i";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // Cek apakah NIDN sudah dipakai
    public function nidnExists($nidn, $excludeId = null)
    {
        $sql = "SELECT user_id FROM admin WHERE nidn = ?";
        $params = ["s", $nidn];
        $types = "s";

        if ($excludeId) {
            $sql .= " AND user_id != ?";
            $types .= "i";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // Cek apakah Email sudah dipakai
    public function emailExists($email, $excludeId = null)
    {
        $sql = "SELECT user_id FROM admin WHERE email = ?";
        $params = ["s", $email];
        $types = "s";

        if ($excludeId) {
            $sql .= " AND user_id != ?";
            $types .= "i";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }
}