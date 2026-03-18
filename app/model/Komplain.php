<?php
namespace App\Model;

class Komplain implements Subject {
    private $id_komplain;
    private $id_pengguna;
    private $judul_masalah;
    private $deskripsi;
    private $status_komplain;
    private $tanggal_lapor;
    private $created_at;
    private $updated_at;

    // Array untuk menampung siapa saja yang butuh notifikasi
    private $observers = [];

    // ==========================================
    // IMPLEMENTASI OBSERVER PATTERN
    // ==========================================

    public function attach(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer) {
        $index = array_search($observer, $this->observers);
        if ($index !== false) {
            unset($this->observers[$index]);
        }
    }

    public function notify() {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    // ==========================================
    // GETTER & SETTER
    // ==========================================

    public function getIdKomplain() {
        return $this->id_komplain;
    }

    public function setIdKomplain($id_komplain) {
        $this->id_komplain = $id_komplain;
    }

    public function getIdPengguna() {
        return $this->id_pengguna;
    }

    public function setIdPengguna($id_pengguna) {
        $this->id_pengguna = $id_pengguna;
    }

    public function getJudulMasalah() {
        return $this->judul_masalah;
    }

    public function setJudulMasalah($judul_masalah) {
        $this->judul_masalah = $judul_masalah;
    }

    public function getDeskripsi() {
        return $this->deskripsi;
    }

    public function setDeskripsi($deskripsi) {
        $this->deskripsi = $deskripsi;
    }

    public function getStatusKomplain() {
        return $this->status_komplain;
    }

    // Setter khusus untuk status_komplain yang memicu notifikasi Observer
    public function setStatusKomplain($status_komplain) {
        $this->status_komplain = $status_komplain;
        $this->notify(); // Trigger pattern saat status berubah
    }

    public function getTanggalLapor() {
        return $this->tanggal_lapor;
    }

    public function setTanggalLapor($tanggal_lapor) {
        $this->tanggal_lapor = $tanggal_lapor;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt() {
        return $this->updated_at;
    }

    public function setUpdatedAt($updated_at) {
        $this->updated_at = $updated_at;
    }
}
?>