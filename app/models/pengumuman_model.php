<?php

class PengumumanModel
{
    private $db;

    public function __construct()
    {
        require __DIR__ . '/../../config/config.php';
        $this->db = $config; // mysqli dari config.php
    }

    /* ---------------------------------------------------------
       GET ALL PENGUMUMAN
    --------------------------------------------------------- */
    public function getAll()
    {
        $sql = "SELECT p.*, k.nama_kategori 
                FROM pengumuman p
                LEFT JOIN kategori k ON p.kategori_id = k.kategori_id
                ORDER BY p.created_at DESC";

        $result = $this->db->query($sql);

        if (!$result) {
            return [];
        }

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    /* ---------------------------------------------------------
       CREATE
    --------------------------------------------------------- */
    public function create($judul, $kategori_id, $isi)
    {
        $sql = "INSERT INTO pengumuman (judul, kategori_id, isi, created_at)
                VALUES (?, ?, ?, NOW())";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sis", $judul, $kategori_id, $isi);

        return $stmt->execute();
    }

    /* ---------------------------------------------------------
       UPDATE
    --------------------------------------------------------- */
    public function update($id, $judul, $kategori_id, $isi)
    {
        $sql = "UPDATE pengumuman 
                SET judul=?, kategori_id=?, isi=?
                WHERE pengumuman_id=?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sisi", $judul, $kategori_id, $isi, $id);

        return $stmt->execute();
    }

    /* ---------------------------------------------------------
       DELETE
    --------------------------------------------------------- */
    public function delete($id)
    {
        $sql = "DELETE FROM pengumuman WHERE pengumuman_id=?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    /* ---------------------------------------------------------
       GET KATEGORI
    --------------------------------------------------------- */
    public function getKategori()
    {
        $sql = "SELECT * FROM kategori ORDER BY nama_kategori ASC";

        $result = $this->db->query($sql);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    /* ---------------------------------------------------------
       FILTER PENGUMUMAN (MYSQLI VERSION) - FIXED
    --------------------------------------------------------- */
    public function filterPengumuman($kategori = null, $bulan = null, $tahun = null, $limit = null, $offset = null)
    {
        $query = "SELECT p.*, k.nama_kategori
                  FROM pengumuman p
                  LEFT JOIN kategori k ON p.kategori_id = k.kategori_id
                  WHERE 1=1";

        $types = "";
        $values = [];

        if (!empty($kategori)) {
            $query .= " AND k.nama_kategori = ?";
            $types .= "s";
            $values[] = $kategori;
        }

        if (!empty($bulan)) {
            $query .= " AND MONTH(p.created_at) = ?";
            $types .= "i";
            $values[] = $bulan;
        }

        if (!empty($tahun)) {
            $query .= " AND YEAR(p.created_at) = ?";
            $types .= "i";
            $values[] = $tahun;
        }

        $query .= " ORDER BY p.created_at DESC";

        // Tambahkan LIMIT dan OFFSET jika ada
        if ($limit !== null && $offset !== null) {
            $query .= " LIMIT ? OFFSET ?";
            $types .= "ii";
            $values[] = $limit;
            $values[] = $offset;
        }

        $stmt = $this->db->prepare($query);

        if (!empty($values)) {
            $stmt->bind_param($types, ...$values);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    /* ---------------------------------------------------------
       COUNT PENGUMUMAN (untuk pagination)
    --------------------------------------------------------- */
    public function countPengumuman($kategori = null, $bulan = null, $tahun = null)
    {
        $query = "SELECT COUNT(*) as total
                  FROM pengumuman p
                  LEFT JOIN kategori k ON p.kategori_id = k.kategori_id
                  WHERE 1=1";

        $types = "";
        $values = [];

        if (!empty($kategori)) {
            $query .= " AND k.nama_kategori = ?";
            $types .= "s";
            $values[] = $kategori;
        }

        if (!empty($bulan)) {
            $query .= " AND MONTH(p.created_at) = ?";
            $types .= "i";
            $values[] = $bulan;
        }

        if (!empty($tahun)) {
            $query .= " AND YEAR(p.created_at) = ?";
            $types .= "i";
            $values[] = $tahun;
        }

        $stmt = $this->db->prepare($query);

        if (!empty($values)) {
            $stmt->bind_param($types, ...$values);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total'];
    }

    /* ---------------------------------------------------------
       GET DETAIL PENGUMUMAN BY ID
    --------------------------------------------------------- */
    public function getById($id)
    {
        $sql = "SELECT p.*, k.nama_kategori 
                FROM pengumuman p
                LEFT JOIN kategori k ON p.kategori_id = k.kategori_id
                WHERE p.pengumuman_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    /* ---------------------------------------------------------
       GET AVAILABLE MONTHS - FIXED
    --------------------------------------------------------- */
    public function getAvailableMonths()
    {
        $sql = "SELECT DISTINCT MONTH(created_at) AS bulan
                FROM pengumuman
                ORDER BY bulan ASC";

        $result = $this->db->query($sql);

        $bulanNama = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'bulan' => $row['bulan'],
                'nama_bulan' => $bulanNama[$row['bulan']]
            ];
        }

        return $data;
    }

    /* ---------------------------------------------------------
       GET AVAILABLE YEARS - FIXED
    --------------------------------------------------------- */
    public function getAvailableYears()
    {
        $sql = "SELECT DISTINCT YEAR(created_at) AS tahun
                FROM pengumuman
                ORDER BY tahun DESC";

        $result = $this->db->query($sql);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'tahun' => $row['tahun']
            ];
        }

        return $data;
    }
}