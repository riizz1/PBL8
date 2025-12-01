<?php
class CrudKategori {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // READ
    public function getAll() {
        $sql = "SELECT * FROM kategori ORDER BY kategori_id DESC";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // CREATE
    public function add($nama_kategori, $deskripsi) {
        $stmt = $this->conn->prepare("INSERT INTO kategori (nama_kategori, deskripsi) VALUES (?, ?)");
        $stmt->bind_param("ss", $nama_kategori, $deskripsi);
        return $stmt->execute();
    }

    // READ single
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM kategori WHERE kategori_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // UPDATE
    public function update($id, $nama_kategori, $deskripsi) {
        $stmt = $this->conn->prepare("UPDATE kategori SET nama_kategori=?, deskripsi=? WHERE kategori_id=?");
        $stmt->bind_param("ssi", $nama_kategori, $deskripsi, $id);
        return $stmt->execute();
    }

    // DELETE
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM kategori WHERE kategori_id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
