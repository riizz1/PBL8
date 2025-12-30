<?php
// PBL8/app/controllers/superadmin/mahasiswa_controller.php

class MahasiswaControllerSuperadmin {
    private $mahasiswaModel;
    
    public function __construct() {
        require_once __DIR__ . '/../../models/mahasiswa_model.php';
        $this->mahasiswaModel = new MahasiswaModelSuperadmin();
    }
    
    public function index() {
        return $this->mahasiswaModel->getAll();
    }
    
    public function getAllJurusan() {
        return $this->mahasiswaModel->getAllJurusan();
    }
    
    public function getAllProdi() {
        return $this->mahasiswaModel->getAllProdi();
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => 'Invalid request method'];
        }
        
        // Validasi input required
        $required = ['nim', 'nama_lengkap', 'username', 'password', 'jurusan_id', 'prodi_id', 'kelas'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                return ['success' => false, 'message' => "Field $field harus diisi"];
            }
        }
        
        // Check NIM
        if ($this->mahasiswaModel->nimExists($_POST['nim'])) {
            return ['success' => false, 'message' => 'NIM sudah terdaftar'];
        }
        
        // Check username
        if ($this->mahasiswaModel->usernameExists($_POST['username'])) {
            return ['success' => false, 'message' => 'Username sudah digunakan'];
        }
        
        // Check email (jika ada)
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
        
        $result = $this->mahasiswaModel->create($data);
        
        if ($result) {
            return ['success' => true, 'message' => 'Mahasiswa berhasil ditambahkan'];
        } else {
            return ['success' => false, 'message' => 'Gagal menambahkan mahasiswa'];
        }
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => 'Invalid request method'];
        }
        
        $id = intval($_POST['mahasiswa_id']);
        
        // Validasi input required
        $required = ['nim', 'nama_lengkap', 'username', 'jurusan_id', 'prodi_id', 'kelas'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                return ['success' => false, 'message' => "Field $field harus diisi"];
            }
        }
        
        // Check NIM (exclude current)
        if ($this->mahasiswaModel->nimExists($_POST['nim'], $id)) {
            return ['success' => false, 'message' => 'NIM sudah digunakan oleh mahasiswa lain'];
        }
        
        // Check username (exclude current)
        if ($this->mahasiswaModel->usernameExists($_POST['username'], $id)) {
            return ['success' => false, 'message' => 'Username sudah digunakan oleh mahasiswa lain'];
        }
        
        // Check email (jika ada, exclude current)
        if (!empty($_POST['email']) && $this->mahasiswaModel->emailExists($_POST['email'], $id)) {
            return ['success' => false, 'message' => 'Email sudah digunakan oleh mahasiswa lain'];
        }
        
        $data = [
            'nim' => trim($_POST['nim']),
            'nama_lengkap' => trim($_POST['nama_lengkap']),
            'username' => trim($_POST['username']),
            'password' => trim($_POST['password'] ?? ''),
            'jurusan_id' => intval($_POST['jurusan_id']),
            'prodi_id' => intval($_POST['prodi_id']),
            'kelas' => trim($_POST['kelas']),
            'email' => trim($_POST['email'] ?? ''),
            'alamat' => trim($_POST['alamat'] ?? '')
        ];
        
        $result = $this->mahasiswaModel->update($id, $data);
        
        if ($result) {
            return ['success' => true, 'message' => 'Data mahasiswa berhasil diperbarui'];
        } else {
            return ['success' => false, 'message' => 'Gagal memperbarui data mahasiswa'];
        }
    }
    
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => 'Invalid request method'];
        }
        
        $id = intval($_POST['mahasiswa_id']);
        
        if ($id <= 0) {
            return ['success' => false, 'message' => 'ID tidak valid'];
        }
        
        $result = $this->mahasiswaModel->delete($id);
        
        if ($result) {
            return ['success' => true, 'message' => 'Mahasiswa berhasil dihapus'];
        } else {
            return ['success' => false, 'message' => 'Gagal menghapus mahasiswa'];
        }
    }
    
    public function getById($id) {
        return $this->mahasiswaModel->getById($id);
    }
    
    public function checkNimExists($nim, $excludeId = null) {
        return $this->mahasiswaModel->nimExists($nim, $excludeId);
    }
    
    public function checkUsernameExists($username, $excludeId = null) {
        return $this->mahasiswaModel->usernameExists($username, $excludeId);
    }
    
    public function checkEmailExists($email, $excludeId = null) {
        return $this->mahasiswaModel->emailExists($email, $excludeId);
    }
}
?>