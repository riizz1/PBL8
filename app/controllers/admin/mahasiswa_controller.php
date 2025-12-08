<?php
// PBL8/app/controllers/admin/MahasiswaController.php

class MahasiswaController {
    private $mahasiswaModel;
    
    public function __construct() {
        // Load config
        include __DIR__ . '/../../../config/config.php';
        
        // Load model
        require_once __DIR__ . '/../../models/mahasiswa_model.php';
        
        $this->mahasiswaModel = new Mahasiswa($config);
    }
    
    /**
     * Get all mahasiswa
     */
    public function index() {
        return $this->mahasiswaModel->getAll();
    }
    
    /**
     * Create mahasiswa
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => 'Invalid request method'];
        }
        
        // Validasi input
        $required = ['nim', 'nama_lengkap', 'username', 'password', 'prodi'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                return ['success' => false, 'message' => "Field $field harus diisi"];
            }
        }
        
        // Check if NIM already exists
        if ($this->mahasiswaModel->nimExists($_POST['nim'])) {
            return ['success' => false, 'message' => 'NIM sudah terdaftar'];
        }
        
        // Check if username already exists
        if ($this->mahasiswaModel->usernameExists($_POST['username'])) {
            return ['success' => false, 'message' => 'Username sudah digunakan'];
        }
        
        $data = [
            'nim' => trim($_POST['nim']),
            'nama_lengkap' => trim($_POST['nama_lengkap']),
            'username' => trim($_POST['username']),
            'password' => $_POST['password'],
            'prodi' => trim($_POST['prodi']),
            'email' => trim($_POST['email'] ?? ''),
            'alamat' => trim($_POST['alamat'] ?? '')
        ];
        
        return $this->mahasiswaModel->create($data);
    }
    
    /**
     * Update mahasiswa
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => 'Invalid request method'];
        }
        
        $id = intval($_POST['mahasiswa_id']);
        
        // Validasi input
        $required = ['nim', 'nama_lengkap', 'prodi'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                return ['success' => false, 'message' => "Field $field harus diisi"];
            }
        }
        
        // Check if NIM already exists (exclude current mahasiswa)
        if ($this->mahasiswaModel->nimExists($_POST['nim'], $id)) {
            return ['success' => false, 'message' => 'NIM sudah digunakan oleh mahasiswa lain'];
        }
        
        $data = [
            'nim' => trim($_POST['nim']),
            'nama_lengkap' => trim($_POST['nama_lengkap']),
            'prodi' => trim($_POST['prodi']),
            'email' => trim($_POST['email'] ?? ''),
            'alamat' => trim($_POST['alamat'] ?? '')
        ];
        
        return $this->mahasiswaModel->update($id, $data);
    }
    
    /**
     * Delete mahasiswa
     */
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => 'Invalid request method'];
        }
        
        $id = intval($_POST['mahasiswa_id']);
        
        if ($id <= 0) {
            return ['success' => false, 'message' => 'ID tidak valid'];
        }
        
        return $this->mahasiswaModel->delete($id);
    }
    
    /**
     * Get mahasiswa by ID (for edit)
     */
    public function getById($id) {
        return $this->mahasiswaModel->getById($id);
    }
}
?>