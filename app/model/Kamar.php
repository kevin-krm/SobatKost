<?php
class Kamar {
    private $id_kamar;
    private $nomor_kamar;
    private $tipe_kamar;
    private $status_kamar;
    private $harga_dasar;

    public function __construct($id, $nomor, $tipe, $status, $harga) {
        $this->id_kamar = $id;
        $this->nomor_kamar = $nomor;
        $this->tipe_kamar = $tipe;
        $this->status_kamar = $status;
        $this->harga_dasar = $harga;
    }

    public function getId() {
        return $this->id_kamar;
    }

    public function getNomorKamar() {
        return $this->nomor_kamar;
    }

    public function getTipeKamar() {
        return $this->tipe_kamar;
    }

    public function getStatusKamar() {
        return $this->status_kamar;
    }

    public function getHargaDasar() {
        return $this->harga_dasar;
    }
}
?>