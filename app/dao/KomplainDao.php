<?php
require_once __DIR__ . '/PDOUtil.php';
require_once __DIR__ . '/../model/Komplain.php';

class KomplainDao {

    /**
     * Mengambil data komplain dengan pagination (Standar Kevin)
     */
    public function getKomplainPage($limit, $offset) {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM komplain ORDER BY tanggal_lapor DESC LIMIT :limit OFFSET :offset";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Komplain(
                $row['id_komplain'],
                $row['id_pengguna'],
                $row['judul_masalah'],
                $row['deskripsi'],
                $row['status_komplain'],
                $row['tanggal_lapor']
            );
        }
        return $result;
    }

    /**
     * Menghitung total data komplain untuk pagination
     */
    public function countKomplain() {
        $link = PDOUtil::createConnection();
        return $link->query("SELECT COUNT(*) FROM komplain")->fetchColumn();
    }

    /**
     * Mengambil 1 data komplain berdasarkan ID
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
            $row['id_komplain'],
            $row['id_pengguna'],
            $row['judul_masalah'],
            $row['deskripsi'],
            $row['status_komplain'],
            $row['tanggal_lapor']
        );
    }

    /**
     * Menambahkan komplain baru memanggil Stored Procedure
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

    /**
     * Memperbarui status komplain (Terkait Observer Pattern)
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
     * Menghapus data komplain
     */
    public function deleteKomplain($id) {
        $link = PDOUtil::createConnection();
        $query = "DELETE FROM komplain WHERE id_komplain = :id";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':id', $id);

        $stmt->execute();
    }
}
?>