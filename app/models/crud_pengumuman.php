<?php

class PengumumanModel {

    private $config;

    public function __construct($db) {
        $this->config = $db;
    }

    public function getAll() {
        $sql = "SELECT p.*, k.nama_kategori 
                FROM pengumuman p 
                LEFT JOIN kategori k ON k.kategori_id = p.kategori_id
                ORDER BY p.created_at DESC";
        return $this->config->query($sql);
    }

    public function getKategori() {
        $sql = "SELECT * FROM kategori ORDER BY nama_kategori ASC";
        return $this->config->query($sql);
    }

    public function add($judul, $kategori_id, $isi) {
        $stmt = $this->config->prepare(
            "INSERT INTO pengumuman (judul, kategori_id, isi, created_at)
             VALUES (?, ?, ?, NOW())"
        );
        $stmt->bind_param("sis", $judul, $kategori_id, $isi);
        return $stmt->execute();
    }

    public function update($id, $judul, $kategori_id, $isi) {
        $stmt = $this->config->prepare(
            "UPDATE pengumuman 
             SET judul=?, kategori_id=?, isi=?
             WHERE pengumuman_id=?"
        );
        $stmt->bind_param("sisi", $judul, $kategori_id, $isi, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->config->prepare(
            "DELETE FROM pengumuman WHERE pengumuman_id=?"
        );
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

}
