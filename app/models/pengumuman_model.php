<?php
// PBL8/app/models/pengumuman_model.php

class PengumumanModel
{
    private $db;

    public function __construct($config)
    {
        $this->db = $config;
    }

    /* =========================
       ===== ADMIN SECTION =====
       ========================= */

    public function getAll()
    {
        $query = "SELECT 
                    p.pengumuman_id,
                    p.judul,
                    p.isi,
                    p.kategori_id,
                    p.target_type,
                    p.target_jurusan_id,
                    p.target_prodi_id,
                    p.target_kelas,
                    p.created_at,
                    k.nama_kategori,
                    j.nama_jurusan,
                    pr.nama_prodi,
                    CASE 
                        WHEN p.target_type = 'all' THEN 'Semua Mahasiswa'
                        WHEN p.target_type = 'jurusan' AND p.target_prodi_id IS NULL AND p.target_kelas IS NULL THEN CONCAT('Jurusan ', j.nama_jurusan)
                        WHEN p.target_type = 'jurusan' AND p.target_prodi_id IS NOT NULL AND p.target_kelas IS NULL THEN CONCAT(j.nama_jurusan, ' - ', pr.nama_prodi)
                        WHEN p.target_type = 'jurusan' AND p.target_kelas IS NOT NULL THEN CONCAT(j.nama_jurusan, ' - ', pr.nama_prodi, ' - ', p.target_kelas)
                        WHEN p.target_type = 'prodi' AND p.target_kelas IS NULL THEN CONCAT('Prodi ', pr.nama_prodi)
                        WHEN p.target_type = 'prodi' AND p.target_kelas IS NOT NULL THEN CONCAT(pr.nama_prodi, ' - ', p.target_kelas)
                        WHEN p.target_type = 'kelas' THEN CONCAT(pr.nama_prodi, ' - Kelas ', p.target_kelas)
                        ELSE 'Semua Mahasiswa'
                    END as target_display
                  FROM pengumuman p
                  LEFT JOIN kategori k ON p.kategori_id = k.kategori_id
                  LEFT JOIN jurusan j ON p.target_jurusan_id = j.jurusan_id
                  LEFT JOIN prodi pr ON p.target_prodi_id = pr.prodi_id
                  ORDER BY p.created_at DESC";

        $result = $this->db->query($query);
        if (!$result)
            return [];

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getById($id)
    {
        $query = "SELECT 
                    p.*,
                    k.nama_kategori,
                    j.nama_jurusan,
                    pr.nama_prodi
                  FROM pengumuman p
                  LEFT JOIN kategori k ON p.kategori_id = k.kategori_id
                  LEFT JOIN jurusan j ON p.target_jurusan_id = j.jurusan_id
                  LEFT JOIN prodi pr ON p.target_prodi_id = pr.prodi_id
                  WHERE p.pengumuman_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getKategori()
    {
        $query = "SELECT kategori_id, nama_kategori FROM kategori ORDER BY nama_kategori ASC";
        $result = $this->db->query($query);
        if (!$result)
            return [];

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getAllJurusan()
    {
        $query = "SELECT jurusan_id, nama_jurusan FROM jurusan ORDER BY nama_jurusan ASC";
        $result = $this->db->query($query);
        if (!$result)
            return [];

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getAllProdi()
    {
        $query = "SELECT prodi_id, nama_prodi, jurusan_id FROM prodi ORDER BY nama_prodi ASC";
        $result = $this->db->query($query);
        if (!$result)
            return [];

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getKelasByProdi($prodi_id)
    {
        $query = "SELECT DISTINCT kelas 
                  FROM mahasiswa 
                  WHERE prodi_id = ? AND kelas IS NOT NULL AND kelas != ''
                  ORDER BY kelas ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $prodi_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function create($judul, $kategori_id, $isi, $targetData)
    {
        $query = "INSERT INTO pengumuman 
                  (judul, kategori_id, isi, target_type, target_jurusan_id, target_prodi_id, target_kelas) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);

        // FIX: Perbaikan tipe data binding
        // target_kelas di database adalah varchar, gunakan 's'. 
        // Jika null, bind_param tetap aman.
        $stmt->bind_param(
            'sisssss', // s(string), i(int), s(string), s(string), s(int/null), s(int/null), s(string/null)
            $judul,
            $kategori_id,
            $isi,
            $targetData['target_type'],
            $targetData['target_jurusan_id'],
            $targetData['target_prodi_id'],
            $targetData['target_kelas']
        );

        return $stmt->execute();
    }

    public function update($id, $judul, $kategori_id, $isi, $targetData)
    {
        $query = "UPDATE pengumuman 
                  SET judul = ?, kategori_id = ?, isi = ?, target_type = ?, 
                      target_jurusan_id = ?, target_prodi_id = ?, target_kelas = ?
                  WHERE pengumuman_id = ?";

        $stmt = $this->db->prepare($query);

        // FIX: Perbaikan tipe data binding
        $stmt->bind_param(
            'sisssssi',
            $judul,
            $kategori_id,
            $isi,
            $targetData['target_type'],
            $targetData['target_jurusan_id'],
            $targetData['target_prodi_id'],
            $targetData['target_kelas'],
            $id
        );

        return $stmt->execute();
    }

    public function delete($id)
    {
        $query = "DELETE FROM pengumuman WHERE pengumuman_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    /* =========================
       ===== USER SECTION ======
       ========================= */

    public function countPengumuman($kategori = null, $bulan = null, $tahun = null)
    {
        $sql = "SELECT COUNT(*) as total FROM pengumuman WHERE 1=1";
        $params = [];
        $types = "";

        if ($kategori) {
            $sql .= " AND kategori_id = ?";
            $params[] = $kategori;
            $types .= "i";
        }

        if ($bulan) {
            $sql .= " AND MONTH(created_at) = ?";
            $params[] = $bulan;
            $types .= "i";
        }

        if ($tahun) {
            $sql .= " AND YEAR(created_at) = ?";
            $params[] = $tahun;
            $types .= "i";
        }

        $stmt = $this->db->prepare($sql);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc()['total'] ?? 0;
    }

    public function filterPengumuman($kategori, $bulan, $tahun, $limit, $offset)
    {
        $sql = "SELECT 
                    p.pengumuman_id,
                    p.judul,
                    p.isi,
                    p.created_at,
                    k.nama_kategori
                FROM pengumuman p
                LEFT JOIN kategori k ON p.kategori_id = k.kategori_id
                WHERE 1=1";

        $params = [];
        $types = "";

        if ($kategori) {
            $sql .= " AND p.kategori_id = ?";
            $params[] = $kategori;
            $types .= "i";
        }

        if ($bulan) {
            $sql .= " AND MONTH(p.created_at) = ?";
            $params[] = $bulan;
            $types .= "i";
        }

        if ($tahun) {
            $sql .= " AND YEAR(p.created_at) = ?";
            $params[] = $tahun;
            $types .= "i";
        }

        $sql .= " ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= "ii";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getAvailableYears()
    {
        $query = "SELECT DISTINCT YEAR(created_at) AS tahun FROM pengumuman ORDER BY tahun DESC";
        $result = $this->db->query($query);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    // Method untuk mendapatkan email mahasiswa berdasarkan target pengumuman
    public function getEmailMahasiswaByTarget($targetData)
    {
        $query = "SELECT DISTINCT m.email, m.nama_lengkap, m.nim 
              FROM mahasiswa m
              WHERE 1=1";

        $params = [];
        $types = "";

        if ($targetData['target_type'] === 'all') {
            // Ambil semua email mahasiswa
            $query .= " AND m.email IS NOT NULL AND m.email != ''";
        } elseif ($targetData['target_type'] === 'jurusan') {
            if (!empty($targetData['target_kelas'])) {
                // Jurusan + Prodi + Kelas spesifik
                $query .= " AND m.jurusan_id = ? AND m.prodi_id = ? AND m.kelas = ?";
                $params = [$targetData['target_jurusan_id'], $targetData['target_prodi_id'], $targetData['target_kelas']];
                $types = "iis";
            } elseif (!empty($targetData['target_prodi_id'])) {
                // Jurusan + Prodi tertentu
                $query .= " AND m.jurusan_id = ? AND m.prodi_id = ?";
                $params = [$targetData['target_jurusan_id'], $targetData['target_prodi_id']];
                $types = "ii";
            } else {
                // Jurusan tertentu saja
                $query .= " AND m.jurusan_id = ?";
                $params = [$targetData['target_jurusan_id']];
                $types = "i";
            }
        } elseif ($targetData['target_type'] === 'prodi') {
            // Logika khusus jika target type hanya prodi (misal select prodi tapi jurusan null)
            if (!empty($targetData['target_kelas'])) {
                $query .= " AND m.prodi_id = ? AND m.kelas = ?";
                $params = [$targetData['target_prodi_id'], $targetData['target_kelas']];
                $types = "is";
            } else {
                $query .= " AND m.prodi_id = ?";
                $params = [$targetData['target_prodi_id']];
                $types = "i";
            }
        } elseif ($targetData['target_type'] === 'kelas') {
            // Logika khusus jika target type hanya kelas
            $query .= " AND m.prodi_id = ? AND m.kelas = ?";
            // Note: Biasanya kelas butuh prodi context, jadi pakai prodi_id
            $params = [$targetData['target_prodi_id'], $targetData['target_kelas']];
            $types = "is";
        }

        $stmt = $this->db->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();

        $result = $stmt->get_result();
        $emails = [];
        while ($row = $result->fetch_assoc()) {
            $emails[] = $row;
        }
        return $emails;
    }
}