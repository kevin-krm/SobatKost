<?php
/**
 * Invoice Factory
 * Factory Pattern untuk membuat objek Tagihan berdasarkan tipe sewa
 */
class TagihanFactory
{
    /**
     * Buat tagihan berdasarkan tipe sewa
     * 
     * @param string $tipe_sewa Tipe sewa: Harian, Bulanan, atau Tahunan
     * @param string $id_kontrak ID Kontrak sewa
     * @param float $harga_dasar Harga dasar kamar
     * @param float $biaya_tambahan Biaya tambahan (optional)
     * @param string $tanggal_mulai Tanggal mulai sewa (YYYY-MM-DD)
     * @return Tagihan|null
     */
    public static function createTagihan(
        $tipe_sewa,
        $id_kontrak,
        $harga_dasar,
        $biaya_tambahan = 0,
        $tanggal_mulai = null
    ) {
        if ($tanggal_mulai === null) {
            $tanggal_mulai = date('Y-m-d');
        }

        switch (strtolower($tipe_sewa)) {
            case 'harian':
                return self::createTagihanHarian($id_kontrak, $harga_dasar, $biaya_tambahan, $tanggal_mulai);
            
            case 'bulanan':
                return self::createTagihanBulanan($id_kontrak, $harga_dasar, $biaya_tambahan, $tanggal_mulai);
            
            case 'tahunan':
                return self::createTagihanTahunan($id_kontrak, $harga_dasar, $biaya_tambahan, $tanggal_mulai);
            
            default:
                throw new Exception("Tipe sewa '$tipe_sewa' tidak dikenali");
        }
    }

    /**
     * Buat Tagihan Harian
     * Jatuh tempo: H+1
     * Total biaya: harga_dasar * 1 hari
     */
    private static function createTagihanHarian($id_kontrak, $harga_dasar, $biaya_tambahan, $tanggal_mulai)
    {
        $total_hari = 1;
        $total_biaya_sewa = $harga_dasar * $total_hari;
        
        // Jatuh tempo besok
        $tanggal_jatuh_tempo = date('Y-m-d', strtotime($tanggal_mulai . ' +1 day'));

        $tagihan = new Tagihan(
            null,
            $id_kontrak,
            $total_biaya_sewa,
            $biaya_tambahan,
            $tanggal_jatuh_tempo,
            'Belum Lunas',
            'Harian'
        );

        return $tagihan;
    }

    /**
     * Buat Tagihan Bulanan
     * Jatuh tempo: sebulan dari tanggal mulai
     * Total biaya: harga_dasar * jumlah hari dalam sebulan / 30
     */
    private static function createTagihanBulanan($id_kontrak, $harga_dasar, $biaya_tambahan, $tanggal_mulai)
    {
        // Hitung jumlah hari dalam bulan
        $hari_dalam_bulan = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($tanggal_mulai)), date('Y', strtotime($tanggal_mulai)));
        
        // Total biaya: harga dasar dianggap untuk 30 hari, jadi ambil rata-rata
        $total_biaya_sewa = $harga_dasar; // Harga sudah untuk 1 bulan
        
        // Jatuh tempo: akhir bulan atau 30 hari kemudian
        $tanggal_jatuh_tempo = date('Y-m-d', strtotime($tanggal_mulai . ' +1 month'));

        $tagihan = new Tagihan(
            null,
            $id_kontrak,
            $total_biaya_sewa,
            $biaya_tambahan,
            $tanggal_jatuh_tempo,
            'Belum Lunas',
            'Bulanan'
        );

        return $tagihan;
    }

    /**
     * Buat Tagihan Tahunan
     * Jatuh tempo: setahun dari tanggal mulai
     * Total biaya: harga_dasar * 12 bulan
     */
    private static function createTagihanTahunan($id_kontrak, $harga_dasar, $biaya_tambahan, $tanggal_mulai)
    {
        // Asumsi harga_dasar adalah harga per bulan
        // Untuk tahunan, kalikan dengan 12
        $total_biaya_sewa = $harga_dasar * 12;
        
        // Jatuh tempo: setahun kemudian
        $tanggal_jatuh_tempo = date('Y-m-d', strtotime($tanggal_mulai . ' +1 year'));

        $tagihan = new Tagihan(
            null,
            $id_kontrak,
            $total_biaya_sewa,
            $biaya_tambahan,
            $tanggal_jatuh_tempo,
            'Belum Lunas',
            'Tahunan'
        );

        return $tagihan;
    }

    /**
     * Generate ID Tagihan
     * Format: T-YYMMDD000
     * 
     * @param int $index Index untuk sequence
     * @return string
     */
    public static function generateTagihanId($index = 0)
    {
        $prefix = 'T-' . date('ymd');
        $sequence = str_pad($index, 3, '0', STR_PAD_LEFT);
        return $prefix . $sequence;
    }

    /**
     * Generate ID Pembayaran
     * Format: P-YYMMDD000
     * 
     * @param int $index Index untuk sequence
     * @return string
     */
    public static function generatePembayaranId($index = 0)
    {
        $prefix = 'P-' . date('ymd');
        $sequence = str_pad($index, 3, '0', STR_PAD_LEFT);
        return $prefix . $sequence;
    }

    /**
     * Hitung biaya berdasarkan jumlah hari dan tipe sewa
     * Digunakan untuk perincian biaya
     * 
     * @param string $tipe_sewa
     * @param float $harga_dasar
     * @param int $jumlah_hari
     * @return array
     */
    public static function hitungBiaya($tipe_sewa, $harga_dasar, $jumlah_hari = null)
    {
        $result = [
            'tipe_sewa' => $tipe_sewa,
            'harga_dasar' => $harga_dasar,
            'deskripsi' => '',
            'total' => 0
        ];

        switch (strtolower($tipe_sewa)) {
            case 'harian':
                $result['deskripsi'] = '1 hari sewa';
                $result['total'] = $harga_dasar;
                break;
            
            case 'bulanan':
                $result['deskripsi'] = '1 bulan sewa';
                $result['total'] = $harga_dasar;
                break;
            
            case 'tahunan':
                $result['deskripsi'] = '1 tahun sewa';
                $result['total'] = $harga_dasar * 12;
                break;
        }

        return $result;
    }
}
?>
