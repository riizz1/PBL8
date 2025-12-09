    <?php

    class KategoriModel
    {
        private $db;

        public function __construct($config)
        {
            $this->db = $config;
        }

        // ========== CREATE ==========
        public function tambahKategori($nama, $deskripsi)
        {
            $stmt = $this->db->prepare("INSERT INTO kategori (nama_kategori, deskripsi) VALUES (?, ?)");
            $stmt->bind_param("ss", $nama, $deskripsi);
            return $stmt->execute();
        }

        // ========== READ ALL ==========
        public function getAll()
        {
            $result = $this->db->query("SELECT * FROM kategori ORDER BY kategori_id DESC");
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        // ========== READ BY ID ==========
        public function getById($id)
        {
            $stmt = $this->db->prepare("SELECT * FROM kategori WHERE kategori_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }

        // ========== UPDATE ==========
        public function updateKategori($id, $nama, $deskripsi)
        {
            $stmt = $this->db->prepare("UPDATE kategori SET nama_kategori = ?, deskripsi = ? WHERE kategori_id = ?");
            $stmt->bind_param("ssi", $nama, $deskripsi, $id);
            return $stmt->execute();
        }

        // ========== CEK DUPLIKASI NAMA KATEGORI ==========
        public function cekDuplikasiNama($nama, $excludeId = null)
        {
            if ($excludeId) {
                // Untuk edit, exclude ID yang sedang diedit
                $stmt = $this->db->prepare("SELECT COUNT(*) as jumlah FROM kategori WHERE nama_kategori = ? AND kategori_id != ?");
                $stmt->bind_param("si", $nama, $excludeId);
            } else {
                // Untuk tambah baru
                $stmt = $this->db->prepare("SELECT COUNT(*) as jumlah FROM kategori WHERE nama_kategori = ?");
                $stmt->bind_param("s", $nama);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['jumlah'] > 0;
        }

        // ========== CEK KATEGORI DIGUNAKAN ==========
        public function cekKategoriDigunakan($id)
        {
            // Cek apakah kategori ini masih digunakan di tabel pengumuman
            $stmt = $this->db->prepare("SELECT COUNT(*) as jumlah FROM pengumuman WHERE kategori_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['jumlah'] > 0;
        }

        // ========== DELETE ==========
        public function hapusKategori($id)
        {
            // Cek dulu apakah kategori masih digunakan
            if ($this->cekKategoriDigunakan($id)) {
                return false; // Kategori masih digunakan, tidak bisa dihapus
            }
            
            $stmt = $this->db->prepare("DELETE FROM kategori WHERE kategori_id = ?");
            $stmt->bind_param("i", $id);
            $result = $stmt->execute();
            
            if (!$result) {
                error_log("Error hapusKategori: " . $this->db->error);
            }
            
            return $result;
        }
    }
    ?>