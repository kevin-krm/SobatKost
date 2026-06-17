<?php
interface TagihanComponent
{
    public function getTotalTagihan();
    public function getRincianTagihan();
}

class TagihanSewaComponent implements TagihanComponent
{
    private $total_biaya_sewa;

    public function __construct($total_biaya_sewa)
    {
        $this->total_biaya_sewa = $total_biaya_sewa;
    }

    public function getTotalTagihan()
    {
        return $this->total_biaya_sewa;
    }

    public function getRincianTagihan()
    {
        return [
            [
                'nama' => 'Biaya sewa',
                'jumlah' => $this->total_biaya_sewa
            ]
        ];
    }
}

/**
 * Menerapkan Decorator Pattern (Structural).
 * Memungkinkan kita untuk membungkus (menumpuk) biaya fasilitas tambahan 
 * (seperti kipas angin/parkir) di atas biaya sewa dasar secara dinamis, 
 * tanpa harus mengubah kode rumus tagihan intinya.
 */
abstract class TagihanDecorator implements TagihanComponent
{
    protected $tagihan;

    public function __construct(TagihanComponent $tagihan)
    {
        $this->tagihan = $tagihan;
    }

    public function getRincianTagihan()
    {
        return $this->tagihan->getRincianTagihan();
    }
}

class BiayaTambahanTagihanDecorator extends TagihanDecorator
{
    private $nama_biaya;
    private $jumlah_biaya;

    public function __construct(TagihanComponent $tagihan, $nama_biaya, $jumlah_biaya)
    {
        parent::__construct($tagihan);
        $this->nama_biaya = $nama_biaya;
        $this->jumlah_biaya = $jumlah_biaya;
    }

    public function getTotalTagihan()
    {
        return $this->tagihan->getTotalTagihan() + $this->jumlah_biaya;
    }

    public function getRincianTagihan()
    {
        $rincian = parent::getRincianTagihan();
        $rincian[] = [
            'nama' => $this->nama_biaya,
            'jumlah' => $this->jumlah_biaya
        ];

        return $rincian;
    }
}

class InventarisTambahanTagihanDecorator extends BiayaTambahanTagihanDecorator
{
    public function __construct(TagihanComponent $tagihan, $jumlah_biaya)
    {
        parent::__construct($tagihan, 'Biaya tambahan inventaris', $jumlah_biaya);
    }
}

class Tagihan
{
    private $id_tagihan;
    private $id_kontrak;
    private $total_biaya_sewa;
    private $biaya_tambahan;
    private $komponen_tagihan;
    private $tanggal_jatuh_tempo;
    private $status_tagihan;
    private $tipe_sewa; // Harian, Bulanan, Tahunan
    private $created_at;
    private $updated_at;
    private $nama_lengkap;
    private $nomor_kamar;
    private $id_pengguna;

    public function __construct(
        $id_tagihan = null,
        $id_kontrak,
        $total_biaya_sewa,
        $biaya_tambahan = 0,
        $tanggal_jatuh_tempo,
        $status_tagihan = 'Belum Lunas',
        $tipe_sewa = null,
        $created_at = null,
        $updated_at = null,
        $nama_lengkap = null,
        $nomor_kamar = null,
        $id_pengguna = null
    ) {
        $this->id_tagihan = $id_tagihan;
        $this->id_kontrak = $id_kontrak;
        $this->total_biaya_sewa = $total_biaya_sewa;
        $this->biaya_tambahan = $biaya_tambahan;
        $this->komponen_tagihan = $this->buildKomponenTagihan();
        $this->tanggal_jatuh_tempo = $tanggal_jatuh_tempo;
        $this->status_tagihan = $status_tagihan;
        $this->tipe_sewa = $tipe_sewa;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->nama_lengkap = $nama_lengkap;
        $this->nomor_kamar = $nomor_kamar;
        $this->id_pengguna = $id_pengguna;
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
    public function getNamaLengkap(){return $this->nama_lengkap; }
    public function getNomorKamar(){return $this->nomor_kamar; }
    public function getIdPengguna(){return $this->id_pengguna; }

    // Setter
    public function setIdTagihan($id) { $this->id_tagihan = $id; }
    public function setStatusTagihan($status) { $this->status_tagihan = $status; }
    public function setBiayaTambahan($biaya)
    {
        $this->biaya_tambahan = $biaya;
        $this->komponen_tagihan = $this->buildKomponenTagihan();
    }
    public function setTanggalJatuhTempo($tanggal) { $this->tanggal_jatuh_tempo = $tanggal; }
    public function setUpdatedAt($updated_at) { $this->updated_at = $updated_at; }

    private function buildKomponenTagihan()
    {
        $komponen = new TagihanSewaComponent($this->total_biaya_sewa);

        if ($this->biaya_tambahan > 0) {
            $komponen = new InventarisTambahanTagihanDecorator($komponen, $this->biaya_tambahan);
        }

        return $komponen;
    }

    // Hitung total tagihan menggunakan Decorator Pattern (GoF)
    public function getTotalTagihan()
    {
        return $this->komponen_tagihan->getTotalTagihan();
    }

    public function getRincianTagihan()
    {
        return $this->komponen_tagihan->getRincianTagihan();
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
