<?php

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../models/pengumuman_model.php';

class PengumumanControllerUser
{
    private $model;

    public function __construct()
    {
        global $config;
        $this->model = new PengumumanModel($config); // ✅ FIX ERROR
    }

    public function index()
    {
        // Ambil filter dari URL
        $kategori = isset($_GET['kategori']) ? $_GET['kategori'] : null;
        $bulan    = isset($_GET['bulan']) ? $_GET['bulan'] : null;
        $tahun    = isset($_GET['tahun']) ? $_GET['tahun'] : null;

        // Pagination
        $page   = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit  = 20;
        $offset = ($page - 1) * $limit;

        // Total data
        $totalData  = $this->model->countPengumuman($kategori, $bulan, $tahun);
        $totalPages = ceil($totalData / $limit);

        // Ambil data pengumuman
        $pengumuman = $this->model->filterPengumuman(
            $kategori,
            $bulan,
            $tahun,
            $limit,
            $offset
        );

        // Data pendukung
        $kategoriList = $this->model->getKategori();
        $tahunList    = $this->model->getAvailableYears();

        $bulanList = [
            ['bulan' => 1, 'nama_bulan' => 'Januari'],
            ['bulan' => 2, 'nama_bulan' => 'Februari'],
            ['bulan' => 3, 'nama_bulan' => 'Maret'],
            ['bulan' => 4, 'nama_bulan' => 'April'],
            ['bulan' => 5, 'nama_bulan' => 'Mei'],
            ['bulan' => 6, 'nama_bulan' => 'Juni'],
            ['bulan' => 7, 'nama_bulan' => 'Juli'],
            ['bulan' => 8, 'nama_bulan' => 'Agustus'],
            ['bulan' => 9, 'nama_bulan' => 'September'],
            ['bulan' => 10, 'nama_bulan' => 'Oktober'],
            ['bulan' => 11, 'nama_bulan' => 'November'],
            ['bulan' => 12, 'nama_bulan' => 'Desember']
        ];

        return [
            'pengumuman'      => $pengumuman,
            'kategori'        => $kategoriList,
            'bulanList'       => $bulanList,
            'tahunList'       => $tahunList,
            'kategoriDipilih' => $kategori,
            'bulanDipilih'    => $bulan,
            'tahunDipilih'    => $tahun,
            'currentPage'     => $page,
            'totalPages'      => $totalPages,
            'totalData'       => $totalData,
            'startData'       => $offset + 1,
            'endData'         => min($offset + $limit, $totalData)
        ];
    }

    public function detail($id)
    {
        $pengumuman = $this->model->getById($id);

        if (!$pengumuman) {
            return null;
        }

        return [
            'pengumuman' => $pengumuman
        ];
    }
}
