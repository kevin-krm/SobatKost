<?php
namespace App\Dao;

use App\Model\Pengumuman;
use PDO;

include_once 'PDOUtil.php';

class PengumumanDao {
    public function showAllPengumuman() {
        $link = \PDOUtil::createMySQLConnection();
        $query = "SELECT * FROM pengumuman ORDER BY tanggal_siar DESC";
        $stmt = $link->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Pengumuman::class);
        $stmt->execute();
        $link = null;
        return $stmt->fetchAll();
    }

    public function addPengumuman(Pengumuman $pengumuman) {
        $link = \PDOUtil::createMySQLConnection();
        // Memanggil Stored Procedure sesuai instruksi
        $query = "CALL sp_insert_pengumuman(?, ?)";
        $stmt = $link->prepare($query);
        $stmt->bindValue(1, $pengumuman->getJudul());
        $stmt->bindValue(2, $pengumuman->getKonten());
        $result = $stmt->execute();
        $link = null;
        return $result;
    }
}
?>