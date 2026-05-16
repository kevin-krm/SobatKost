<?php
class Tagihan
{
    private $id_tagihan;
    private $id_kontrak;
    private $total_biaya_sewa;
    private $biaya_tambahan;
    private $tanggal_jatuh_tempo;
    private $status_tagihan;
    private $tipe_sewa; // Harian, Bulanan, Tahunan
    private $created_at;
    private $updated_at;

    public function __construct(
        $id_tagihan = null,
        $id_kontrak,
        $total_biaya_sewa,
        $biaya_tambahan = 0,
        $tanggal_jatuh_tempo,
        $status_tagihan = 'Belum Lunas',
        $tipe_sewa = null,
        $created_at = null,
        $updated_at = null
    ) {
        $this->id_tagihan = $id_tagihan;
        $this->id_kontrak = $id_kontrak;
        $this->total_biaya_sewa = $total_biaya_sewa;
        $this->biaya_tambahan = $biaya_tambahan;
        $this->tanggal_jatuh_tempo = $tanggal_jatuh_tempo;
        $this->status_tagihan = $status_tagihan;
        $this->tipe_sewa = $tipe_sewa;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // Getter
    public function getIdTagihan() { return $this->id_tagihan; }
    public function getIdKontrak() { return $this->id_kontrak; }
    public function getTotalBiayaSewa() { return $this->total_biaya_sewa; }
    public function getBiayaTambahan() { return $this->biaya_tambahan; }
    public function getTanggalJatuhTempo() { return $this->tanggal_jatuh_tempo; }
    public function getStatusTagihan() { return $this->status_tagihan; }
    public function getTipeSewa() { return $this->tipe_sewa; }
    public function getCreatedAt() { return $this->created_at; }
    public function getUpdatedAt() { return $this->updated_at; }

    // Setter
    public function setIdTagihan($id) { $this->id_tagihan = $id; }
    public function setStatusTagihan($status) { $this->status_tagihan = $status; }
    public function setBiayaTambahan($biaya) { $this->biaya_tambahan = $biaya; }
    public function setTanggalJatuhTempo($tanggal) { $this->tanggal_jatuh_tempo = $tanggal; }
    public function setUpdatedAt($updated_at) { $this->updated_at = $updated_at; }

    // Hitung total tagihan (sewa + tambahan)
    public function getTotalTagihan()
    {
        return $this->total_biaya_sewa + $this->biaya_tambahan;
    }

    // Cek tagihan overdue
    public function isOverdue()
    {
        $jatuh_tempo = strtotime($this->tanggal_jatuh_tempo);
        $hari_ini = time();
        return $hari_ini > $jatuh_tempo && $this->status_tagihan === 'Belum Lunas';
    }

    // Format currency
    public function formatTotalTagihan()
    {
        return "Rp " . number_format($this->getTotalTagihan(), 0, ',', '.');
    }
}
?>
