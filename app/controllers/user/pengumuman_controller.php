<?php

require_once __DIR__ . '/../../models/pengumuman_model.php';

class PengumumanControllerUser
{
    private $model;

    public function __construct()
    {
        $this->model = new PengumumanModel();
    }

    public function index()
    {
        // Ambil filter dari URL
        $kategori = isset($_GET['kategori']) ? $_GET['kategori'] : null;
        $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : null;
        $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : null;
        
        // Pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20; // 20 data per halaman
        $offset = ($page - 1) * $limit;

        // Hitung total data
        $totalData = $this->model->countPengumuman($kategori, $bulan, $tahun);
        $totalPages = ceil($totalData / $limit);

        // Jika ada filter, pakai filter; jika tidak, tampilkan semua
        if ($kategori || $bulan || $tahun) {
            $pengumuman = $this->model->filterPengumuman($kategori, $bulan, $tahun, $limit, $offset);
        } else {
            $pengumuman = $this->model->filterPengumuman(null, null, null, $limit, $offset);
        }

        // Ambil data pendukung untuk tampilan
        $kategoriList = $this->model->getKategori();
        $tahunList    = $this->model->getAvailableYears();

        // Fixed list bulan (Januari - Desember)
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

        // Controller harus return array (supaya view aman dipanggil)
        return [
            'pengumuman'        => $pengumuman,
            'kategori'          => $kategoriList,
            'bulanList'         => $bulanList,
            'tahunList'         => $tahunList,
            'kategoriDipilih'   => $kategori,
            'bulanDipilih'      => $bulan,
            'tahunDipilih'      => $tahun,
            'currentPage'       => $page,
            'totalPages'        => $totalPages,
            'totalData'         => $totalData,
            'startData'         => $offset + 1,
            'endData'           => min($offset + $limit, $totalData)
        ];
    }

    public function detail($id)
    {
        // Ambil detail pengumuman berdasarkan ID
        $pengumuman = $this->model->getById($id);

        // Jika tidak ditemukan
        if (!$pengumuman) {
            return null;
        }

        return [
            'pengumuman' => $pengumuman
        ];
    }
}