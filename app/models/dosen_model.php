<?php
// File: app/models/dosen_model.php
require_once __DIR__ . '/../../config/config.php';

class DosenModel
{
    private $db;

    public function __construct()
    {
        global $config;         // dari config.php
        $this->db = $config;    // mysqli connection
    }

    /**
     * Ambil semua dosen (role_id = 2)
     */
    public function getAllDosen()
    {
        $query = "SELECT 
                    user_id,
                    username,
                    password,
                    role_id,
                    created_at
                  FROM admin
                  WHERE role_id = 2
                  ORDER BY username ASC";

        $result = $this->db->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Ambil dosen berdasarkan user_id
     */
    public function getDosenById($id)
    {
        $stmt = $this->db->prepare("SELECT user_id, username, role_id, created_at 
                                    FROM admin 
                                    WHERE user_id = ? AND role_id = 2");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * Tambah dosen baru
     */
    public function createDosen($data)
    {
        $password = password_hash($data['password'], PASSWORD_BCRYPT);
        $role_id = 2; // otomatis dosen

        $stmt = $this->db->prepare(
            "INSERT INTO admin (username, password, role_id, created_at)
             VALUES (?, ?, ?, NOW())"
        );

        $stmt->bind_param("ssi", $data['username'], $password, $role_id);

        return $stmt->execute();
    }

    /**
     * Update dosen
     */
    public function updateDosen($id, $data)
    {
        if (!empty($data['password'])) {
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt = $this->db->prepare(
                "UPDATE admin SET username = ?, password = ? WHERE user_id = ? AND role_id = 2"
            );
            $stmt->bind_param("ssi", $data['username'], $password, $id);

        } else {
            // tanpa update password
            $stmt = $this->db->prepare(
                "UPDATE admin SET username = ? WHERE user_id = ? AND role_id = 2"
            );
            $stmt->bind_param("si", $data['username'], $id);
        }

        return $stmt->execute();
    }

    /**
     * Hapus dosen
     */
    public function deleteDosen($id)
    {
        $stmt = $this->db->prepare("DELETE FROM admin WHERE user_id = ? AND role_id = 2");
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}
