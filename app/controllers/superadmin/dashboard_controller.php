<?php
// PBL8/app/controllers/superadmin/dashboard_controller.php

class DashboardController {
    private $db;
    
    public function __construct() {
        // Include config.php dan ambil koneksi
        include __DIR__ . '/../../../config/config.php';
        
        // Langsung assign variabel $config ke $this->db
        $this->db = $config;
        
        // Cek koneksi
        if ($this->db === null) {
            die("Error: Variabel \$config tidak ditemukan di config.php");
        }
        
        if ($this->db->connect_error) {
            die("Error: Koneksi database gagal - " . $this->db->connect_error);
        }
    }
    
    /**
     * Get dashboard statistics
     */
    public function getStatistics() {
        $stats = [];
        
        // Total Dosen
        $query = "SELECT COUNT(*) as total FROM admin WHERE role_id = 2";
        $result = $this->db->query($query);
        $stats['total_dosen'] = $result->fetch_assoc()['total'];
        
        // Total Mahasiswa
        $query = "SELECT COUNT(*) as total FROM mahasiswa";
        $result = $this->db->query($query);
        $stats['total_mahasiswa'] = $result->fetch_assoc()['total'];
        
        // Total Pengumuman
        $query = "SELECT COUNT(*) as total FROM pengumuman";
        $result = $this->db->query($query);
        $stats['total_pengumuman'] = $result->fetch_assoc()['total'];
        
        // Total Kategori
        $query = "SELECT COUNT(*) as total FROM kategori";
        $result = $this->db->query($query);
        $stats['total_kategori'] = $result->fetch_assoc()['total'];
        
        return $stats;
    }
    
    /**
     * Get announcements by category
     */
    public function getAnnouncementsByCategory() {
        $query = "SELECT 
                    k.nama_kategori,
                    COUNT(p.pengumuman_id) as jumlah_pengumuman
                  FROM kategori k
                  LEFT JOIN pengumuman p ON k.kategori_id = p.kategori_id
                  GROUP BY k.kategori_id, k.nama_kategori
                  ORDER BY jumlah_pengumuman DESC";
        
        $result = $this->db->query($query);
        $data = [];
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
    
    /**
     * Get recent announcements
     */
    public function getRecentAnnouncements($limit = 5) {
        $query = "SELECT 
                    p.judul,
                    p.created_at,
                    k.nama_kategori
                  FROM pengumuman p
                  LEFT JOIN kategori k ON p.kategori_id = k.kategori_id
                  ORDER BY p.created_at DESC
                  LIMIT ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
    
    /**
     * Get monthly announcement trend (last 6 months)
     */
    public function getMonthlyTrend() {
        $query = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as bulan,
                    COUNT(*) as jumlah
                  FROM pengumuman
                  WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                  GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                  ORDER BY bulan ASC";
        
        $result = $this->db->query($query);
        $data = [];
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
    
    /**
     * Get recent admin (dosen & mahasiswa)
     */
    public function getRecentAdmin($limit = 5) {
        $query = "SELECT 
                    u.username,
                    r.role_name,
                    u.created_at
                  FROM admin a
                  LEFT JOIN roles r ON u.role_id = r.role_id
                  WHERE u.role_id IN (2, 3)
                  ORDER BY u.created_at DESC
                  LIMIT ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}
?>