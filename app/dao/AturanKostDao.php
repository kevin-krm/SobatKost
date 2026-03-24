<?php
namespace App\Dao;

use App\Model\AturanKost;
use PDO;

include_once 'PDOUtil.php';

class AturanKostDao {
    public function showAllAturan() {
        $link = \PDOUtil::createMySQLConnection();
        $query = "SELECT * FROM aturan_kost ORDER BY created_at DESC";
        $stmt = $link->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, AturanKost::class);
        $stmt->execute();
        $link = null;
        return $stmt->fetchAll();
    }

    public function addAturan(AturanKost $aturan) {
        $link = \PDOUtil::createMySQLConnection();
        $query = "CALL sp_insert_aturan(?, ?)";
        $stmt = $link->prepare($query);
        $stmt->bindValue(1, $aturan->getJudulAturan());
        $stmt->bindValue(2, $aturan->getDeskripsiAturan());
        $result = $stmt->execute();
        $link = null;
        return $result;
    }
}
?>