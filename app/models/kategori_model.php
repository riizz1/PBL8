<?php
class KategoriModel {
    private $db;

    public function __construct($config)
    {
        $this->db = $config;
    }

    public function getAllKategori()
    {
        $query = "SELECT * FROM kategori ORDER BY kategori_id ASC";
        $result = $this->db->query($query);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}
?>
