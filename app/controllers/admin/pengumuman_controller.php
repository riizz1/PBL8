<?php

require_once __DIR__ . '/../../models/pengumuman_model.php';

class PengumumanControllerAdmin
{
    private $model;

    public function __construct()
    {
        $this->model = new PengumumanModel();
    }

    public function index()
    {
        return [
            "pengumuman" => $this->model->getAll(),
            "kategori"   => $this->model->getKategori()
        ];
    }

    public function tambah($post)
    {
        return $this->model->create($post['judul'], $post['kategori_id'], $post['isi']);
    }

    public function edit($post)
    {
        return $this->model->update(
            $post['pengumuman_id'],
            $post['judul'],
            $post['kategori_id'],
            $post['isi']
        );
    }

    public function hapus($id)
    {
        return $this->model->delete($id);
    }
}
