<?php
/**
 * Cash Payment Strategy
 * Implementasi pembayaran via Tunai
 */
class CashPaymentStrategy implements PaymentStrategy
{
    public function process($amount, $data = [])
    {
        // Validasi dulu
        $validation = $this->validate($data);
        if (!$validation['valid']) {
            return ['success' => false, 'message' => 'Data invalid: ' . implode(', ', $validation['errors'])];
        }

        // Simulasi proses pembayaran tunai
        $result = [
            'success' => true,
            'message' => 'Pembayaran tunai sebesar Rp ' . number_format($amount, 0, ',', '.') . ' berhasil direkam',
            'nominal' => $amount,
            'lokasi' => $data['lokasi_pembayaran'] ?? 'Kantor Kost',
            'penerima' => $data['nama_penerima'] ?? 'Admin',
            'timestamp' => date('Y-m-d H:i:s'),
            'bukti_kirim' => 'CASH-' . strtoupper(bin2hex(random_bytes(6)))
        ];

        return $result;
    }

    public function validate($data = [])
    {
        $errors = [];

        if (empty($data['nama_penerima'])) {
            $errors[] = 'Nama penerima wajib diisi';
        }

        if (empty($data['lokasi_pembayaran'])) {
            $errors[] = 'Lokasi pembayaran wajib diisi';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    public function getMethodName()
    {
        return 'Tunai';
    }

    public function getDescription()
    {
        return 'Pembayaran tunai langsung ke kantor kost';
    }

    public function getRequiredFields()
    {
        return [
            'nama_penerima' => 'Nama Penerima',
            'lokasi_pembayaran' => 'Lokasi Pembayaran'
        ];
    }
}
?>
