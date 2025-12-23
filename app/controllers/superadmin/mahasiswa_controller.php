<?php
require_once __DIR__ . '/../../models/mahasiswa_model.php';
require_once __DIR__ . '/../../../config/config.php';

class MahasiswaControllerSuperadmin {
    private $model;

    public function __construct() {
        global $db;
        $this->model = new MahasiswaModel($db);
    }

    public function index() { return $this->model->getAll(); }
    public function getById($id) { return $this->model->getById($id); }

    public function create() {
        $required = ['nim', 'nama_lengkap', 'username', 'password', 'jurusan_id', 'prodi_id', 'kelas'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) return ['success' => false, 'message' => 'Field ' . $field . ' wajib diisi'];
        }
        if ($this->model->nimExists($_POST['nim'])) return ['success' => false, 'message' => 'NIM sudah digunakan'];
        if ($this->model->usernameExists($_POST['username'])) return ['success' => false, 'message' => 'Username sudah digunakan'];
        if (!empty($_POST['email']) && $this->model->emailExists($_POST['email'])) return ['success' => false, 'message' => 'Email sudah digunakan'];

        $data = [
            'nim' => trim($_POST['nim']),
            'nama_lengkap' => trim($_POST['nama_lengkap']),
            'username' => trim($_POST['username']),
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'jurusan_id' => (int)$_POST['jurusan_id'],
            'prodi_id' => (int)$_POST['prodi_id'],
            'kelas' => trim($_POST['kelas']),
            'email' => $_POST['email'] ?? null,
            'alamat' => $_POST['alamat'] ?? null
        ];

        return $this->model->create($data) ? ['success' => true, 'message' => 'Mahasiswa berhasil ditambahkan'] : ['success' => false, 'message' => 'Gagal menambahkan'];
    }

    public function update() {
        if (empty($_POST['mahasiswa_id'])) return ['success' => false, 'message' => 'ID tidak valid'];
        $id = (int)$_POST['mahasiswa_id'];

        if ($this->model->nimExists($_POST['nim'], $id)) return ['success' => false, 'message' => 'NIM sudah digunakan'];
        if ($this->model->usernameExists($_POST['username'], $id)) return ['success' => false, 'message' => 'Username sudah digunakan'];
        if (!empty($_POST['email']) && $this->model->emailExists($_POST['email'], $id)) return ['success' => false, 'message' => 'Email sudah digunakan'];

        $data = [
            'nim' => trim($_POST['nim']),
            'nama_lengkap' => trim($_POST['nama_lengkap']),
            'username' => trim($_POST['username']),
            'password' => !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null,
            'jurusan_id' => (int)$_POST['jurusan_id'],
            "prodi_id" => (int)$_POST['prodi_id'],
            'kelas' => trim($_POST['kelas']),
            'email' => $_POST['email'] ?? null,
            'alamat' => $_POST['alamat'] ?? null
        ];

        return $this->model->update($id, $data) ? ['success' => true, 'message' => 'Data diperbarui'] : ['success' => false, 'message' => 'Gagal memperbarui'];
    }

    public function delete() {
        if (empty($_POST['mahasiswa_id'])) return ['success' => false, 'message' => 'ID tidak valid'];
        return $this->model->delete((int)$_POST['mahasiswa_id']) ? ['success' => true, 'message' => 'Mahasiswa berhasil dihapus'] : ['success' => false, 'message' => 'Gagal menghapus'];
    }
}
?>