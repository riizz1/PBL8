<?php
require_once __DIR__ . '/../../../config/config.php';

class DashboardControllerUser {
    private $config;
    
    public function __construct() {
        global $config;
        $this->config = $config;
    }
    
    public function index() {
        // Ambil 5 pengumuman terbaru
        $query = "SELECT p.pengumuman_id, p.judul, p.isi, p.created_at, k.nama_kategori 
                  FROM pengumuman p 
                  LEFT JOIN kategori k ON p.kategori_id = k.kategori_id 
                  ORDER BY p.created_at DESC 
                  LIMIT 5";
        
        $result = $this->config->query($query);
        
        $pengumumanTerbaru = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pengumumanTerbaru[] = $row;
            }
        }
        
        return [
            'pengumumanTerbaru' => $pengumumanTerbaru
        ];
    }
}