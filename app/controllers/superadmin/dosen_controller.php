<?php
// app/controllers/superadmin/dosen_controller.php

require_once __DIR__ . '/../../models/dosen_model.php';

class DosenController
{
    private $model;

    public function __construct()
    {
        $this->model = new DosenModel();
    }

    /**
     * Get all dosen
     */
    public function index()
    {
        return $this->model->getAll();
    }

    /**
     * Get dosen by ID
     */
    public function getById($id)
    {
        return $this->model->getById($id);
    }

    /**
     * Create new dosen
     */
    public function create()
    {
        try {
            // Validasi field required
            $required = ['nama_lengkap', 'nidn', 'username', 'password', 'email'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    return [
                        'success' => false,
                        'message' => ucfirst(str_replace('_', ' ', $field)) . " wajib diisi"
                    ];
                }
            }

            // Validasi format email
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'message' => 'Format email tidak valid'];
            }

            // Cek duplikasi Username
            if ($this->model->usernameExists(trim($_POST['username']))) {
                return [
                    'success' => false,
                    'message' => 'Username sudah digunakan'
                ];
            }

            // Cek duplikasi NIDN
            if ($this->model->nidnExists(trim($_POST['nidn']))) {
                return ['success' => false, 'message' => 'NIDN sudah terdaftar'];
            }

            // Cek duplikasi Email
            if ($this->model->emailExists(trim($_POST['email']))) {
                return ['success' => false, 'message' => 'Email sudah terdaftar'];
            }

            // Siapkan data untuk insert
            $data = [
                'username' => trim($_POST['username']),
                'nama_lengkap' => trim($_POST['nama_lengkap']),
                'nidn' => trim($_POST['nidn']),
                'email' => trim($_POST['email']),
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'no_telepon' => isset($_POST['no_telepon']) ? trim($_POST['no_telepon']) : null,
                'alamat' => isset($_POST['alamat']) ? trim($_POST['alamat']) : null,
                'jenis_kelamin' => isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] !== '' ? $_POST['jenis_kelamin'] : null,
                'jabatan' => isset($_POST['jabatan']) ? trim($_POST['jabatan']) : null
            ];

            // Simpan ke database
            if ($this->model->create($data)) {
                return [
                    'success' => true,
                    'message' => 'Dosen berhasil ditambahkan'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal menambahkan dosen. Silakan coba lagi.'
                ];
            }
        } catch (Exception $e) {
            error_log("Error in DosenController::create: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update dosen
     */
    public function update()
    {
        try {
            // Validasi ID
            if (empty($_POST['dosen_id'])) {
                return ['success' => false, 'message' => 'ID tidak valid'];
            }

            $id = intval($_POST['dosen_id']);

            // Validasi field required
            $required = ['nama_lengkap', 'nidn', 'username', 'email'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    return [
                        'success' => false,
                        'message' => ucfirst(str_replace('_', ' ', $field)) . " wajib diisi"
                    ];
                }
            }

            // Validasi format email
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'message' => 'Format email tidak valid'];
            }

            // Cek duplikasi Username (kecuali ID sendiri)
            if ($this->model->usernameExists(trim($_POST['username']), $id)) {
                return [
                    'success' => false,
                    'message' => 'Username sudah digunakan oleh dosen lain'
                ];
            }

            // Cek duplikasi NIDN (kecuali ID sendiri)
            if ($this->model->nidnExists(trim($_POST['nidn']), $id)) {
                return ['success' => false, 'message' => 'NIDN sudah terdaftar pada dosen lain'];
            }

            // Cek duplikasi Email (kecuali ID sendiri)
            if ($this->model->emailExists(trim($_POST['email']), $id)) {
                return ['success' => false, 'message' => 'Email sudah terdaftar pada dosen lain'];
            }

            // Siapkan data untuk update
            $data = [
                'username' => trim($_POST['username']),
                'nama_lengkap' => trim($_POST['nama_lengkap']),
                'nidn' => trim($_POST['nidn']),
                'email' => trim($_POST['email']),
                'no_telepon' => isset($_POST['no_telepon']) ? trim($_POST['no_telepon']) : null,
                'alamat' => isset($_POST['alamat']) ? trim($_POST['alamat']) : null,
                'jenis_kelamin' => isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] !== '' ? $_POST['jenis_kelamin'] : null,
                'jabatan' => isset($_POST['jabatan']) ? trim($_POST['jabatan']) : null
            ];

            // Update data
            if ($this->model->update($id, $data)) {
                // Jika password diisi, update password juga
                if (!empty($_POST['password'])) {
                    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $this->model->updatePassword($id, $hashedPassword);
                }

                return [
                    'success' => true,
                    'message' => 'Data dosen berhasil diperbarui'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal memperbarui data. Silakan coba lagi.'
                ];
            }
        } catch (Exception $e) {
            error_log("Error in DosenController::update: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Delete dosen
     */
    public function delete()
    {
        try {
            if (empty($_POST['dosen_id'])) {
                return ['success' => false, 'message' => 'ID tidak valid'];
            }

            $id = intval($_POST['dosen_id']);

            // Cegah penghapusan akun sendiri
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
                return [
                    'success' => false,
                    'message' => 'Tidak dapat menghapus akun Anda sendiri'
                ];
            }

            // Hapus akun
            if ($this->model->delete($id)) {
                return [
                    'success' => true,
                    'message' => 'Dosen berhasil dihapus'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal menghapus dosen. Silakan coba lagi.'
                ];
            }
        } catch (Exception $e) {
            error_log("Error in DosenController::delete: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check Username exists (untuk validasi realtime)
     */
    public function checkUsernameExists($username, $excludeId = null)
    {
        return $this->model->usernameExists($username, $excludeId);
    }

    /**
     * Check NIDN exists (untuk validasi realtime)
     */
    public function checkNidnExists($nidn, $excludeId = null)
    {
        return $this->model->nidnExists($nidn, $excludeId);
    }

    /**
     * Check Email exists (untuk validasi realtime)
     */
    public function checkEmailExists($email, $excludeId = null)
    {
        return $this->model->emailExists($email, $excludeId);
    }
}