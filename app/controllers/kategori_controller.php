<?php
include_once __DIR__ . "/../../config/config.php";
include_once __DIR__ . "/../../models/kategori_model.php";

class KategoriController {

    public function index()
    {
        global $config;

        $kategoriModel = new KategoriModel($config);
        $dataKategori = $kategoriModel->getAllKategori();

        // lempar ke view
        include __DIR__ . "/../../views/admin/kategori.php";
    }
}

?>
