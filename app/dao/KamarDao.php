<?php
require_once __DIR__ . '/PDOUtil.php';
require_once __DIR__ . '/../model/Kamar.php';

class KamarDao {

    public function getAllKamar() {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM kamar ORDER BY id_kamar DESC";
        $stmt = $link->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Kamar');
    }

    public function insertKamar($nomor, $tipe, $harga) {
        $link = PDOUtil::createConnection();
        try {
            $query = "CALL sp_insert_kamar(:no, :tipe, :harga)";
            $stmt = $link->prepare($query);
            $stmt->bindParam(':no', $nomor);
            $stmt->bindParam(':tipe', $tipe);
            $stmt->bindParam(':harga', $harga);
            $stmt->execute();
            PDOUtil::closeConnection();
            return true;
        } catch (PDOException $e) {
            PDOUtil::closeConnection();
            die("Gagal menambah kamar: " . $e->getMessage());
        }
    }

    public function getKamarById($id) {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM kamar WHERE id_kamar = :id";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateKamar($id, $nomor, $tipe, $status, $harga) {
        $link = PDOUtil::createConnection();
        $query = "UPDATE kamar 
                  SET nomor_kamar=:no, tipe_kamar=:tipe, status_kamar=:status, harga_dasar=:harga
                  WHERE id_kamar=:id";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':no', $nomor);
        $stmt->bindParam(':tipe', $tipe);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':harga', $harga);
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
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Kamar');
    }

    public function countKamar() {
        $link = PDOUtil::createConnection();
        $query = "SELECT COUNT(*) FROM kamar";
        $stmt = $link->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}