<?php
require_once __DIR__ . '/PDOUtil.php';
require_once __DIR__ . '/../model/Penghuni.php';

class PenghuniDao {
    public function getAllPenghuni() {
        $link = PDOUtil::createConnection();
        $query = "SELECT p.id_pengguna, p.nama_lengkap, p.email, p.nomor_telepon, pr.nama_peran 
                  FROM pengguna p 
                  JOIN peran pr ON p.id_peran = pr.id_peran 
                  ORDER BY p.id_pengguna DESC";
        $stmt = $link->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Penghuni');
    }

    public function insertPengguna($peran, $nama, $email, $password) {
        $link = PDOUtil::createConnection();
        try {
            $query = "CALL sp_insert_pengguna(:role, :nama, :email, :pass)";
            $stmt = $link->prepare($query);
            $stmt->bindParam(':role', $peran, PDO::PARAM_INT);
            $stmt->bindParam(':nama', $nama, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':pass', $password, PDO::PARAM_STR);
            $stmt->execute();
            PDOUtil::closeConnection();
            return true;
        } catch (PDOException $e) {
            PDOUtil::closeConnection();
            die("Gagal menambah penghuni: " . $e->getMessage());
        }
    }
}
?>