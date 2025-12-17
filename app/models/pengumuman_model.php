<?php
// PBL8/app/models/pengumuman_model.php

class PengumumanModel {
    private $db;
    
    public function __construct($config) {
        $this->db = $config;
    }
    
    /**
     * Get all pengumuman with target information
     */
    public function getAll() {
        $query = "SELECT 
                    p.pengumuman_id,
                    p.judul,
                    p.isi,
                    p.kategori_id,
                    p.target_type,
                    p.target_jurusan_id,
                    p.target_prodi_id,
                    p.target_kelas,
                    k.nama_kategori,
                    j.nama_jurusan,
                    pr.nama_prodi,
                    CASE 
                        WHEN p.target_type = 'all' THEN 'Semua Mahasiswa'
                        WHEN p.target_type = 'jurusan' AND p.target_prodi_id IS NULL AND p.target_kelas IS NULL THEN CONCAT('Jurusan ', j.nama_jurusan)
                        WHEN p.target_type = 'jurusan' AND p.target_prodi_id IS NOT NULL AND p.target_kelas IS NULL THEN CONCAT(j.nama_jurusan, ' - ', pr.nama_prodi)
                        WHEN p.target_type = 'jurusan' AND p.target_kelas IS NOT NULL THEN CONCAT(j.nama_jurusan, ' - ', pr.nama_prodi, ' - ', p.target_kelas)
                        WHEN p.target_type = 'prodi' AND p.target_kelas IS NULL THEN CONCAT('Prodi ', pr.nama_prodi)
                        WHEN p.target_type = 'prodi' AND p.target_kelas IS NOT NULL THEN CONCAT(pr.nama_prodi, ' - ', p.target_kelas)
                        WHEN p.target_type = 'kelas' THEN CONCAT(pr.nama_prodi, ' - Kelas ', p.target_kelas)
                        ELSE 'Semua Mahasiswa'
                    END as target_display
                  FROM pengumuman p
                  LEFT JOIN kategori k ON p.kategori_id = k.kategori_id
                  LEFT JOIN jurusan j ON p.target_jurusan_id = j.jurusan_id
                  LEFT JOIN prodi pr ON p.target_prodi_id = pr.prodi_id
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
                    p.*,
                    k.nama_kategori,
                    j.nama_jurusan,
                    pr.nama_prodi
                  FROM pengumuman p
                  LEFT JOIN kategori k ON p.kategori_id = k.kategori_id
                  LEFT JOIN jurusan j ON p.target_jurusan_id = j.jurusan_id
                  LEFT JOIN prodi pr ON p.target_prodi_id = pr.prodi_id
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
     * Get all jurusan
     */
    public function getAllJurusan() {
        $query = "SELECT jurusan_id, nama_jurusan FROM jurusan ORDER BY nama_jurusan ASC";
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
     * Get all prodi
     */
    public function getAllProdi() {
        $query = "SELECT prodi_id, nama_prodi, jurusan_id FROM prodi ORDER BY nama_prodi ASC";
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
     * Get kelas by prodi
     */
    public function getKelasByProdi($prodi_id) {
        $query = "SELECT DISTINCT kelas 
                  FROM mahasiswa 
                  WHERE prodi_id = ? AND kelas IS NOT NULL AND kelas != ''
                  ORDER BY kelas ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $prodi_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
    
    /**
     * Create new pengumuman
     */
    public function create($judul, $kategori_id, $isi, $targetData) {
        $query = "INSERT INTO pengumuman 
                  (judul, kategori_id, isi, target_type, target_jurusan_id, target_prodi_id, target_kelas) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        
        // Handle NULL values untuk target
        $target_jurusan_id = !empty($targetData['target_jurusan_id']) ? $targetData['target_jurusan_id'] : null;
        $target_prodi_id = !empty($targetData['target_prodi_id']) ? $targetData['target_prodi_id'] : null;
        $target_kelas = !empty($targetData['target_kelas']) ? $targetData['target_kelas'] : null;
        
        $stmt->bind_param(
            'sississ',
            $judul,
            $kategori_id,
            $isi,
            $targetData['target_type'],
            $target_jurusan_id,
            $target_prodi_id,
            $target_kelas
        );
        
        return $stmt->execute();
    }
    
    /**
     * Update pengumuman
     */
    public function update($id, $judul, $kategori_id, $isi, $targetData) {
        $query = "UPDATE pengumuman 
                  SET judul = ?, 
                      kategori_id = ?, 
                      isi = ?,
                      target_type = ?,
                      target_jurusan_id = ?,
                      target_prodi_id = ?,
                      target_kelas = ?
                  WHERE pengumuman_id = ?";
        
        $stmt = $this->db->prepare($query);
        
        // Handle NULL values untuk target
        $target_jurusan_id = !empty($targetData['target_jurusan_id']) ? $targetData['target_jurusan_id'] : null;
        $target_prodi_id = !empty($targetData['target_prodi_id']) ? $targetData['target_prodi_id'] : null;
        $target_kelas = !empty($targetData['target_kelas']) ? $targetData['target_kelas'] : null;
        
        $stmt->bind_param(
            'sississ i',
            $judul,
            $kategori_id,
            $isi,
            $targetData['target_type'],
            $target_jurusan_id,
            $target_prodi_id,
            $target_kelas,
            $id
        );
        
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
?>