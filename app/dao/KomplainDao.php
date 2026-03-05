<?php

include_once 'PDOUtil.php';
include_once 'model/Komplain.php';

class KomplainDao{
    public static function showAllKomplain(){
        // $link = PDOUtil::createMySQLConnection();
        // $query = "SELECT * FROM komplain";
        // $stmt = $link->prepare($query);
        // $stmt -> setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Komplain::class);
        // $stmt -> execute();
        // $link = null;
        // return $stmt->featchAll();
    }
    
    public function addKomplain(Komplain $komplain){
        // Koneksi ke database
        // Query SQL
    }

    public function updateKomplain(Komplain $komplain){
        // Koneksi ke database
        // Query SQL
    }

    public function deleteKomplain($noKomplain){
        // Koneksi ke database
        // Query SQL
    }
}
?>