<?php
/**
 * Transfer Bank Payment Strategy
 * Implementasi pembayaran via transfer bank
 */
class TransferPaymentStrategy implements PaymentStrategy
{
    public function process($amount, $data = [])
    {
        // Validasi dulu
        $validation = $this->validate($data);
        if (!$validation['valid']) {
            return ['success' => false, 'message' => 'Data invalid: ' . implode(', ', $validation['errors'])];
        }

        // Simulasi proses transfer
        // Dalam praktik nyata, bisa terintegrasi dengan gateway bank
        $result = [
            'success' => true,
            'message' => 'Transfer sebesar Rp ' . number_format($amount, 0, ',', '.') . ' berhasil diproses',
            'bank' => $data['bank_tujuan'],
            'rekening' => $data['no_rekening'],
            'nominal' => $amount,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        return $result;
    }

    public function validate($data = [])
    {
        $errors = [];

        if (empty($data['bank_tujuan'])) {
            $errors[] = 'Bank tujuan wajib diisi';
        }

        if (empty($data['no_rekening'])) {
            $errors[] = 'Nomor rekening wajib diisi';
        } elseif (!preg_match('/^\d{10,20}$/', $data['no_rekening'])) {
            $errors[] = 'Nomor rekening tidak valid (10-20 digit)';
        }

        if (empty($data['nama_pemilik'])) {
            $errors[] = 'Nama pemilik rekening wajib diisi';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    public function getMethodName()
    {
        return 'Transfer Bank';
    }

    public function getDescription()
    {
        return 'Pembayaran melalui transfer ke rekening bank';
    }

    public function getRequiredFields()
    {
        return [
            'bank_tujuan' => 'Nama Bank',
            'no_rekening' => 'Nomor Rekening',
            'nama_pemilik' => 'Nama Pemilik Rekening'
        ];
    }
}
?>
