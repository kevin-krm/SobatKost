<?php
/**
 * Jembatan database komplain. Fungsi update status yang men-trigger Observer ada di sini.
 */
require_once __DIR__ . '/PDOUtil.php';
require_once __DIR__ . '/../model/Komplain.php';

class KomplainDao {

    /**
     * Mengambil data daftar komplain secara bertahap untuk fungsionalitas pagination.
     */
    public function getKomplainPage($limit, $offset) {
        $link = PDOUtil::createConnection();
        
        $query = "SELECT k.*, p.nama_lengkap, ks.id_kamar 
                  FROM komplain k 
                  LEFT JOIN pengguna p ON k.id_pengguna = p.id_pengguna 
                  LEFT JOIN kontrak_sewa ks ON k.id_pengguna = ks.id_pengguna
                  ORDER BY k.tanggal_lapor DESC 
                  LIMIT :limit OFFSET :offset";

        $stmt = $link->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $komplain = new Komplain(
                $row['id_komplain'], $row['id_pengguna'], $row['judul_masalah'],
                $row['deskripsi'], $row['status_komplain'], $row['tanggal_lapor']
            );

            $komplain->setNamaPengguna($row['nama_lengkap']);
            $komplain->setIdKamar($row['id_kamar']);

            $result[] = $komplain;
        }
        return $result;
    }

    /**
     * Menghitung total seluruh tiket komplain yang ada di dalam sistem.
     */
    public function countKomplain() {
        $link = PDOUtil::createConnection();
        return $link->query("SELECT COUNT(*) FROM komplain")->fetchColumn();
    }

    /**
     * Mengambil rincian detail spesifik dari satu tiket komplain.
     */
    public function getKomplainById($id) {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM komplain WHERE id_komplain = :id";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        return new Komplain(
            $row['id_komplain'], $row['id_pengguna'], $row['judul_masalah'],
            $row['deskripsi'], $row['status_komplain'], $row['tanggal_lapor']
        );
    }

    /**
     * Mengambil riwayat tiket komplain khusus untuk satu penyewa menggunakan pagination.
     */
    public function getKomplainByUserIdPage($id_pengguna, $limit, $offset) {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM komplain WHERE id_pengguna = :id_pengguna ORDER BY tanggal_lapor DESC LIMIT :limit OFFSET :offset";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':id_pengguna', $id_pengguna);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Komplain(
                $row['id_komplain'], $row['id_pengguna'], $row['judul_masalah'],
                $row['deskripsi'], $row['status_komplain'], $row['tanggal_lapor']
            );
        }
        return $result;
    }

    /**
     * Menghitung jumlah keseluruhan tiket komplain yang pernah dilaporkan oleh seorang penyewa.
     */
    public function countKomplainByUserId($id_pengguna) {
        $link = PDOUtil::createConnection();
        $query = "SELECT COUNT(*) FROM komplain WHERE id_pengguna = :id_pengguna";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':id_pengguna', $id_pengguna);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    /**
     * Menyimpan entri tiket komplain baru ke database.
     */
    public function insertKomplain(Komplain $komplain) {
        $link = PDOUtil::createConnection();
        $query = "CALL sp_insert_komplain(:user, :judul, :desk)";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':user', $komplain->getIdPengguna());
        $stmt->bindValue(':judul', $komplain->getJudulMasalah());
        $stmt->bindValue(':desk', $komplain->getDeskripsi());
        $stmt->execute();
    }

    // Update Judul & Deskripsi
    /**
     * Memperbarui detail laporan keluhan fasilitas di dalam database.
     */
    public function updateKomplainPenuh(Komplain $k) {
        $link = PDOUtil::createConnection();
        $query = "UPDATE komplain SET judul_masalah = :judul, deskripsi = :deskripsi, 
                  status_komplain = :status WHERE id_komplain = :id";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':judul', $k->getJudulMasalah());
        $stmt->bindValue(':deskripsi', $k->getDeskripsi());
        $stmt->bindValue(':status', $k->getStatusKomplain());
        $stmt->bindValue(':id', $k->getIdKomplain());
        $stmt->execute();
    }

    /**
     * Menyimpan perubahan status penanganan komplain.
     * Relasi: Berperan sebagai Subject dalam pola Observer untuk mengirimkan notifikasi pembaruan.
     */
    public function updateStatus(Komplain $komplain) {
        $link = PDOUtil::createConnection();
        $query = "UPDATE komplain SET status_komplain = :status WHERE id_komplain = :id";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':status', $komplain->getStatusKomplain());
        $stmt->bindValue(':id', $komplain->getIdKomplain());
        $stmt->execute();
    }

    /**
     * Menghapus data tiket komplain dari tabel database.
     */
    public function deleteKomplain($id) {
        $link = PDOUtil::createConnection();
        $query = "DELETE FROM komplain WHERE id_komplain = :id";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    /**
     * Merekapitulasi jumlah komplain berdasarkan masing-masing statusnya (seperti Selesai atau Diproses) untuk laporan.
     */
    public function countKomplainByStatus() {
        $link = PDOUtil::createConnection();
        $query = "SELECT status_komplain, COUNT(*) as jumlah 
                  FROM komplain 
                  GROUP BY status_komplain";
        $stmt = $link->prepare($query);
        $stmt->execute();

        $result = [
            'Menunggu' => 0,
            'Diproses' => 0,
            'Selesai' => 0
        ];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $status = $row['status_komplain'];
            // Normalize status to proper keys (case-insensitive)
            $normalized = ucfirst(strtolower($status));
            if (isset($result[$normalized])) {
                $result[$normalized] = (int)$row['jumlah'];
            } else {
                // If unexpected status, add it dynamically
                $result[$normalized] = (int)$row['jumlah'];
            }
        }
        return $result;
    }
}