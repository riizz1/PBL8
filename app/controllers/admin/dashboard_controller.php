<?php
class DashboardController
{
    private $db;

    public function __construct($config)
    {
        $this->db = $config;
    }

    public function index()
    {
        return [
            'total_pengumuman'   => $this->getCount("pengumuman"),
            'total_kategori'     => $this->getCount("kategori"),
            'pengumuman_terbaru' => $this->getLatest()
        ];
    }

    // =====================
    // Hitung data (AMAN)
    // =====================
    private function getCount($table, $where = null)
    {
        $sql = "SELECT COUNT(*) FROM $table";
        if ($where) {
            $sql .= " WHERE $where";
        }

        $result = mysqli_query($this->db, $sql);
        if (!$result) {
            die("Query Error: " . mysqli_error($this->db));
        }

        $row = mysqli_fetch_row($result);
        return $row[0] ?? 0;
    }

    // =====================
    // Pengumuman terbaru
    // =====================
    private function getLatest()
    {
        $sql = "
            SELECT judul, kategori_id, created_at
            FROM pengumuman
            ORDER BY created_at DESC
            LIMIT 5
        ";

        $result = mysqli_query($this->db, $sql);
        if (!$result) {
            die("Query Error: " . mysqli_error($this->db));
        }

        return $result;
    }
}