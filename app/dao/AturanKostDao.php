<?php
/**
 * Menjembatani aplikasi dengan database tabel `aturan_kost`. Eksekusi query ada di sini.
 */
require_once __DIR__ . '/PDOUtil.php';
require_once __DIR__ . '/../model/AturanKost.php';

class AturanKostDao {
    /**
     * Menarik semua list tata tertib dari database.
     */
    public function showAllAturan() {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM aturan_kost ORDER BY created_at DESC";
        $stmt = $link->prepare($query);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $a = new AturanKost();
            $a->setIdAturan($row['id_aturan']);
            $a->setJudulAturan($row['judul_aturan']);
            $a->setDeskripsiAturan($row['deskripsi_aturan']);
            $a->setCreatedAt($row['created_at']);
            $a->setUpdatedAt($row['updated_at']);
            $result[] = $a;
        }
        $link = null;
        return $result;
    }

    /**
     * Mengambil data satu aturan spesifik untuk keperluan edit.
     */
    public function getAturanById($id) {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM aturan_kost WHERE id_aturan = ?";
        $stmt = $link->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        $a = new AturanKost();
        $a->setIdAturan($row['id_aturan']);
        $a->setJudulAturan($row['judul_aturan']);
        $a->setDeskripsiAturan($row['deskripsi_aturan']);
        $a->setCreatedAt($row['created_at']);
        $a->setUpdatedAt($row['updated_at']);
        $link = null;
        return $a;
    }

    /**
     * Menyimpan tata tertib baru ke tabel aturan_kost.
     */
    public function addAturan(AturanKost $aturan) {
        $link = PDOUtil::createConnection();
        $query = "CALL sp_insert_aturan(?, ?)";
        $stmt = $link->prepare($query);
        $stmt->bindValue(1, $aturan->getJudulAturan());
        $stmt->bindValue(2, $aturan->getDeskripsiAturan());
        $result = $stmt->execute();
        $link = null;
        return $result;
    }

    /**
     * Menimpa data aturan lama dengan aturan baru hasil editan.
     */
    public function updateAturan(AturanKost $aturan) {
        $link = PDOUtil::createConnection();
        $query = "UPDATE aturan_kost SET judul_aturan = ?, deskripsi_aturan = ?, updated_at = NOW() WHERE id_aturan = ?";
        $stmt = $link->prepare($query);
        $stmt->bindValue(1, $aturan->getJudulAturan());
        $stmt->bindValue(2, $aturan->getDeskripsiAturan());
        $stmt->bindValue(3, $aturan->getIdAturan());
        $result = $stmt->execute();
        $link = null;
        return $result;
    }

    /**
     * Menghapus aturan dari database secara permanen.
     */
    public function deleteAturan($id) {
        $link = PDOUtil::createConnection();
        $query = "DELETE FROM aturan_kost WHERE id_aturan = ?";
        $stmt = $link->prepare($query);
        $stmt->bindValue(1, $id);
        $result = $stmt->execute();
        $link = null;
        return $result;
    }
}
?>