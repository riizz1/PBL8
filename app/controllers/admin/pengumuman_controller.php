<?php

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

    /**
     * LIST DATA
     */
    public function index()
    {
        return [
            "pengumuman" => $this->model->getAll(),
            "kategori" => $this->model->getKategori(),
            "jurusan" => $this->model->getAllJurusan(),
            "prodi" => $this->model->getAllProdi()
        ];
    }

    /**
     * GET BY ID
     */
    public function getById($id)
    {
        return $this->model->getById($id);
    }

    /**
     * GET KELAS BY PRODI
     */
    public function getKelasByProdi($prodi_id)
    {
        return $this->model->getKelasByProdi($prodi_id);
    }

    /**
     * TAMBAH DATA
     */
    public function tambah($post)
    {
        if (!isset($post['judul'], $post['kategori_id'], $post['isi'], $post['target_type'])) {
            return [
                'success' => false,
                'message' => 'Data tidak lengkap'
            ];
        }

        // FIX: Normalisasi data target
        // Ubah string kosong menjadi NULL agar sesuai tipe database
        $targetJurusan = (!empty($post['target_jurusan_id'])) ? $post['target_jurusan_id'] : null;
        $targetProdi = (!empty($post['target_prodi_id'])) ? $post['target_prodi_id'] : null;
        $targetKelas = (!empty($post['target_kelas'])) ? $post['target_kelas'] : null;

        // Prepare target data
        $targetData = [
            'target_type' => $post['target_type'],
            'target_jurusan_id' => $targetJurusan,
            'target_prodi_id' => $targetProdi,
            'target_kelas' => $targetKelas
        ];

        $result = $this->model->create(
            $post['judul'],
            $post['kategori_id'],
            $post['isi'],
            $targetData
        );

        if ($result) {
            return [
                'success' => true,
                'message' => 'Pengumuman berhasil ditambahkan'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menambahkan pengumuman. Periksa kembali inputan Anda.'
            ];
        }
    }

    /**
     * EDIT DATA
     */
    public function edit($post)
    {
        if (!isset($post['pengumuman_id'], $post['judul'], $post['kategori_id'], $post['isi'], $post['target_type'])) {
            return [
                'success' => false,
                'message' => 'Data tidak lengkap'
            ];
        }

        // FIX: Normalisasi data target (Sama seperti tambah)
        $targetJurusan = (!empty($post['target_jurusan_id'])) ? $post['target_jurusan_id'] : null;
        $targetProdi = (!empty($post['target_prodi_id'])) ? $post['target_prodi_id'] : null;
        $targetKelas = (!empty($post['target_kelas'])) ? $post['target_kelas'] : null;

        // Prepare target data
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

    /**
     * HAPUS DATA
     */
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

    /**
     * KIRIM EMAIL
     */
    /**
     * KIRIM EMAIL - UPDATED dengan PHPMailer
     */
    public function kirimEmail($post)
    {
        if (!isset($post['pengumuman_id'])) {
            return [
                'success' => false,
                'message' => 'ID pengumuman tidak valid'
            ];
        }

        // Load PHPMailer & Email Service
        require_once __DIR__ . '/../../../vendor/phpmailer/src/Exception.php';
        require_once __DIR__ . '/../../../vendor/phpmailer/src/PHPMailer.php';
        require_once __DIR__ . '/../../../vendor/phpmailer/src/SMTP.php';
        require_once __DIR__ . '/../../services/email_service.php';

        // Get detail pengumuman
        $pengumuman = $this->model->getById($post['pengumuman_id']);

        if (!$pengumuman) {
            return [
                'success' => false,
                'message' => 'Pengumuman tidak ditemukan'
            ];
        }

        // Prepare target data
        $targetData = [
            'target_type' => $pengumuman['target_type'],
            'target_jurusan_id' => $pengumuman['target_jurusan_id'],
            'target_prodi_id' => $pengumuman['target_prodi_id'],
            'target_kelas' => $pengumuman['target_kelas']
        ];

        // Get email mahasiswa
        $recipients = $this->model->getEmailMahasiswaByTarget($targetData);

        if (empty($recipients)) {
            return [
                'success' => false,
                'message' => 'Tidak ada email mahasiswa yang ditemukan untuk target ini'
            ];
        }

        // Filter recipients yang punya email valid
        $validRecipients = array_filter($recipients, function ($r) {
            return !empty($r['email']) && filter_var($r['email'], FILTER_VALIDATE_EMAIL);
        });

        if (empty($validRecipients)) {
            return [
                'success' => false,
                'message' => 'Tidak ada email mahasiswa yang valid'
            ];
        }

        // Initialize Email Service
        $emailService = new EmailService();

        // Kirim email
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

                // Delay kecil untuk menghindari spam detection
                usleep(100000); // 0.1 detik

            } catch (Exception $e) {
                $failCount++;
                $errors[] = $recipient['email'];
                error_log("Email Error: " . $e->getMessage());
            }
        }

        // Response
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