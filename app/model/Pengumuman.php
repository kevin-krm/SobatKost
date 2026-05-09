<?php

class Pengumuman {
    private $id_pengumuman;
    private $judul;
    private $konten;
    private $tanggal_siar;
    private $created_at;
    private $updated_at;

    public function getIdPengumuman() { return $this->id_pengumuman; }
    public function setIdPengumuman($id_pengumuman) { $this->id_pengumuman = $id_pengumuman; }

    public function getJudul() { return $this->judul; }
    public function setJudul($judul) { $this->judul = $judul; }

    public function getKonten() { return $this->konten; }
    public function setKonten($konten) { $this->konten = $konten; }

    public function getTanggalSiar() { return $this->tanggal_siar; }
    public function setTanggalSiar($tanggal_siar) { $this->tanggal_siar = $tanggal_siar; }

    public function getCreatedAt() { return $this->created_at; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }

    public function getUpdatedAt() { return $this->updated_at; }
    public function setUpdatedAt($updated_at) { $this->updated_at = $updated_at; }
}
?>