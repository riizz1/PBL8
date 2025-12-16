<?php
// PBL8/app/models/pengumuman_model.php

class PengumumanModel {
    private $db;
    
    public function __construct($connection) {
        $this->db = $connection;
    }
    
    /**
     * Get all pengumuman
     */
    public function getAll() {
        $query = "SELECT 
                    p.pengumuman_id,
                    p.judul,
                    p.isi,
                    p.kategori_id,
                    k.nama_kategori
                  FROM pengumuman p
                  LEFT JOIN kategori k ON p.kategori_id = k.kategori_id
                  ORDER BY p.pengumuman_id DESC";
        
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
     * Get pengumuman by ID
     */
    public function getById($id) {
        $query = "SELECT 
                    p.pengumuman_id,
                    p.judul,
                    p.isi,
                    p.kategori_id,
                    k.nama_kategori
                  FROM pengumuman p
                  LEFT JOIN kategori k ON p.kategori_id = k.kategori_id
                  WHERE p.pengumuman_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    /**
     * Get all kategori
     */
    public function getKategori() {
        $query = "SELECT kategori_id, nama_kategori FROM kategori ORDER BY nama_kategori ASC";
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
     * Create new pengumuman
     */
    public function create($judul, $kategori_id, $isi) {
        $query = "INSERT INTO pengumuman (judul, kategori_id, isi) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sis', $judul, $kategori_id, $isi);
        
        return $stmt->execute();
    }
    
    /**
     * Update pengumuman
     */
    public function update($id, $judul, $kategori_id, $isi) {
        $query = "UPDATE pengumuman SET judul = ?, kategori_id = ?, isi = ? WHERE pengumuman_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sisi', $judul, $kategori_id, $isi, $id);
        
        return $stmt->execute();
    }
    
    /**
     * Delete pengumuman
     */
    public function delete($id) {
        $query = "DELETE FROM pengumuman WHERE pengumuman_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        
        return $stmt->execute();
    }
}