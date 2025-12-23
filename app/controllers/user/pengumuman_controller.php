<?php

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../models/pengumuman_model.php';

class PengumumanControllerUser
{
    private $model;

    public function __construct()
    {
        global $config;
        $this->model = new PengumumanModel($config);
    }

    public function index()
    {
        // Ambil parameter filter
        // FIX: Kita ambil NAMA kategori dari URL untuk keperluan selected di dropdown
        $namaKategoriDipilih = isset($_GET['kategori']) ? $_GET['kategori'] : null;
        $bulanDipilih = isset($_GET['bulan']) ? $_GET['bulan'] : null;
        $tahunDipilih = isset($_GET['tahun']) ? $_GET['tahun'] : null;

        // Ambil data untuk dropdown filter
        $kategoriList = $this->model->getKategori();
        
        // LOGIKA BARU: Cari ID Kategori berdasarkan Nama yang dipilih
        $kategoriIdDipilih = null;
        if (!empty($namaKategoriDipilih)) {
            foreach ($kategoriList as $kat) {
                // Case insensitive comparison biar aman
                if (strtolower($kat['nama_kategori']) === strtolower($namaKategoriDipilih)) {
                    $kategoriIdDipilih = $kat['kategori_id'];
                    break;
                }
            }
        }

        // List Bulan (Manual)
        $bulanList = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulanList[] = [
                'bulan' => $i,
                'nama_bulan' => date('F', mktime(0, 0, 0, $i, 1))
            ];
        }

        $tahunList = $this->model->getAvailableYears();

        // Kirim ID Kategori ke Model, bukan Nama
        $pengumuman = $this->model->filterPengumuman(
            $kategoriIdDipilih, 
            $bulanDipilih, 
            $tahunDipilih, 
            1000000, 
            0 
        );

        return [
            "pengumuman" => $pengumuman,
            "kategori" => $kategoriList,
            "bulanList" => $bulanList,
            "tahunList" => $tahunList,
            "kategoriDipilih" => $namaKategoriDipilih, // Kembalikan nama untuk view (dropdown)
            "bulanDipilih" => $bulanDipilih,
            "tahunDipilih" => $tahunDipilih
        ];
    }

    // TAMBAHKAN FUNGSI INI (Sering menyebabkan error karena tidak ada)
    public function detail($id)
    {
        // Gunakan fungsi getById yang sudah ada di Model
        $data = $this->model->getById($id);
        
        // Bungkus dalam struktur array yang sesuai dengan get_detail.php
        return [
            'pengumuman' => $data
        ];
    }
}
?>