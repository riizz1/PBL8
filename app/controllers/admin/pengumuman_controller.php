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
    public function kirimEmail($post)
    {
        if (!isset($post['pengumuman_id'])) {
            return [
                'success' => false,
                'message' => 'ID pengumuman tidak valid'
            ];
        }

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

        // Kirim email
        $successCount = 0;
        $failCount = 0;

        foreach ($recipients as $recipient) {
            $to = $recipient['email'];
            $subject = "[Pengumuman] " . $pengumuman['judul'];

            // HTML Email Template
            $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%); 
                          color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .footer { text-align: center; margin-top: 20px; color: #777; font-size: 12px; }
                h1 { margin: 0; font-size: 24px; }
                .kategori { display: inline-block; background: #fff; color: #2193b0; 
                           padding: 5px 15px; border-radius: 20px; margin-top: 10px; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>📢 Pengumuman Baru</h1>
                    <span class='kategori'>" . htmlspecialchars($pengumuman['nama_kategori']) . "</span>
                </div>
                <div class='content'>
                    <p>Halo <strong>" . htmlspecialchars($recipient['nama_lengkap']) . "</strong>,</p>
                    <h2>" . htmlspecialchars($pengumuman['judul']) . "</h2>
                    <p>" . nl2br(htmlspecialchars($pengumuman['isi'])) . "</p>
                    <hr>
                    <p><small>Dikirim pada: " . date('d F Y, H:i', strtotime($pengumuman['created_at'])) . " WIB</small></p>
                </div>
                <div class='footer'>
                    <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
                    <p>&copy; " . date('Y') . " Sistem Informasi Kampus</p>
                </div>
            </div>
        </body>
        </html>
        ";

            // Headers untuk HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: noreply@kampus.ac.id" . "\r\n";

            // Kirim email
            if (mail($to, $subject, $message, $headers)) {
                $successCount++;
            } else {
                $failCount++;
            }
        }

        return [
            'success' => true,
            'message' => "Email berhasil dikirim ke {$successCount} mahasiswa" .
                ($failCount > 0 ? " ({$failCount} gagal)" : ""),
            'detail' => [
                'total' => count($recipients),
                'success' => $successCount,
                'failed' => $failCount
            ]
        ];
    }
}
?>