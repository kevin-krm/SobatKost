<?php
/**
 * Menerapkan Strategy Pattern
 * Berfungsi memisahkan algoritma/logika berbagai metode pembayaran (Transfer, E-Wallet, Cash) agar berdiri sendiri. 
 * Jika ingin menambah metode baru, cukup buat class baru tanpa membongkar class lama.
 */
interface PaymentStrategy
{
    /**
     * Proses pembayaran
     * 
     * @param float $amount Jumlah pembayaran
     * @param array $data Data pembayaran (nomor rekening, nomor e-wallet, dll)
     * @return array Hasil proses dengan 'success' dan 'message'
     */
    public function process($amount, $data = []);

    /**
     * Validasi data pembayaran
     * 
     * @param array $data Data pembayaran
     * @return array Hasil validasi dengan 'valid' dan 'errors'
     */
    public function validate($data = []);

    /**
     * Get nama metode pembayaran
     * 
     * @return string
     */
    public function getMethodName();

    /**
     * Get deskripsi metode pembayaran
     * 
     * @return string
     */
    public function getDescription();

    /**
     * Get required fields untuk metode ini
     * 
     * @return array
     */
    public function getRequiredFields();
}
?>
