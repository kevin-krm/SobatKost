<?php

class Kontrak
{
    const STATUS_VALID = 1;
    const STATUS_AKTIF = 2;
    const STATUS_SELESAI = 0;

    private $id_kontrak;
    private $id_pengguna;
    private $id_kamar;
    private $tanggal_mulai;
    private $tanggal_selesai;
    private $tipe_sewa;
    private $status_aktif;
    private $nama_lengkap;
    private $nomor_kamar;
    private $harga_dasar;

    public function __construct(
        $id_kontrak,
        $id_pengguna,
        $id_kamar,
        $tanggal_mulai,
        $tanggal_selesai,
        $tipe_sewa,
        $status_aktif,
        $nama_lengkap = null,
        $nomor_kamar = null,
        $harga_dasar = null
    )
    {
        $this->id_kontrak = $id_kontrak;
        $this->id_pengguna = $id_pengguna;
        $this->id_kamar = $id_kamar;
        $this->tanggal_mulai = $tanggal_mulai;
        $this->tanggal_selesai = $tanggal_selesai;
        $this->tipe_sewa = $tipe_sewa;
        $this->status_aktif = $status_aktif;
        $this->nama_lengkap = $nama_lengkap;
        $this->nomor_kamar = $nomor_kamar;
        $this->harga_dasar = $harga_dasar;
    }

    public function getNamaLengkap() {
        return $this->nama_lengkap;
    }

    public function getIdKontrak()
    {
        return $this->id_kontrak;
    }

    public function getIdPengguna()
    {
        return $this->id_pengguna;
    }

    public function getIdKamar()
    {
        return $this->id_kamar;
    }

    public function getTanggalMulai()
    {
        return $this->tanggal_mulai;
    }

    public function getTanggalSelesai()
    {
        return $this->tanggal_selesai;
    }

    public function getTipeSewa()
    {
        return $this->tipe_sewa;
    }

    public function getStatusAktif()
    {
        return $this->status_aktif;
    }

    public function getNomorKamar()
    {
        return $this->nomor_kamar;
    }

    public function getHargaDasar()
    {
        return $this->harga_dasar;
    }

    public function getStatusLabel() {
        switch ($this->status_aktif) {
            case self::STATUS_VALID: return "Valid (Menunggu)";
            case self::STATUS_AKTIF: return "Aktif (Dalam Sewa)";
            case self::STATUS_SELESAI: return "Selesai/Nonaktif";
            default: return "Tidak Diketahui";
        }
    }
}