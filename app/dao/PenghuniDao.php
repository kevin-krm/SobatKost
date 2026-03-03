<?php

include_once 'PDOUtil.php';
include_once 'model/Penghuni.php';

class PenghuniDao{
    public static function showAllPenghuni(){
        // $link = PDOUtil::createMySQLConnection();
        //$query = "SELECT * FROM penghuni";
        //$stmt = $link->prepare($query);
        //$stmt -> setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, penghuni::class);
        //$stmt -> execute();
        //$link = null;
        //return $stmt->fetchAll();
    }
    public function addPenghuni (Penghuni $penghuni){
        // Koneksi ke database
        // Query SQL
    }

    public function updatePenghuni(Penghuni $penghuni){
        // Koneksi ke database
        // Query SQL
    }

    public function deletePenghuni($kodePenghuni){
        // Koneksi ke database
        // Query SQL
    }
}
?>