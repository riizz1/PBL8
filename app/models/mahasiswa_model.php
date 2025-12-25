<?php
// PBL8/app/models/mahasiswa_model.php

class MahasiswaModel
{
    private $db;

    public function __construct()
    {
        // Buat koneksi ke database langsung
        $this->db = new mysqli('localhost', 'root', '', 'db_pbl8', 3307); // Ganti sesuai DB kamu
        if ($this->db->connect_error) {
            die("Koneksi gagal: " . $this->db->connect_error);
        }
    }

    /**
     * Get all mahasiswa with jurusan and prodi
     */
    public function getAll()
    {
        $query = "SELECT 
                    m.mahasiswa_id,
                    m.nim,
                    m.nama_lengkap,
                    m.email,
                    m.alamat,
                    m.jurusan_id,
                    m.prodi_id,
                    m.kelas,
                    j.nama_jurusan AS nama_jurusan,
                    p.nama_prodi AS nama_prodi,
                    m.created_at
                  FROM mahasiswa m
                  LEFT JOIN jurusan j ON m.jurusan_id = j.jurusan_id
                  LEFT JOIN prodi p ON m.prodi_id = p.prodi_id
                  ORDER BY m.nama_lengkap ASC";

        $result = $this->db->query($query);
        if (!$result)
            return [];

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     * Get all jurusan
     */
    public function getAllJurusan()
    {
        $query = "SELECT jurusan_id, nama_jurusan FROM jurusan ORDER BY nama_jurusan ASC";
        $result = $this->db->query($query);
        if (!$result)
            return [];

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     * Get all prodi with jurusan relation
     */
    public function getAllProdi()
    {
        $query = "SELECT prodi_id, nama_prodi AS prodi, jurusan_id FROM prodi ORDER BY nama_prodi ASC";
        $result = $this->db->query($query);
        if (!$result)
            return [];

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     * Get mahasiswa by ID
     */
    public function getById($id)
    {
        $query = "SELECT 
                    m.*,
                    j.nama_jurusan AS jurusan,
                    p.nama_prodi AS prodi
                  FROM mahasiswa m
                  LEFT JOIN jurusan j ON m.jurusan_id = j.jurusan_id
                  LEFT JOIN prodi p ON m.prodi_id = p.prodi_id
                  WHERE m.mahasiswa_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Create new mahasiswa
     */
    public function create($data)
    {
        $query = "INSERT INTO mahasiswa (nim, nama_lengkap, username, password, jurusan_id, prodi_id, kelas, email, alamat) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        $stmt->bind_param(
            'ssssiisss',
            $data['nim'],
            $data['nama_lengkap'],
            $data['username'],
            $hashedPassword,
            $data['jurusan_id'],
            $data['prodi_id'],
            $data['kelas'],
            $data['email'],
            $data['alamat']
        );

        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Mahasiswa berhasil ditambahkan',
                'id' => $this->db->insert_id
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menambahkan mahasiswa: ' . $stmt->error
            ];
        }
    }

    /**
     * Update mahasiswa
     */
    public function update($id, $data)
    {
        $query = "UPDATE mahasiswa 
                  SET nim = ?, 
                      nama_lengkap = ?, 
                      jurusan_id = ?,
                      prodi_id = ?,
                      kelas = ?,
                      email = ?, 
                      alamat = ?
                  WHERE mahasiswa_id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param(
            'ssiisssi',
            $data['nim'],
            $data['nama_lengkap'],
            $data['jurusan_id'],
            $data['prodi_id'],
            $data['kelas'],
            $data['email'],
            $data['alamat'],
            $id
        );

        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Data mahasiswa berhasil diperbarui'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $stmt->error
            ];
        }
    }

    /**
     * Delete mahasiswa
     */
    public function delete($id)
    {
        $query = "DELETE FROM mahasiswa WHERE mahasiswa_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Mahasiswa berhasil dihapus'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menghapus mahasiswa: ' . $stmt->error
            ];
        }
    }

    /**
     * Check if NIM already exists
     */
    public function nimExists($nim, $excludeId = null)
    {
        if ($excludeId) {
            $query = "SELECT COUNT(*) as count FROM mahasiswa WHERE nim = ? AND mahasiswa_id != ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('si', $nim, $excludeId);
        } else {
            $query = "SELECT COUNT(*) as count FROM mahasiswa WHERE nim = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('s', $nim);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    /**
     * Check if username already exists
     */
    public function usernameExists($username, $excludeId = null)
    {
        if ($excludeId) {
            $query = "SELECT COUNT(*) as count FROM mahasiswa WHERE username = ? AND mahasiswa_id != ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('si', $username, $excludeId);
        } else {
            $query = "SELECT COUNT(*) as count FROM mahasiswa WHERE username = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('s', $username);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    /**
     * Check if email already exists
     */
    public function emailExists($email, $excludeId = null)
    {
        if (empty($email))
            return false;

        if ($excludeId) {
            $query = "SELECT COUNT(*) as count FROM mahasiswa WHERE email = ? AND mahasiswa_id != ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('si', $email, $excludeId);
        } else {
            $query = "SELECT COUNT(*) as count FROM mahasiswa WHERE email = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('s', $email);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }
}
?>