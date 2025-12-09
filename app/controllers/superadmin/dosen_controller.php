<?php
// File: app/controllers/admin/dosen_controller.php

require_once __DIR__ . '/../../models/dosen_model.php';

class DosenController
{
    private $dosenModel;

    public function __construct()
    {
        $this->dosenModel = new DosenModel();
    }

    /**
     * Get all dosen (READ)
     */
    public function index()
    {
        try {
            return $this->dosenModel->getAllDosen();
        } catch (Exception $e) {
            error_log("Error in DosenController::index - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get dosen by ID
     */
    public function getById($id)
    {
        try {
            if (empty($id)) {
                return ['error' => 'ID dosen tidak valid'];
            }

            $dosen = $this->dosenModel->getDosenById($id);

            if (!$dosen) {
                return ['error' => 'Dosen tidak ditemukan'];
            }

            return $dosen;
        } catch (Exception $e) {
            error_log("Error in DosenController::getById - " . $e->getMessage());
            return ['error' => 'Terjadi kesalahan saat mengambil data dosen'];
        }
    }

    /**
     * Create new dosen (CREATE)
     */
    public function create()
    {
        try {
            // Validasi input required
            $requiredFields = ['nama_lengkap', 'nidn', 'username', 'password', 'email'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    return [
                        'success' => false,
                        'message' => 'Field ' . str_replace('_', ' ', $field) . ' harus diisi'
                    ];
                }
            }

            // Validasi format email
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                return [
                    'success' => false,
                    'message' => 'Format email tidak valid'
                ];
            }

            // Validasi panjang password minimal 6 karakter
            if (strlen($_POST['password']) < 6) {
                return [
                    'success' => false,
                    'message' => 'Password minimal 6 karakter'
                ];
            }

            // Cek apakah NIDN sudah ada
            if ($this->dosenModel->checkNidnExists($_POST['nidn'])) {
                return [
                    'success' => false,
                    'message' => 'NIDN sudah terdaftar'
                ];
            }

            // Cek apakah username sudah ada
            if ($this->dosenModel->checkUsernameExists($_POST['username'])) {
                return [
                    'success' => false,
                    'message' => 'Username sudah digunakan'
                ];
            }

            // Cek apakah email sudah ada
            if ($this->dosenModel->checkEmailExists($_POST['email'])) {
                return [
                    'success' => false,
                    'message' => 'Email sudah terdaftar'
                ];
            }

            // Prepare data
            $data = [
                'nama_lengkap' => trim($_POST['nama_lengkap']),
                'nidn' => trim($_POST['nidn']),
                'username' => trim($_POST['username']),
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'email' => trim($_POST['email']),
                'no_telepon' => isset($_POST['no_telepon']) ? trim($_POST['no_telepon']) : null,
                'alamat' => isset($_POST['alamat']) ? trim($_POST['alamat']) : null,
                'role_id' => 2 // role_id 2 untuk dosen (sesuaikan dengan database Anda)
            ];

            // Insert dosen
            $result = $this->dosenModel->createDosen($data);

            if ($result) {
                return [
                    'success' => true,
                    'message' => 'Dosen berhasil ditambahkan'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal menambahkan dosen'
                ];
            }
        } catch (Exception $e) {
            error_log("Error in DosenController::create - " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update dosen (UPDATE)
     */
    public function update()
    {
        try {
            // Validasi input
            if (empty($_POST['dosen_id'])) {
                return [
                    'success' => false,
                    'message' => 'ID dosen tidak valid'
                ];
            }

            $requiredFields = ['nama_lengkap', 'nidn', 'email'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    return [
                        'success' => false,
                        'message' => 'Field ' . str_replace('_', ' ', $field) . ' harus diisi'
                    ];
                }
            }

            // Validasi format email
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                return [
                    'success' => false,
                    'message' => 'Format email tidak valid'
                ];
            }

            $dosenId = intval($_POST['dosen_id']);

            // Cek apakah NIDN sudah digunakan oleh dosen lain
            if ($this->dosenModel->checkNidnExistsExcept($_POST['nidn'], $dosenId)) {
                return [
                    'success' => false,
                    'message' => 'NIDN sudah digunakan oleh dosen lain'
                ];
            }

            // Cek apakah email sudah digunakan oleh dosen lain
            if ($this->dosenModel->checkEmailExistsExcept($_POST['email'], $dosenId)) {
                return [
                    'success' => false,
                    'message' => 'Email sudah digunakan oleh dosen lain'
                ];
            }

            // Prepare data
            $data = [
                'dosen_id' => $dosenId,
                'nama_lengkap' => trim($_POST['nama_lengkap']),
                'nidn' => trim($_POST['nidn']),
                'email' => trim($_POST['email']),
                'no_telepon' => isset($_POST['no_telepon']) ? trim($_POST['no_telepon']) : null,
                'alamat' => isset($_POST['alamat']) ? trim($_POST['alamat']) : null
            ];

            // Update dosen
            $result = $this->dosenModel->updateDosen($data);

            if ($result) {
                return [
                    'success' => true,
                    'message' => 'Data dosen berhasil diperbarui'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal memperbarui data dosen'
                ];
            }
        } catch (Exception $e) {
            error_log("Error in DosenController::update - " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Delete dosen (DELETE)
     */
    public function delete()
    {
        try {
            if (empty($_POST['dosen_id'])) {
                return [
                    'success' => false,
                    'message' => 'ID dosen tidak valid'
                ];
            }

            $dosenId = intval($_POST['dosen_id']);

            // Cek apakah dosen ada
            $dosen = $this->dosenModel->getDosenById($dosenId);
            if (!$dosen) {
                return [
                    'success' => false,
                    'message' => 'Dosen tidak ditemukan'
                ];
            }

            // Opsional: Cek apakah dosen memiliki relasi dengan data lain
            // Misalnya cek apakah dosen masih mengajar mata kuliah
            // if ($this->dosenModel->hasActiveClasses($dosenId)) {
            //     return [
            //         'success' => false,
            //         'message' => 'Dosen tidak dapat dihapus karena masih memiliki kelas aktif'
            //     ];
            // }

            $result = $this->dosenModel->deleteDosen($dosenId);

            if ($result) {
                return [
                    'success' => true,
                    'message' => 'Dosen berhasil dihapus'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal menghapus dosen'
                ];
            }
        } catch (Exception $e) {
            error_log("Error in DosenController::delete - " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Search dosen by keyword
     */
    public function search($keyword)
    {
        try {
            if (empty($keyword)) {
                return $this->index();
            }

            return $this->dosenModel->searchDosen($keyword);
        } catch (Exception $e) {
            error_log("Error in DosenController::search - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get dosen by user_id (untuk profile dosen yang sedang login)
     */
    public function getByUserId($userId)
    {
        try {
            if (empty($userId)) {
                return ['error' => 'User ID tidak valid'];
            }

            $dosen = $this->dosenModel->getDosenByUserId($userId);

            if (!$dosen) {
                return ['error' => 'Dosen tidak ditemukan'];
            }

            return $dosen;
        } catch (Exception $e) {
            error_log("Error in DosenController::getByUserId - " . $e->getMessage());
            return ['error' => 'Terjadi kesalahan saat mengambil data dosen'];
        }
    }

    /**
     * Count total dosen
     */
    public function countTotal()
    {
        try {
            return $this->dosenModel->countDosen();
        } catch (Exception $e) {
            error_log("Error in DosenController::countTotal - " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Update password dosen
     */
    public function updatePassword()
    {
        try {
            if (empty($_POST['dosen_id']) || empty($_POST['new_password'])) {
                return [
                    'success' => false,
                    'message' => 'Data tidak lengkap'
                ];
            }

            // Validasi panjang password
            if (strlen($_POST['new_password']) < 6) {
                return [
                    'success' => false,
                    'message' => 'Password minimal 6 karakter'
                ];
            }

            $dosenId = intval($_POST['dosen_id']);
            $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

            $result = $this->dosenModel->updatePassword($dosenId, $newPassword);

            if ($result) {
                return [
                    'success' => true,
                    'message' => 'Password berhasil diperbarui'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal memperbarui password'
                ];
            }
        } catch (Exception $e) {
            error_log("Error in DosenController::updatePassword - " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }
}