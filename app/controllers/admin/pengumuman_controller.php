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

        // Prepare target data
        $targetData = [
            'target_type' => $post['target_type'],
            'target_jurusan_id' => $post['target_jurusan_id'] ?? null,
            'target_prodi_id' => $post['target_prodi_id'] ?? null,
            'target_kelas' => $post['target_kelas'] ?? null
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
                'message' => 'Gagal menambahkan pengumuman'
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

        // Prepare target data
        $targetData = [
            'target_type' => $post['target_type'],
            'target_jurusan_id' => $post['target_jurusan_id'] ?? null,
            'target_prodi_id' => $post['target_prodi_id'] ?? null,
            'target_kelas' => $post['target_kelas'] ?? null
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
}
?>