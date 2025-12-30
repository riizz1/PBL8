<?php
// PBL8/app/controllers/superadmin/dashboard_controller.php

class DashboardController
{
    private $db;

    public function __construct()
    {
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
    public function getStatistics()
    {
        $stats = [];

        // Total Dosen (exclude username 'admin' dan 'superadmin')
        $query = "SELECT COUNT(*) as total FROM admin 
                  WHERE role_id = 2 
                  AND username NOT IN ('admin', 'superadmin')";
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
     * Get recent announcements
     */
    public function getRecentAnnouncements($limit = 5)
    {
        $query = "SELECT 
                    p.judul,
                    p.created_at,
                    k.nama_kategori
                  FROM pengumuman p
                  LEFT JOIN kategori k ON p.kategori_id = k.kategori_id
                  ORDER BY p.created_at DESC
                  LIMIT ?";

        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            error_log("Error prepare getRecentAnnouncements: " . $this->db->error);
            return [];
        }

        $stmt->bind_param('i', $limit);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}