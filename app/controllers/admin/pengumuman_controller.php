<?php

require_once __DIR__ . '/../../../config/config.php'; // Path ke config.php
require_once __DIR__ . '/../../models/pengumuman_model.php';

class PengumumanControllerAdmin
{
    private $model;

    public function __construct()
    {
        global $config; // Gunakan variabel global $config dari config.php
        
        // Inisialisasi model dengan config database
        $this->model = new PengumumanModel($config);
    }

    /* ===========================
       LIST DATA
    ============================ */
    public function index()
    {
        return [
            "pengumuman" => $this->model->getAll(),
            "kategori" => $this->model->getKategori()
        ];
    }

    /* ===========================
       GET BY ID
    ============================ */
    public function getById($id)
    {
        return $this->model->getById($id);
    }

    /* ===========================
       TAMBAH DATA
    ============================ */
    public function tambah($post)
    {
        if (!isset($post['judul'], $post['kategori_id'], $post['isi'])) {
            return [
                'success' => false,
                'message' => 'Data tidak lengkap'
            ];
        }

        $result = $this->model->create(
            $post['judul'],
            $post['kategori_id'],
            $post['isi']
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

    /* ===========================
       EDIT DATA
    ============================ */
    public function edit($post)
    {
        if (
            !isset($post['pengumuman_id'], $post['judul'], $post['kategori_id'], $post['isi'])
        ) {
            return [
                'success' => false,
                'message' => 'Data tidak lengkap'
            ];
        }

        $result = $this->model->update(
            $post['pengumuman_id'],
            $post['judul'],
            $post['kategori_id'],
            $post['isi']
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

    /* ===========================
       HAPUS DATA
    ============================ */
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