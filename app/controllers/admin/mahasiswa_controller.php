<?php
// PBL8/app/controllers/admin/MahasiswaController.php

class MahasiswaControllerAdmin {
    private $mahasiswaModel;
    
    public function __construct() {
        // Load config
        include __DIR__ . '/../../../config/config.php';
        
        // Load model
        require_once __DIR__ . '/../../models/mahasiswa_model.php';
        
        $this->mahasiswaModel = new MahasiswaModel($config);
    }
    
    /**
     * Get all mahasiswa
     */
    public function index() {
        return $this->mahasiswaModel->getAll();
    }
    
    /**
     * Get all jurusan
     */
    public function getAllJurusan() {
        return $this->mahasiswaModel->getAllJurusan();
    }
    
    /**
     * Get all prodi
     */
    public function getAllProdi() {
        return $this->mahasiswaModel->getAllProdi();
    }
    
    /**
     * Create mahasiswa
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => 'Invalid request method'];
        }
        
        // Validasi input
        $required = ['nim', 'nama_lengkap', 'username', 'password', 'jurusan_id', 'prodi_id', 'kelas'];
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
        
        // Check if email already exists (if provided)
        if (!empty($_POST['email']) && $this->mahasiswaModel->emailExists($_POST['email'])) {
            return ['success' => false, 'message' => 'Email sudah digunakan'];
        }
        
        $data = [
            'nim' => trim($_POST['nim']),
            'nama_lengkap' => trim($_POST['nama_lengkap']),
            'username' => trim($_POST['username']),
            'password' => $_POST['password'],
            'jurusan_id' => intval($_POST['jurusan_id']),
            'prodi_id' => intval($_POST['prodi_id']),
            'kelas' => trim($_POST['kelas']),
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
        $required = ['nim', 'nama_lengkap', 'jurusan_id', 'prodi_id', 'kelas'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                return ['success' => false, 'message' => "Field $field harus diisi"];
            }
        }
        
        // Check if NIM already exists (exclude current mahasiswa)
        if ($this->mahasiswaModel->nimExists($_POST['nim'], $id)) {
            return ['success' => false, 'message' => 'NIM sudah digunakan oleh mahasiswa lain'];
        }
        
        // Check if email already exists (if provided and exclude current mahasiswa)
        if (!empty($_POST['email']) && $this->mahasiswaModel->emailExists($_POST['email'], $id)) {
            return ['success' => false, 'message' => 'Email sudah digunakan oleh mahasiswa lain'];
        }
        
        $data = [
            'nim' => trim($_POST['nim']),
            'nama_lengkap' => trim($_POST['nama_lengkap']),
            'jurusan_id' => intval($_POST['jurusan_id']),
            'prodi_id' => intval($_POST['prodi_id']),
            'kelas' => trim($_POST['kelas']),
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
    
    /**
     * Check if NIM exists (for AJAX validation)
     */
    public function checkNimExists($nim, $excludeId = null) {
        return $this->mahasiswaModel->nimExists($nim, $excludeId);
    }
    
    /**
     * Check if username exists (for AJAX validation)
     */
    public function checkUsernameExists($username, $excludeId = null) {
        return $this->mahasiswaModel->usernameExists($username, $excludeId);
    }
    
    /**
     * Check if email exists (for AJAX validation)
     */
    public function checkEmailExists($email, $excludeId = null) {
        return $this->mahasiswaModel->emailExists($email, $excludeId);
    }
}
?>