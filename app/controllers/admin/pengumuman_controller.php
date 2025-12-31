<?php
// app/controllers/admin/pengumuman_controller.php

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../models/pengumuman_model.php';

class PengumumanControllerAdmin
{
    private $model;

    public function __construct()
    {
        global $config;
        $this->model = new PengumumanModel($config);
    }

    public function index()
    {
        return [
            "pengumuman" => $this->model->getAll(),
            "kategori" => $this->model->getKategori(),
            "jurusan" => $this->model->getAllJurusan(),
            "prodi" => $this->model->getAllProdi()
        ];
    }

    public function getById($id)
    {
        return $this->model->getById($id);
    }

    public function getKelasByProdi($prodi_id)
    {
        return $this->model->getKelasByProdi($prodi_id);
    }

    /**
     * function create
     */
    public function create($post)
    {
        // Validasi input
        $required = ['judul', 'isi', 'target_type', 'kategori_id'];
        foreach ($required as $field) {
            if (empty($post[$field])) {
                return ['success' => false, 'message' => "Field $field harus diisi"];
            }
        }

        // Ambil user_id dari session
        if (!isset($_SESSION['user_id'])) {
            session_start();
        }
        
        $createdBy = $_SESSION['user_id'] ?? null;

        if (!$createdBy) {
            return ['success' => false, 'message' => 'User tidak teridentifikasi'];
        }

        $data = [
            'judul' => trim($post['judul']),
            'isi' => trim($post['isi']),
            'target_type' => $post['target_type'],
            'target_jurusan_id' => !empty($post['target_jurusan_id']) ? intval($post['target_jurusan_id']) : null,
            'target_prodi_id' => !empty($post['target_prodi_id']) ? intval($post['target_prodi_id']) : null,
            'target_kelas' => !empty($post['target_kelas']) ? trim($post['target_kelas']) : null,
            'kategori_id' => intval($post['kategori_id']),
            'created_by' => $createdBy
        ];

        if ($this->model->create($data)) {
            return ['success' => true, 'message' => 'Pengumuman berhasil dibuat'];
        } else {
            return ['success' => false, 'message' => 'Gagal membuat pengumuman'];
        }
    }

    public function edit($post)
    {
        if (!isset($post['pengumuman_id'], $post['judul'], $post['kategori_id'], $post['isi'], $post['target_type'])) {
            return [
                'success' => false,
                'message' => 'Data tidak lengkap'
            ];
        }

        $targetJurusan = (!empty($post['target_jurusan_id'])) ? $post['target_jurusan_id'] : null;
        $targetProdi = (!empty($post['target_prodi_id'])) ? $post['target_prodi_id'] : null;
        $targetKelas = (!empty($post['target_kelas'])) ? $post['target_kelas'] : null;

        $targetData = [
            'target_type' => $post['target_type'],
            'target_jurusan_id' => $targetJurusan,
            'target_prodi_id' => $targetProdi,
            'target_kelas' => $targetKelas
        ];

        $result = $this->model->update(
            $post['pengumuman_id'],
            $post['judul'],
            $post['kategori_id'],
            $post['isi'],
            $targetData
        );

        if ($result) {
            return [
                'success' => true,
                'message' => 'Pengumuman berhasil diperbarui'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal memperbarui pengumuman'
            ];
        }
    }

    public function hapus($id)
    {
        if (!isset($id) || empty($id)) {
            return [
                'success' => false,
                'message' => 'ID tidak valid'
            ];
        }

        $result = $this->model->delete($id);

        if ($result) {
            return [
                'success' => true,
                'message' => 'Pengumuman berhasil dihapus'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menghapus pengumuman'
            ];
        }
    }

    public function kirimEmail($post)
    {
        if (!isset($post['pengumuman_id'])) {
            return [
                'success' => false,
                'message' => 'ID pengumuman tidak valid'
            ];
        }

        require_once __DIR__ . '/../../../vendor/phpmailer/src/Exception.php';
        require_once __DIR__ . '/../../../vendor/phpmailer/src/PHPMailer.php';
        require_once __DIR__ . '/../../../vendor/phpmailer/src/SMTP.php';
        require_once __DIR__ . '/../../services/email_service.php';

        $pengumuman = $this->model->getById($post['pengumuman_id']);

        if (!$pengumuman) {
            return [
                'success' => false,
                'message' => 'Pengumuman tidak ditemukan'
            ];
        }

        $targetData = [
            'target_type' => $pengumuman['target_type'],
            'target_jurusan_id' => $pengumuman['target_jurusan_id'],
            'target_prodi_id' => $pengumuman['target_prodi_id'],
            'target_kelas' => $pengumuman['target_kelas']
        ];

        $recipients = $this->model->getEmailMahasiswaByTarget($targetData);

        if (empty($recipients)) {
            return [
                'success' => false,
                'message' => 'Tidak ada email mahasiswa yang ditemukan untuk target ini'
            ];
        }

        $validRecipients = array_filter($recipients, function ($r) {
            return !empty($r['email']) && filter_var($r['email'], FILTER_VALIDATE_EMAIL);
        });

        if (empty($validRecipients)) {
            return [
                'success' => false,
                'message' => 'Tidak ada email mahasiswa yang valid'
            ];
        }

        $emailService = new EmailService();

        $successCount = 0;
        $failCount = 0;
        $errors = [];

        foreach ($validRecipients as $recipient) {
            try {
                if ($emailService->sendPengumuman($recipient, $pengumuman)) {
                    $successCount++;
                } else {
                    $failCount++;
                    $errors[] = $recipient['email'];
                }

                usleep(100000);

            } catch (Exception $e) {
                $failCount++;
                $errors[] = $recipient['email'];
                error_log("Email Error: " . $e->getMessage());
            }
        }

        if ($successCount > 0) {
            $message = "✅ Email berhasil dikirim ke {$successCount} mahasiswa";
            if ($failCount > 0) {
                $message .= " ({$failCount} gagal)";
            }

            return [
                'success' => true,
                'message' => $message,
                'detail' => [
                    'total' => count($validRecipients),
                    'success' => $successCount,
                    'failed' => $failCount
                ]
            ];
        } else {
            return [
                'success' => false,
                'message' => "❌ Gagal mengirim email ke semua penerima",
                'detail' => [
                    'total' => count($validRecipients),
                    'success' => 0,
                    'failed' => $failCount,
                    'errors' => $errors
                ]
            ];
        }
    }
}
?>