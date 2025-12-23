<?php

require_once __DIR__ . '/../../models/dosen_model.php';

class DosenController
{
    private $model;

    public function __construct()
    {
        $this->model = new DosenModel();
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
        // Cek kelengkapan data
        $required = ['nama_lengkap', 'nidn', 'username', 'password', 'email'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                return ['success' => false, 'message' => "$field wajib diisi"];
            }
        }

        // Validasi: Cek duplikasi
        if ($this->model->usernameExists($_POST['username'])) {
            return ['success' => false, 'message' => 'Username sudah digunakan'];
        }

        if ($this->model->nidnExists($_POST['nidn'])) {
            return ['success' => false, 'message' => 'NIDN sudah terdaftar'];
        }

        if ($this->model->emailExists($_POST['email'])) {
            return ['success' => false, 'message' => 'Email sudah terdaftar'];
        }

        // Siapkan data
        $data = [
            'nama_lengkap' => $_POST['nama_lengkap'],
            'nidn'         => $_POST['nidn'],
            'username'     => $_POST['username'],
            'password'     => password_hash($_POST['password'], PASSWORD_DEFAULT), // Hash password
            'email'        => $_POST['email'],
            'no_telepon'   => $_POST['no_telepon'] ?? null,
            'alamat'       => $_POST['alamat'] ?? null
        ];

        // Simpan ke Model
        if ($this->model->create($data)) {
            return ['success' => true, 'message' => 'Dosen berhasil ditambahkan'];
        } else {
            return ['success' => false, 'message' => 'Gagal menambahkan data dosen.'];
        }
    }

    public function update()
    {
        if (empty($_POST['dosen_id'])) {
            return ['success' => false, 'message' => 'ID tidak valid'];
        }

        $id = $_POST['dosen_id'];

        // Validasi Update (Kecualikan ID sendiri dari pengecekan duplikat)
        if ($this->model->nidnExists($_POST['nidn'], $id)) {
            return ['success' => false, 'message' => 'NIDN sudah terdaftar pada dosen lain'];
        }

        if ($this->model->emailExists($_POST['email'], $id)) {
            return ['success' => false, 'message' => 'Email sudah terdaftar pada dosen lain'];
        }

        // Siapkan data update (Tidak termasuk password/username)
        $data = [
            'nama_lengkap' => $_POST['nama_lengkap'],
            'nidn'         => $_POST['nidn'],
            'email'        => $_POST['email'],
            'no_telepon'   => $_POST['no_telepon'] ?? null,
            'alamat'       => $_POST['alamat'] ?? null
        ];

        // Simpan ke Model
        if ($this->model->update($id, $data)) {
            return ['success' => true, 'message' => 'Data dosen diperbarui'];
        } else {
            return ['success' => false, 'message' => 'Gagal memperbarui data dosen.'];
        }
    }

    public function delete()
    {
        if (empty($_POST['dosen_id'])) {
            return ['success' => false, 'message' => 'ID tidak valid'];
        }

        if ($this->model->delete($_POST['dosen_id'])) {
            return ['success' => true, 'message' => 'Dosen berhasil dihapus'];
        } else {
            return ['success' => false, 'message' => 'Gagal menghapus dosen.'];
        }
    }
}