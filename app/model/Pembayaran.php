<?php
class Pembayaran
{
    private $id_pembayaran;
    private $id_tagihan;
    private $metode_pembayaran;
    private $bukti_pembayaran;
    private $tanggal_bayar;
    private $status_verifikasi;
    private $created_at;
    private $updated_at;

    public function __construct(
        $id_pembayaran = null,
        $id_tagihan,
        $metode_pembayaran,
        $bukti_pembayaran = null,
        $tanggal_bayar = null,
        $status_verifikasi = 'Proses',
        $created_at = null,
        $updated_at = null
    ) {
        $this->id_pembayaran = $id_pembayaran;
        $this->id_tagihan = $id_tagihan;
        $this->metode_pembayaran = $metode_pembayaran;
        $this->bukti_pembayaran = $bukti_pembayaran;
        $this->tanggal_bayar = $tanggal_bayar ?? date('Y-m-d H:i:s');
        $this->status_verifikasi = $status_verifikasi;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // Getter
    public function getIdPembayaran() { return $this->id_pembayaran; }
    public function getIdTagihan() { return $this->id_tagihan; }
    public function getMetodePembayaran() { return $this->metode_pembayaran; }
    public function getBuktiPembayaran() { return $this->bukti_pembayaran; }
    public function getTanggalBayar() { return $this->tanggal_bayar; }
    public function getStatusVerifikasi() { return $this->status_verifikasi; }
    public function getCreatedAt() { return $this->created_at; }
    public function getUpdatedAt() { return $this->updated_at; }

    // Setter
    public function setIdPembayaran($id) { $this->id_pembayaran = $id; }
    public function setStatusVerifikasi($status) { $this->status_verifikasi = $status; }
    public function setBuktiPembayaran($bukti) { $this->bukti_pembayaran = $bukti; }
    public function setUpdatedAt($updated_at) { $this->updated_at = $updated_at; }

    // Status badge helper
    public function getStatusBadge()
    {
        $badges = [
            'Proses' => 'bg-warning text-dark',
            'Berhasil' => 'bg-success text-white',
            'Ditolak' => 'bg-danger text-white'
        ];
        return $badges[$this->status_verifikasi] ?? 'bg-secondary text-white';
    }

    // Metode pembayaran icon
    public function getMetodeIcon()
    {
        $icons = [
            'Transfer' => 'bi-bank',
            'E-Wallet' => 'bi-wallet2',
            'Tunai' => 'bi-cash-coin'
        ];
        return $icons[$this->metode_pembayaran] ?? 'bi-question-circle';
    }
}
?>
