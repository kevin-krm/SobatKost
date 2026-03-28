<?php
namespace App\Dao;

use App\Model\Inventaris;
use PDO;

class InventarisDao {
    public function showInventarisByKamar($id_kamar) {
        $link = \PDOUtil::createMySQLConnection();
        $query = "SELECT * FROM inventaris_kamar WHERE id_kamar = ?";
        $stmt = $link->prepare($query);
        $stmt->bindValue(1, $id_kamar);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Inventaris::class);
        $stmt->execute();
        $link = null;
        return $stmt->fetchAll();
    }

    public function addInventaris(Inventaris $inventaris) {
        $link = \PDOUtil::createMySQLConnection();
        $query = "CALL sp_insert_inventaris(?, ?, ?)";
        $stmt = $link->prepare($query);
        $stmt->bindValue(1, $inventaris->getIdKamar());
        $stmt->bindValue(2, $inventaris->getNamaBarang());
        $stmt->bindValue(3, $inventaris->getKondisiBarang());
        $result = $stmt->execute();
        $link = null;
        return $result;
    }
}