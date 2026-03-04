<?php

include_once 'PDOUtil.php';
include_once 'model/Kamar.php';

class KamarDao{
    public static function showAllKamar(){
        // $link = PDOUtil::createMySQLConnection();
        // $query = "SELECT * FROM kamar";
        // $stmt = $link->prepare($query);
        // $stmt -> setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Kamar::class);
        // $stmt -> execute();
        // $link = null;
        // return $stmt->fetchAll();
    }
    
    public function addKamar(Kamar $kamar){
        // Koneksi ke database
        // Query SQL
    }

    public function updateKamar(Kamar $kamar){
        // Koneksi ke database
        // Query SQL
    }

    public function deleteKamar($noKamar){
        // Koneksi ke database
        // Query SQL
    }
}
?>