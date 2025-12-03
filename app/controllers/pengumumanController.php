<?php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/crud_pengumuman.php';

class PengumumanController {

    private $model;

    public function __construct() {
        global $conn;
        $this->model = new PengumumanModel($conn);
    }

    public function index() {

        // HANDLE FORM
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($_POST['tambah'])) {
                $this->model->add($_POST['judul'], $_POST['kategori_id'], $_POST['isi']);
                header("Location: index.php");
                exit;
            }

            if (isset($_POST['edit'])) {
                $this->model->update($_POST['pengumuman_id'], $_POST['judul'], $_POST['kategori_id'], $_POST['isi']);
                header("Location: index.php");
                exit;
            }
        }

        if (isset($_GET['hapus'])) {
            $this->model->delete($_GET['hapus']);
            header("Location: index.php");
            exit;
        }

        // AMBIL DATA
        $pengumuman = $this->model->getAll();
        $kategori   = $this->model->getKategori();

        // Kirim ke view
        return [
            'pengumuman' => $pengumuman,
            'kategori' => $kategori
        ];
    }
}
