<?php
class Inventaris {
    private $id_inventaris;
    private $id_kamar;
    private $nama_barang;
    private $kondisi_barang;

    public function __construct($id_inventaris, $id_kamar, $nama_barang, $kondisi_barang) {
        $this->id_inventaris = $id_inventaris;
        $this->id_kamar = $id_kamar;
        $this->nama_barang = $nama_barang;
        $this->kondisi_barang = $kondisi_barang;
    }

    public function getIdInventaris() { return $this->id_inventaris; }
    public function getIdKamar() { return $this->id_kamar; }
    public function getNamaBarang() { return $this->nama_barang; }
    public function getKondisiBarang() { return $this->kondisi_barang; }
}
?>