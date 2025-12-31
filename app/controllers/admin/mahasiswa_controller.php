<?php
// app/controllers/admin/mahasiswa_controller.php

class MahasiswaControllerAdmin
{
    private $mahasiswaModel;

    public function __construct()
    {
        require_once __DIR__ . '/../../models/mahasiswa_model.php';
        $this->mahasiswaModel = new MahasiswaModelAdmin();
    }

    public function index()
    {
        return $this->mahasiswaModel->getAll();
    }

    public function getAllJurusan()
    {
        return $this->mahasiswaModel->getAllJurusan();
    }

    public function getAllProdi()
    {
        return $this->mahasiswaModel->getAllProdi();
    }

    /**
     * ✅ PERBAIKAN: Terima parameter $post
     */
    public function create($post)
    {
        // Validasi input required
        $required = ['nim', 'nama_lengkap', 'username', 'password', 'jurusan_id', 'prodi_id', 'kelas'];
        foreach ($required as $field) {
            if (empty($post[$field])) {
                return ['success' => false, 'message' => "Field $field harus diisi"];
            }
        }

        // ✅ CEK USERNAME DI SEMUA TABEL (mahasiswa & admin)
        require_once __DIR__ . '/../../helpers/auth_helper.php';
        require_once __DIR__ . '/../../../config/config.php';

        $auth = new AuthHelper($config);

        if ($auth->isUsernameExists(trim($post['username']))) {
            return [
                'success' => false,
                'message' => 'Username sudah digunakan oleh pengguna lain (mahasiswa/dosen/admin)'
            ];
        }

        // Check NIM (hanya di tabel mahasiswa)
        if ($this->mahasiswaModel->nimExists($post['nim'])) {
            return ['success' => false, 'message' => 'NIM sudah terdaftar'];
        }

        // Check email (jika ada, hanya di tabel mahasiswa)
        if (!empty($post['email']) && $this->mahasiswaModel->emailExists($post['email'])) {
            return ['success' => false, 'message' => 'Email sudah digunakan'];
        }

        $data = [
            'nim' => trim($post['nim']),
            'nama_lengkap' => trim($post['nama_lengkap']),
            'username' => trim($post['username']),
            'password' => $post['password'],
            'jurusan_id' => intval($post['jurusan_id']),
            'prodi_id' => intval($post['prodi_id']),
            'kelas' => trim($post['kelas']),
            'email' => trim($post['email'] ?? ''),
            'alamat' => trim($post['alamat'] ?? '')
        ];

        $result = $this->mahasiswaModel->create($data);

        if ($result) {
            return ['success' => true, 'message' => 'Mahasiswa berhasil ditambahkan'];
        } else {
            return ['success' => false, 'message' => 'Gagal menambahkan mahasiswa'];
        }
    }

    /**
     * ✅ PERBAIKAN: Terima parameter $post
     */
    public function update($post)
    {
        $id = intval($post['mahasiswa_id']);

        // Validasi input required
        $required = ['nim', 'nama_lengkap', 'jurusan_id', 'prodi_id', 'kelas'];
        foreach ($required as $field) {
            if (empty($post[$field])) {
                return ['success' => false, 'message' => "Field $field harus diisi"];
            }
        }

        // Check NIM (exclude current)
        if ($this->mahasiswaModel->nimExists($post['nim'], $id)) {
            return ['success' => false, 'message' => 'NIM sudah digunakan oleh mahasiswa lain'];
        }

        // Check email (jika ada, exclude current)
        if (!empty($post['email']) && $this->mahasiswaModel->emailExists($post['email'], $id)) {
            return ['success' => false, 'message' => 'Email sudah digunakan oleh mahasiswa lain'];
        }

        $data = [
            'nim' => trim($post['nim']),
            'nama_lengkap' => trim($post['nama_lengkap']),
            'jurusan_id' => intval($post['jurusan_id']),
            'prodi_id' => intval($post['prodi_id']),
            'kelas' => trim($post['kelas']),
            'email' => trim($post['email'] ?? ''),
            'alamat' => trim($post['alamat'] ?? '')
        ];

        $result = $this->mahasiswaModel->update($id, $data);

        if ($result) {
            return ['success' => true, 'message' => 'Data mahasiswa berhasil diperbarui'];
        } else {
            return ['success' => false, 'message' => 'Gagal memperbarui data mahasiswa'];
        }
    }

    /**
     * ✅ PERBAIKAN: Terima parameter $post
     */
    public function delete($post)
    {
        $id = intval($post['mahasiswa_id']);

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

    public function getById($id)
    {
        return $this->mahasiswaModel->getById($id);
    }

    public function checkNimExists($nim, $excludeId = null)
    {
        return $this->mahasiswaModel->nimExists($nim, $excludeId);
    }

    public function checkUsernameExists($username, $excludeId = null)
    {
        return $this->mahasiswaModel->usernameExists($username, $excludeId);
    }

    public function checkEmailExists($email, $excludeId = null)
    {
        return $this->mahasiswaModel->emailExists($email, $excludeId);
    }
}
?>