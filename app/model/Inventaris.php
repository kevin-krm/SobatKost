<?php
namespace App\Model;

class Inventaris {
    private $id_inventaris;
    private $id_kamar;
    private $nama_barang;
    private $kondisi_barang;
    private $created_at;
    private $updated_at;

    // Getter & Setter
    public function getIdInventaris() { return $this->id_inventaris; }
    public function setIdInventaris($id) { $this->id_inventaris = $id; }

    public function getIdKamar() { return $this->id_kamar; }
    public function setIdKamar($id_kamar) { $this->id_kamar = $id_kamar; }

    public function getNamaBarang() { return $this->nama_barang; }
    public function setNamaBarang($nama) { $this->nama_barang = $nama; }

    public function getKondisiBarang() { return $this->kondisi_barang; }
    public function setKondisiBarang($kondisi) { $this->kondisi_barang = $kondisi; }
}