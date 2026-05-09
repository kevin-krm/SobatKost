<?php
class BiayaOperasional {
    private $id_biaya;
    private $kategori_biaya;
    private $jumlah_biaya;
    private $tanggal_pengeluaran;
    private $keterangan;

    public function __construct($id_biaya, $kategori_biaya, $jumlah_biaya, $tanggal_pengeluaran, $keterangan) {
        $this->id_biaya = $id_biaya;
        $this->kategori_biaya = $kategori_biaya;
        $this->jumlah_biaya = $jumlah_biaya;
        $this->tanggal_pengeluaran = $tanggal_pengeluaran;
        $this->keterangan = $keterangan;
    }

    public function getIdBiaya() { return $this->id_biaya; }
    public function getKategoriBiaya() { return $this->kategori_biaya; }
    public function getJumlahBiaya() { return $this->jumlah_biaya; }
    public function getTanggalPengeluaran() { return $this->tanggal_pengeluaran; }
    public function getKeterangan() { return $this->keterangan; }
}
?>
