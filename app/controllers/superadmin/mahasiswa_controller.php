<?php
require_once __DIR__ . '/../../models/mahasiswa_model.php';

class MahasiswaControllerSuperadmin
{
    private $model;

    public function __construct()
    {
        // MODEL TIDAK MENERIMA PARAMETER
        $this->model = new MahasiswaModel();
    }

    public function index()
    {
        return $this->model->getAll();
    }

    public function getById($id)
    {
        return $this->model->getById($id);
    }

    public function create()
    {
        // FIELD YANG BENAR-BENAR ADA DI FORM
        $required = ['nim', 'nama_lengkap', 'username', 'password', 'jurusan', 'prodi', 'kelas'];

        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                return ['success' => false, 'message' => 'Field ' . $field . ' wajib diisi'];
            }
        }

        if ($this->model->nimExists($_POST['nim'])) {
            return ['success' => false, 'message' => 'NIM sudah digunakan'];
        }

        if ($this->model->usernameExists($_POST['username'])) {
            return ['success' => false, 'message' => 'Username sudah digunakan'];
        }

        if (!empty($_POST['email']) && $this->model->emailExists($_POST['email'])) {
            return ['success' => false, 'message' => 'Email sudah digunakan'];
        }

        // SESUAI MODEL
        $data = [
            'nim' => trim($_POST['nim']),
            'nama_lengkap' => trim($_POST['nama_lengkap']),
            'username' => trim($_POST['username']),
            'password' => $_POST['password'], // HASH DI MODEL
            'jurusan_id' => null, // sementara null
            'prodi_id' => null,   // sementara null
            'kelas' => null,
            'email' => $_POST['email'] ?? null,
            'alamat' => $_POST['alamat'] ?? null,
        ];

        return $this->model->create($data);
    }

    public function update()
    {
        if (empty($_POST['mahasiswa_id'])) {
            return ['success' => false, 'message' => 'ID tidak valid'];
        }

        $id = (int) $_POST['mahasiswa_id'];

        if ($this->model->nimExists($_POST['nim'], $id)) {
            return ['success' => false, 'message' => 'NIM sudah digunakan'];
        }

        if ($this->model->usernameExists($_POST['username'], $id)) {
            return ['success' => false, 'message' => 'Username sudah digunakan'];
        }

        if (!empty($_POST['email']) && $this->model->emailExists($_POST['email'], $id)) {
            return ['success' => false, 'message' => 'Email sudah digunakan'];
        }

        $data = [
            'nim' => trim($_POST['nim']),
            'nama_lengkap' => trim($_POST['nama_lengkap']),
            'jurusan_id' => null,
            'prodi_id' => null,
            'kelas' => null,
            'email' => $_POST['email'] ?? null,
            'alamat' => $_POST['alamat'] ?? null
        ];

        return $this->model->update($id, $data);
    }

    public function delete()
    {
        if (empty($_POST['mahasiswa_id'])) {
            return ['success' => false, 'message' => 'ID tidak valid'];
        }

        return $this->model->delete((int) $_POST['mahasiswa_id']);
    }
}
?>