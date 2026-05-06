<?php
require_once __DIR__ . '/PDOUtil.php';
require_once __DIR__ . '/../model/Kamar.php';

class KamarDao {

    public function getAllKamar() {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM kamar ORDER BY id_kamar DESC";
        $stmt = $link->prepare($query);
        $stmt->execute();

        $result = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Kamar(
                $row['id_kamar'],
                $row['nomor_kamar'],
                $row['tipe_kamar'],
                $row['status_kamar'],
                $row['harga_dasar']
            );
        }

        return $result;
    }

    public function getKamarById($id) {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM kamar WHERE id_kamar = :id";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new Kamar(
            $row['id_kamar'],
            $row['nomor_kamar'],
            $row['tipe_kamar'],
            $row['status_kamar'],
            $row['harga_dasar']
        );
    }

    public function insertKamar(Kamar $kamar) {
        $link = PDOUtil::createConnection();

        $query = "CALL sp_insert_kamar(:no, :tipe, :harga)";
        $stmt = $link->prepare($query);

        $stmt->bindValue(':no', $kamar->getNomorKamar());
        $stmt->bindValue(':tipe', $kamar->getTipeKamar());
        $stmt->bindValue(':harga', $kamar->getHargaDasar());

        $stmt->execute();
    }

    public function updateKamar(Kamar $kamar) {
        $link = PDOUtil::createConnection();

        $query = "UPDATE kamar 
                  SET nomor_kamar=:no, tipe_kamar=:tipe, status_kamar=:status, harga_dasar=:harga
                  WHERE id_kamar=:id";

        $stmt = $link->prepare($query);

        $stmt->bindValue(':id', $kamar->getId());
        $stmt->bindValue(':no', $kamar->getNomorKamar());
        $stmt->bindValue(':tipe', $kamar->getTipeKamar());
        $stmt->bindValue(':status', $kamar->getStatusKamar());
        $stmt->bindValue(':harga', $kamar->getHargaDasar());

        $stmt->execute();
    }

    public function deleteKamar($id) {
        $link = PDOUtil::createConnection();
        $query = "DELETE FROM kamar WHERE id_kamar=:id";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function getKamarPage($limit, $offset) {
        $link = PDOUtil::createConnection();

        $query = "SELECT * FROM kamar
                  ORDER BY created_at DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $link->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $result = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Kamar(
                $row['id_kamar'],
                $row['nomor_kamar'],
                $row['tipe_kamar'],
                $row['status_kamar'],
                $row['harga_dasar']
            );
        }

        return $result;
    }

    public function countKamar() {
        $link = PDOUtil::createConnection();
        return $link->query("SELECT COUNT(*) FROM kamar")->fetchColumn();
    }
}