<?php
// app/models/dosen_model.php

class DosenModel
{
    private $db;

    public function __construct()
    {
        // PERBAIKAN: Gunakan mysqli langsung
        $this->db = new mysqli('localhost', 'root', '', 'db_pbl8');
        if ($this->db->connect_error) {
            die("Koneksi gagal: " . $this->db->connect_error);
        }
    }

    public function getAll()
    {
        $sql = "SELECT 
                user_id,
                username,
                nama_lengkap,
                nidn,
                email,
                no_telepon,
                alamat,
                jenis_kelamin,
                jabatan,
                created_at
            FROM admin
            WHERE role_id = 2
              AND username NOT IN ('admin', 'superadmin')
            ORDER BY nama_lengkap ASC";

        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getById($id)
    {
        $sql = "SELECT 
                user_id,
                username,
                nama_lengkap,
                nidn,
                email,
                no_telepon,
                alamat,
                jenis_kelamin,
                jabatan
            FROM admin 
            WHERE user_id = ? AND role_id = 2";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->db->error);
            return null;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($data)
    {
        $sql = "INSERT INTO admin 
                (username, nama_lengkap, nidn, email, no_telepon, alamat, 
                 jenis_kelamin, jabatan, password, role_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 2)";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            error_log("Prepare failed: " . $this->db->error);
            return false;
        }

        $jenis_kelamin = isset($data['jenis_kelamin']) && $data['jenis_kelamin'] !== '' ? $data['jenis_kelamin'] : null;
        $jabatan = isset($data['jabatan']) && $data['jabatan'] !== '' ? $data['jabatan'] : null;
        $no_telepon = isset($data['no_telepon']) && $data['no_telepon'] !== '' ? $data['no_telepon'] : null;
        $alamat = isset($data['alamat']) && $data['alamat'] !== '' ? $data['alamat'] : null;

        $stmt->bind_param(
            "sssssssss",
            $data['username'],
            $data['nama_lengkap'],
            $data['nidn'],
            $data['email'],
            $no_telepon,
            $alamat,
            $jenis_kelamin,
            $jabatan,
            $data['password']
        );


        $result = $stmt->execute();

        if (!$result) {
            error_log("Execute failed: " . $stmt->error);
            return false;
        }

        return true;
    }

    public function update($id, $data)
    {
        $sql = "UPDATE admin SET 
                    username = ?,
                    nama_lengkap = ?, 
                    nidn = ?, 
                    email = ?, 
                    no_telepon = ?, 
                    alamat = ?,
                    jenis_kelamin = ?,
                    jabatan = ?
                WHERE user_id = ? AND role_id = 2";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            error_log("Prepare failed: " . $this->db->error);
            return false;
        }

        $jenis_kelamin = isset($data['jenis_kelamin']) && $data['jenis_kelamin'] !== '' ? $data['jenis_kelamin'] : null;
        $jabatan = isset($data['jabatan']) && $data['jabatan'] !== '' ? $data['jabatan'] : null;
        $no_telepon = isset($data['no_telepon']) && $data['no_telepon'] !== '' ? $data['no_telepon'] : null;
        $alamat = isset($data['alamat']) && $data['alamat'] !== '' ? $data['alamat'] : null;

        $stmt->bind_param(
            "ssssssssi",
            $data['username'],
            $data['nama_lengkap'],
            $data['nidn'],
            $data['email'],
            $no_telepon,
            $alamat,
            $jenis_kelamin,
            $jabatan,
            $id
        );

        $result = $stmt->execute();

        if (!$result) {
            error_log("Execute failed: " . $stmt->error);
            return false;
        }

        return true;
    }

    public function updatePassword($id, $hashedPassword)
    {
        $sql = "UPDATE admin SET password = ? WHERE user_id = ? AND role_id = 2";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            error_log("Prepare failed: " . $this->db->error);
            return false;
        }

        $stmt->bind_param("si", $hashedPassword, $id);
        $result = $stmt->execute();

        if (!$result) {
            error_log("Execute failed: " . $stmt->error);
            return false;
        }

        return true;
    }

    public function delete($id)
    {
        $checkSql = "SELECT role_id, username FROM admin WHERE user_id = ?";
        $checkStmt = $this->db->prepare($checkSql);
        $checkStmt->bind_param("i", $id);
        $checkStmt->execute();
        $result = $checkStmt->get_result()->fetch_assoc();

        if ($result && $result['role_id'] == 1) {
            error_log("Cannot delete superadmin account");
            return false;
        }

        if ($result && $result['username'] === 'admin') {
            error_log("Cannot delete master admin account");
            return false;
        }

        $sql = "DELETE FROM admin WHERE user_id = ? AND role_id = 2";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            error_log("Prepare failed: " . $this->db->error);
            return false;
        }

        $stmt->bind_param("i", $id);
        $result = $stmt->execute();

        if (!$result) {
            error_log("Execute failed: " . $stmt->error);
            return false;
        }

        return true;
    }

    public function usernameExists($username, $excludeId = null)
    {
        if ($excludeId) {
            $sql = "SELECT user_id FROM admin WHERE username = ? AND user_id != ?";
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                error_log("Prepare failed: " . $this->db->error);
                return false;
            }
            $stmt->bind_param("si", $username, $excludeId);
        } else {
            $sql = "SELECT user_id FROM admin WHERE username = ?";
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                error_log("Prepare failed: " . $this->db->error);
                return false;
            }
            $stmt->bind_param("s", $username);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function nidnExists($nidn, $excludeId = null)
    {
        if ($excludeId) {
            $sql = "SELECT user_id FROM admin WHERE nidn = ? AND user_id != ?";
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                error_log("Prepare failed: " . $this->db->error);
                return false;
            }
            $stmt->bind_param("si", $nidn, $excludeId);
        } else {
            $sql = "SELECT user_id FROM admin WHERE nidn = ?";
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                error_log("Prepare failed: " . $this->db->error);
                return false;
            }
            $stmt->bind_param("s", $nidn);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function emailExists($email, $excludeId = null)
    {
        if ($excludeId) {
            $sql = "SELECT user_id FROM admin WHERE email = ? AND user_id != ?";
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                error_log("Prepare failed: " . $this->db->error);
                return false;
            }
            $stmt->bind_param("si", $email, $excludeId);
        } else {
            $sql = "SELECT user_id FROM admin WHERE email = ?";
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                error_log("Prepare failed: " . $this->db->error);
                return false;
            }
            $stmt->bind_param("s", $email);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
}
?>