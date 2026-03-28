<?php
namespace App\Dao;

use App\Model\Komplain;
use PDO;
use Exception;

include_once 'PDOUtil.php';

class KomplainDao {

    /**
     * Mengambil semua data komplain dari database
     */
    public static function showAllKomplain() {
        $link = \PDOUtil::createMySQLConnection();
        // Mengambil data dan diurutkan dari yang terbaru
        $query = "SELECT * FROM komplain ORDER BY tanggal_lapor DESC";
        $stmt = $link->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Komplain::class);
        $stmt->execute();
        $link = null;
        return $stmt->fetchAll();
    }

    /**
     * Menambahkan komplain baru (Wajib pakai Stored Procedure sesuai aturan kelompok)
     */
    public function addKomplain(Komplain $komplain) {
        $link = \PDOUtil::createMySQLConnection();
        // Memanggil Stored Procedure sp_insert_komplain
        $query = "CALL sp_insert_komplain(?, ?, ?)";
        $stmt = $link->prepare($query);
        $stmt->bindValue(1, $komplain->getIdPengguna());
        $stmt->bindValue(2, $komplain->getJudulMasalah());
        $stmt->bindValue(3, $komplain->getDeskripsi());

        $result = $stmt->execute();
        $link = null;
        return $result;
    }

    /**
     * Memperbarui komplain (Khususnya status untuk memicu Observer Pattern)
     */
    public function updateKomplain(Komplain $komplain) {
        $link = \PDOUtil::createMySQLConnection();
        // Kita fokus mengupdate status_komplain berdasarkan ID
        $query = "UPDATE komplain SET status_komplain = ? WHERE id_komplain = ?";
        $stmt = $link->prepare($query);
        $stmt->bindValue(1, $komplain->getStatusKomplain());
        $stmt->bindValue(2, $komplain->getIdKomplain());

        $result = $stmt->execute();
        $link = null;
        return $result;
    }

    /**
     * Menghapus data komplain berdasarkan ID
     */
    public function deleteKomplain($noKomplain) {
        $link = \PDOUtil::createMySQLConnection();
        $query = "DELETE FROM komplain WHERE id_komplain = ?";
        $stmt = $link->prepare($query);
        $stmt->bindValue(1, $noKomplain);

        $result = $stmt->execute();
        $link = null;
        return $result;
    }
}
?>