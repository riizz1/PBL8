<?php
// PBL8/app/models/Mahasiswa.php

class Mahasiswa {
    private $db;
    
    public function __construct($connection) {
        $this->db = $connection;
    }
    
    /**
     * Get all mahasiswa
     */
    public function getAll() {
        $query = "SELECT 
                    mahasiswa_id,
                    nim,
                    nama_lengkap,
                    prodi,
                    email,
                    alamat,
                    created_at
                  FROM mahasiswa 
                  ORDER BY nama_lengkap ASC";
        
        $result = $this->db->query($query);
        
        if (!$result) {
            return [];
        }
        
        $data = [];
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
    
    /**
     * Get mahasiswa by ID
     */
    public function getById($id) {
        $query = "SELECT * FROM mahasiswa WHERE mahasiswa_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    /**
     * Create new mahasiswa
     */
    public function create($data) {
        $query = "INSERT INTO mahasiswa (nim, nama_lengkap, username, password, prodi, email, alamat) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        
        // Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        
        $stmt->bind_param(
            'sssssss',
            $data['nim'],
            $data['nama_lengkap'],
            $data['username'],
            $hashedPassword,
            $data['prodi'],
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
    public function update($id, $data) {
        $query = "UPDATE mahasiswa 
                  SET nim = ?, 
                      nama_lengkap = ?, 
                      prodi = ?, 
                      email = ?, 
                      alamat = ?
                  WHERE mahasiswa_id = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param(
            'sssssi',
            $data['nim'],
            $data['nama_lengkap'],
            $data['prodi'],
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
    public function delete($id) {
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
    public function nimExists($nim, $excludeId = null) {
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
    public function usernameExists($username, $excludeId = null) {
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
}
?>