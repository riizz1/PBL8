<?php

require_once __DIR__ . '/../../models/pengumuman_model.php';

class PengumumanControllerAdmin
{
    private $model;

    public function __construct()
    {
        $this->model = new PengumumanModel();
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
       TAMBAH DATA
    ============================ */
    public function tambah($post)
    {
        if (!isset($post['judul'], $post['kategori_id'], $post['isi'])) {
            return false;
        }

        return $this->model->create(
            $post['judul'],
            $post['kategori_id'],
            $post['isi']
        );
    }

    /* ===========================
       EDIT DATA
    ============================ */
    public function edit($post)
    {
        if (
            !isset($post['pengumuman_id'], $post['judul'], $post['kategori_id'], $post['isi'])
        ) {
            return false;
        }

        return $this->model->update(
            $post['pengumuman_id'],
            $post['judul'],
            $post['kategori_id'],
            $post['isi']
        );
    }

    /* ===========================
       HAPUS DATA
    ============================ */
    public function hapus($id)
    {
        if (!isset($id) || empty($id)) {
            return false;
        }

        return $this->model->delete($id);
    }
}
