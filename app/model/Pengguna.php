<?php
class Pengguna {

    private $id_pengguna;
    private $id_peran;
    private $nama_lengkap;
    private $nomor_telepon;
    private $email;
    private $kata_sandi;
    private $created_at;
    private $foto_ktp;
    private $status_aktif;
    private $nama_peran;

    public function __construct(
        $id,
        $peran,
        $nama,
        $telp,
        $email,
        $password,
        $created,
        $foto,
        $statusAktif = 'aktif',
        $namaPeran = null
    ) {
        $this->id_pengguna = $id;
        $this->id_peran = $peran;
        $this->nama_lengkap = $nama;
        $this->nomor_telepon = $telp;
        $this->email = $email;
        $this->kata_sandi = $password;
        $this->created_at = $created;
        $this->foto_ktp = $foto;
        $this->status_aktif = $statusAktif;
        $this->nama_peran = $namaPeran;
    }

    public function getId() { return $this->id_pengguna; }
    public function getIdPeran() { return $this->id_peran; }
    public function getNamaLengkap() { return $this->nama_lengkap; }
    public function getNomorTelepon() { return $this->nomor_telepon; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->kata_sandi; }
    public function getCreatedAt() { return $this->created_at; }
    public function getFotoKtp() { return $this->foto_ktp; }
    public function getStatusAktif() { return $this->status_aktif; }
    public function getNamaPeran() { return $this->nama_peran; }
}