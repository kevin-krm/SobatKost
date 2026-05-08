<?php
require_once __DIR__ . '/PDOUtil.php';
require_once __DIR__ . '/../model/Komplain.php';

class KomplainDao {

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
                $row['id_komplain'], $row['id_pengguna'], $row['judul_masalah'],
                $row['deskripsi'], $row['status_komplain'], $row['tanggal_lapor']
            );
        }
        return $result;
    }

    public function countKomplain() {
        $link = PDOUtil::createConnection();
        return $link->query("SELECT COUNT(*) FROM komplain")->fetchColumn();
    }

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

    public function updateStatus(Komplain $komplain) {
        $link = PDOUtil::createConnection();
        $query = "UPDATE komplain SET status_komplain = :status WHERE id_komplain = :id";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':status', $komplain->getStatusKomplain());
        $stmt->bindValue(':id', $komplain->getIdKomplain());
        $stmt->execute();
    }

    public function deleteKomplain($id) {
        $link = PDOUtil::createConnection();
        $query = "DELETE FROM komplain WHERE id_komplain = :id";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}