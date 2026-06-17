<?php
require_once __DIR__ . '/Subject.php';
require_once __DIR__ . '/Observer.php';

/**
 * Menerapkan Observer Pattern
 * Berfungsi sebagai sumber informasi utama. 
 * Setiap kali status komplain diubah oleh admin, class ini akan otomatis memberi sinyal (notify) kepada DashboardNotifier agar layar penyewa langsung diperbarui (update).
 */
class Komplain implements Subject {
    private $id_komplain;
    private $id_pengguna;
    private $judul_masalah;
    private $deskripsi;
    private $status_komplain;
    private $tanggal_lapor;

    private $nama_pengguna;
    private $id_kamar;

    // Array untuk menampung siapa saja yang butuh notifikasi
    private $observers = [];

    /**
     * CONSTRUCTOR
     * Agar seragam dengan Kamar.php dan memudahkan DAO membuat objek
     */
    public function __construct(
        $id_komplain = null,
        $id_pengguna = null,
        $judul_masalah = null,
        $deskripsi = null,
        $status_komplain = 'Menunggu',
        $tanggal_lapor = null
    ) {
        $this->id_komplain = $id_komplain;
        $this->id_pengguna = $id_pengguna;
        $this->judul_masalah = $judul_masalah;
        $this->deskripsi = $deskripsi;
        $this->status_komplain = $status_komplain;
        $this->tanggal_lapor = $tanggal_lapor;
    }

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

    public function getIdKomplain() { return $this->id_komplain; }
    public function setIdKomplain($id_komplain) { $this->id_komplain = $id_komplain; }

    public function getIdPengguna() { return $this->id_pengguna; }
    public function setIdPengguna($id_pengguna) { $this->id_pengguna = $id_pengguna; }

    public function getJudulMasalah() { return $this->judul_masalah; }
    public function setJudulMasalah($judul_masalah) { $this->judul_masalah = $judul_masalah; }

    public function getDeskripsi() { return $this->deskripsi; }
    public function setDeskripsi($deskripsi) { $this->deskripsi = $deskripsi; }

    public function getStatusKomplain() { return $this->status_komplain; }

    // Setter khusus untuk status_komplain yang memicu notifikasi Observer
    public function setStatusKomplain($status_komplain) {
        $this->status_komplain = $status_komplain;
        $this->notify(); // Trigger pattern saat status berubah
    }

    public function getTanggalLapor() { return $this->tanggal_lapor; }
    public function setTanggalLapor($tanggal_lapor) { $this->tanggal_lapor = $tanggal_lapor; }

    // Setter Getter untuk relasi data (Nama dan Kamar)
    public function getNamaPengguna() { return $this->nama_pengguna; }
    public function setNamaPengguna($nama_pengguna) { $this->nama_pengguna = $nama_pengguna; }

    public function getIdKamar() { return $this->id_kamar; }
    public function setIdKamar($id_kamar) { $this->id_kamar = $id_kamar; }
}
?>