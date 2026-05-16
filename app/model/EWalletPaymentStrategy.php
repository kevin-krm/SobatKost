<?php
/**
 * E-Wallet Payment Strategy
 * Implementasi pembayaran via E-Wallet
 */
class EWalletPaymentStrategy implements PaymentStrategy
{
    private $supportedWallets = ['GCash', 'PayMaya', 'OVO', 'Dana', 'LinkAja'];

    public function process($amount, $data = [])
    {
        // Validasi dulu
        $validation = $this->validate($data);
        if (!$validation['valid']) {
            return ['success' => false, 'message' => 'Data invalid: ' . implode(', ', $validation['errors'])];
        }

        // Simulasi proses e-wallet
        $result = [
            'success' => true,
            'message' => 'Pembayaran E-Wallet sebesar Rp ' . number_format($amount, 0, ',', '.') . ' berhasil diproses',
            'e_wallet' => $data['jenis_ewallet'],
            'nomor_akun' => $data['nomor_akun'],
            'nominal' => $amount,
            'timestamp' => date('Y-m-d H:i:s'),
            'transaction_id' => 'TXN-' . strtoupper(bin2hex(random_bytes(8)))
        ];

        return $result;
    }

    public function validate($data = [])
    {
        $errors = [];

        if (empty($data['jenis_ewallet'])) {
            $errors[] = 'Jenis E-Wallet wajib dipilih';
        } elseif (!in_array($data['jenis_ewallet'], $this->supportedWallets)) {
            $errors[] = 'E-Wallet tidak didukung. Pilih: ' . implode(', ', $this->supportedWallets);
        }

        if (empty($data['nomor_akun'])) {
            $errors[] = 'Nomor akun E-Wallet wajib diisi';
        } elseif (!preg_match('/^\d{10,15}$/', $data['nomor_akun'])) {
            $errors[] = 'Nomor akun E-Wallet tidak valid (10-15 digit)';
        }

        if (empty($data['nama_pemilik'])) {
            $errors[] = 'Nama pemilik akun wajib diisi';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    public function getMethodName()
    {
        return 'E-Wallet';
    }

    public function getDescription()
    {
        return 'Pembayaran melalui E-Wallet digital';
    }

    public function getRequiredFields()
    {
        return [
            'jenis_ewallet' => 'Jenis E-Wallet',
            'nomor_akun' => 'Nomor Akun',
            'nama_pemilik' => 'Nama Pemilik'
        ];
    }

    public function getSupportedWallets()
    {
        return $this->supportedWallets;
    }
}
?>
