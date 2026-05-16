<?php
require_once APP_PATH . '/dao/PembayaranDao.php';
require_once APP_PATH . '/dao/TagihanDao.php';
require_once APP_PATH . '/model/TagihanFactory.php';
require_once APP_PATH . '/model/PaymentStrategy.php';
require_once APP_PATH . '/model/TransferPaymentStrategy.php';
require_once APP_PATH . '/model/EWalletPaymentStrategy.php';
require_once APP_PATH . '/model/CashPaymentStrategy.php';

class PembayaranController
{
    private $pembayaranDao;
    private $tagihanDao;

    public function __construct()
    {
        $this->pembayaranDao = new PembayaranDao();
        $this->tagihanDao = new TagihanDao();
    }

    /**
     * Tampilkan daftar pembayaran (Admin)
     */
    public function index()
    {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $status = $_GET['status'] ?? 'Proses';
        $pembayaranList = $this->pembayaranDao->getPembayaranByStatus($status, $limit, $offset);
        $totalData = $this->pembayaranDao->countPembayaranByStatus($status);
        $totalPage = ceil($totalData / $limit);

        $statistik = $this->pembayaranDao->getStatistik();

        $contentView = APP_PATH . '/view/pembayaran/index.php';
        require_once APP_PATH . '/view/index.php';
    }

    /**
     * Halaman upload bukti pembayaran (User)
     */
    public function upload()
    {
        $id_tagihan = $_GET['id'] ?? null;

        if (!$id_tagihan) {
            $_SESSION['error'] = 'ID Tagihan tidak valid';
            header("Location: /SobatKost/index.php?url=tagihan");
            exit;
        }

        $tagihan = $this->tagihanDao->getTagihanById($id_tagihan);

        if (!$tagihan) {
            $_SESSION['error'] = 'Tagihan tidak ditemukan';
            header("Location: /SobatKost/index.php?url=tagihan");
            exit;
        }

        if ($tagihan->getStatusTagihan() === 'Lunas') {
            $_SESSION['info'] = 'Tagihan ini sudah lunas';
            header("Location: /SobatKost/index.php?url=tagihan");
            exit;
        }

        // Data metode pembayaran
        $paymentMethods = [
            'Transfer' => new TransferPaymentStrategy(),
            'E-Wallet' => new EWalletPaymentStrategy(),
            'Tunai' => new CashPaymentStrategy()
        ];

        $contentView = APP_PATH . '/view/pembayaran/upload.php';
        require_once APP_PATH . '/view/index.php';
    }

    /**
     * Store pembayaran baru
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /SobatKost/index.php?url=pembayaran");
            exit;
        }

        $id_tagihan = $_POST['id_tagihan'] ?? null;
        $metode = $_POST['metode_pembayaran'] ?? null;

        if (!$id_tagihan || !$metode) {
            $_SESSION['error'] = 'Data pembayaran tidak lengkap';
            header("Location: /SobatKost/index.php?url=pembayaran/upload&id=" . $id_tagihan);
            exit;
        }

        $tagihan = $this->tagihanDao->getTagihanById($id_tagihan);

        if (!$tagihan) {
            $_SESSION['error'] = 'Tagihan tidak ditemukan';
            header("Location: /SobatKost/index.php?url=tagihan");
            exit;
        }

        // Pilih strategy pembayaran
        $strategy = $this->getPaymentStrategy($metode);

        if (!$strategy) {
            $_SESSION['error'] = 'Metode pembayaran tidak dikenali';
            header("Location: /SobatKost/index.php?url=pembayaran/upload&id=" . $id_tagihan);
            exit;
        }

        // Ambil data untuk validation
        $paymentData = $_POST;

        // Validasi data
        $validation = $strategy->validate($paymentData);

        if (!$validation['valid']) {
            $_SESSION['error'] = 'Validasi gagal: ' . implode(', ', $validation['errors']);
            header("Location: /SobatKost/index.php?url=pembayaran/upload&id=" . $id_tagihan);
            exit;
        }

        // Handle upload bukti pembayaran
        $bukti_pembayaran = null;

        if ($metode !== 'Tunai' && isset($_FILES['bukti_pembayaran'])) {
            $bukti_pembayaran = $this->uploadBukti($_FILES['bukti_pembayaran']);

            if (!$bukti_pembayaran) {
                $_SESSION['error'] = 'Gagal upload bukti pembayaran';
                header("Location: /SobatKost/index.php?url=pembayaran/upload&id=" . $id_tagihan);
                exit;
            }
        }

        // Buat objek Pembayaran
        $pembayaran = new Pembayaran(
            null,
            $id_tagihan,
            $metode,
            $bukti_pembayaran,
            date('Y-m-d H:i:s'),
            'Proses'
        );

        // Insert ke database
        $id_pembayaran = $this->pembayaranDao->insertPembayaran($pembayaran);

        $_SESSION['success'] = 'Pembayaran berhasil diupload. ID Pembayaran: ' . $id_pembayaran . '. Menunggu verifikasi admin.';
        header("Location: /SobatKost/index.php?url=pembayaran/detail&id=" . $id_pembayaran);
        exit;
    }

    /**
     * Detail pembayaran
     */
    public function detail($id_pembayaran)
    {
        $pembayaran = $this->pembayaranDao->getPembayaranById($id_pembayaran);

        if (!$pembayaran) {
            $_SESSION['error'] = 'Pembayaran tidak ditemukan';
            header("Location: /SobatKost/index.php?url=pembayaran");
            exit;
        }

        $tagihan = $this->tagihanDao->getTagihanById($pembayaran->getIdTagihan());

        $contentView = APP_PATH . '/view/pembayaran/detail.php';
        require_once APP_PATH . '/view/index.php';
    }

    /**
     * Verifikasi pembayaran (Admin)
     */
    public function verify($id_pembayaran)
    {
        $pembayaran = $this->pembayaranDao->getPembayaranById($id_pembayaran);

        if (!$pembayaran) {
            $_SESSION['error'] = 'Pembayaran tidak ditemukan';
            header("Location: /SobatKost/index.php?url=pembayaran");
            exit;
        }

        $status = $_GET['status'] ?? null;

        if (!in_array($status, ['Berhasil', 'Ditolak'])) {
            $_SESSION['error'] = 'Status verifikasi tidak valid';
            header("Location: /SobatKost/index.php?url=pembayaran/detail&id=" . $id_pembayaran);
            exit;
        }

        $this->pembayaranDao->updateStatusPembayaran($id_pembayaran, $status);

        if ($status === 'Berhasil') {
            $_SESSION['success'] = 'Pembayaran berhasil diverifikasi dan tagihan telah diubah menjadi Lunas';
        } else {
            $_SESSION['success'] = 'Pembayaran ditolak';
        }

        header("Location: /SobatKost/index.php?url=pembayaran");
        exit;
    }

    /**
     * Reject pembayaran dengan alasan
     */
    public function reject($id_pembayaran)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Method tidak valid';
            header("Location: /SobatKost/index.php?url=pembayaran/detail&id=" . $id_pembayaran);
            exit;
        }

        $pembayaran = $this->pembayaranDao->getPembayaranById($id_pembayaran);

        if (!$pembayaran) {
            $_SESSION['error'] = 'Pembayaran tidak ditemukan';
            header("Location: /SobatKost/index.php?url=pembayaran");
            exit;
        }

        $this->pembayaranDao->updateStatusPembayaran($id_pembayaran, 'Ditolak');

        $_SESSION['success'] = 'Pembayaran telah ditolak';
        header("Location: /SobatKost/index.php?url=pembayaran");
        exit;
    }

    /**
     * Get payment strategy berdasarkan tipe
     */
    private function getPaymentStrategy($metode)
    {
        $strategies = [
            'Transfer' => new TransferPaymentStrategy(),
            'E-Wallet' => new EWalletPaymentStrategy(),
            'Tunai' => new CashPaymentStrategy()
        ];

        return $strategies[$metode] ?? null;
    }

    /**
     * Upload bukti pembayaran
     */
    private function uploadBukti($file)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // Validasi tipe file
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        if (!in_array($file['type'], $allowedTypes)) {
            return null;
        }

        // Validasi ukuran (max 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            return null;
        }

        // Buat folder jika belum ada
        $uploadDir = PUBLIC_PATH . '/bukti_pembayaran';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate nama file
        $fileName = 'bukti_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $uploadPath = $uploadDir . '/' . $fileName;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return '/SobatKost/public/bukti_pembayaran/' . $fileName;
        }

        return null;
    }
}
?>
