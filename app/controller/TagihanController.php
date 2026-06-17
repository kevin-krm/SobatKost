<?php
require_once APP_PATH . '/dao/TagihanDao.php';
require_once APP_PATH . '/dao/KontrakDao.php';
require_once APP_PATH . '/dao/KamarDao.php';
require_once APP_PATH . '/model/TagihanFactory.php';
require_once APP_PATH . '/model/TagihanReminderService.php';
require_once APP_PATH . '/dao/PembayaranDao.php';

class TagihanController
{
    private $tagihanDao;

    public function __construct()
    {
        $this->tagihanDao = new TagihanDao();
    }

    /**
     * Tampilkan daftar tagihan (Admin)
     */
/**
     * Menampilkan tabel daftar seluruh tagihan sewa.
     * Relasi: Mengambil data dari fungsi getTagihanPage() di TagihanDao.php agar tampilannya dibagi per halaman (pagination).
     */
    public function index()
    {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $tagihanList = $this->tagihanDao->getTagihanPage($limit, $offset);
        $totalData = $this->tagihanDao->countTagihan();
        $totalPage = ceil($totalData / $limit);

        $statistik = $this->tagihanDao->getStatistik();

        $contentView = APP_PATH . '/view/tagihan/index.php';
        require_once APP_PATH . '/view/index.php';
    }

    /**
     * Generate tagihan baru (Admin)
     */
/**
     * Menampilkan form halaman untuk mencetak tagihan baru.
     * Relasi: Menarik data penyewa yang sedang aktif dari getKontrakAktif() di KontrakDao.php untuk dipilih admin.
     */
    public function create()
    {
        $kontrakDao = new KontrakDao();
        $kontrakList = $kontrakDao->getKontrakAktif();

        $contentView = APP_PATH . '/view/tagihan/create.php';
        require_once APP_PATH . '/view/index.php';
    }

    /**
     * Store tagihan baru ke database
     */
/**
     * Menangkap data dari form buat tagihan lalu mengeksekusi penyimpanannya.
     * Relasi: Memanggil TagihanFactory.php untuk merakit total biaya otomatis, lalu disuruh simpan ke MySQL lewat insertTagihan() di TagihanDao.php.
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /SobatKost/index.php?url=tagihan");
            exit;
        }

        $id_kontrak = $_POST['id_kontrak'] ?? null;
        $biaya_tambahan = max(0, (float) ($_POST['biaya_tambahan'] ?? 0));

        if (!$id_kontrak) {
            $_SESSION['error'] = 'ID Kontrak tidak valid';
            header("Location: /SobatKost/index.php?url=tagihan/create");
            exit;
        }

        // Ambil data kontrak
        $kontrakDao = new KontrakDao();
        $kontrak = $kontrakDao->getKontrakById($id_kontrak);

        if (!$kontrak) {
            $_SESSION['error'] = 'Kontrak tidak ditemukan';
            header("Location: /SobatKost/index.php?url=tagihan/create");
            exit;
        }

        if ($kontrak->getHargaDasar() === null) {
            $_SESSION['error'] = 'Harga kamar pada kontrak tidak ditemukan';
            header("Location: /SobatKost/index.php?url=tagihan/create");
            exit;
        }

        // Total biaya sewa dihitung otomatis dari kontrak_sewa.tipe_sewa.
        $tagihan = TagihanFactory::createTagihan(
            $kontrak->getTipeSewa(),
            $id_kontrak,
            $kontrak->getHargaDasar(),
            $biaya_tambahan,
            $kontrak->getTanggalMulai()
        );

        // Insert tagihan
        $id_tagihan = $this->tagihanDao->insertTagihan($tagihan);

        $_SESSION['success'] = 'Tagihan berhasil dibuat dengan ID: ' . $id_tagihan;
        header("Location: /SobatKost/index.php?url=tagihan");
        exit;
    }

    /**
     * Detail tagihan (Admin & User)
     */
/**
     * Menampilkan halaman rincian satu tagihan spesifik.
     * Relasi: Menarik juga riwayat bayarnya lewat getPembayaranByTagihanId() di PembayaranDao.php.
     */
    public function detail($id_tagihan)
    {
        $tagihan = $this->tagihanDao->getTagihanById($id_tagihan);

        if (!$tagihan) {
            $_SESSION['error'] = 'Tagihan tidak ditemukan';
            header("Location: /SobatKost/index.php?url=tagihan");
            exit;
        }

        $pembayaranDao = new \PembayaranDao();
        $pembayaranList = $pembayaranDao->getPembayaranByTagihanId($id_tagihan);

        $contentView = APP_PATH . '/view/tagihan/detail.php';
        require_once APP_PATH . '/view/index.php';
    }

    /**
     * Edit tagihan (Admin)
     */
/**
     * Menampilkan form edit untuk merevisi tagihan yang salah ketik (contoh: ubah biaya tambahan atau tanggal jatuh tempo).
     */
    public function edit($id_tagihan)
    {
        $tagihan = $this->tagihanDao->getTagihanById($id_tagihan);

        if (!$tagihan) {
            $_SESSION['error'] = 'Tagihan tidak ditemukan';
            header("Location: /SobatKost/index.php?url=tagihan");
            exit;
        }

        $contentView = APP_PATH . '/view/tagihan/edit.php';
        require_once APP_PATH . '/view/index.php';
    }

    /**
     * Update tagihan
     */
/**
     * Menangkap data perubahan dari form edit tagihan, lalu menyimpannya ulang ke database via updateTagihan() di TagihanDao.php.
     */
    public function update($id_tagihan)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /SobatKost/index.php?url=tagihan/edit&id=" . $id_tagihan);
            exit;
        }

        $tagihan = $this->tagihanDao->getTagihanById($id_tagihan);

        if (!$tagihan) {
            $_SESSION['error'] = 'Tagihan tidak ditemukan';
            header("Location: /SobatKost/index.php?url=tagihan");
            exit;
        }

        $biaya_tambahan = $_POST['biaya_tambahan'] ?? $tagihan->getBiayaTambahan();
        $status = $_POST['status_tagihan'] ?? $tagihan->getStatusTagihan();
        $tanggal_jatuh_tempo = $_POST['tanggal_jatuh_tempo'] ?? $tagihan->getTanggalJatuhTempo();

        $tagihan->setBiayaTambahan($biaya_tambahan);
        $tagihan->setStatusTagihan($status);
        $tagihan->setTanggalJatuhTempo($tanggal_jatuh_tempo);

        $this->tagihanDao->updateTagihan($tagihan);

        $_SESSION['success'] = 'Tagihan berhasil diperbarui';
        header("Location: /SobatKost/index.php?url=tagihan/detail&id=" . $id_tagihan);
        exit;
    }

    /**
     * Delete tagihan
     */
/**
     * Menghapus tagihan dari sistem.
     * Logika ketat: Sistem memblokir penghapusan tagihan jika sudah ada uang pembayaran yang masuk (nyambung ke PembayaranDao.php).
     */
    public function delete($id_tagihan)
    {
        $tagihan = $this->tagihanDao->getTagihanById($id_tagihan);

        if (!$tagihan) {
            $_SESSION['error'] = 'Tagihan tidak ditemukan';
            header("Location: /SobatKost/index.php?url=tagihan");
            exit;
        }

        // Cek apakah sudah ada pembayaran
        $pembayaranDao = new \PembayaranDao();
        $pembayaranList = $pembayaranDao->getPembayaranByTagihanId($id_tagihan);

        if (!empty($pembayaranList)) {
            $_SESSION['error'] = 'Tidak dapat menghapus tagihan yang sudah memiliki pembayaran';
            header("Location: /SobatKost/index.php?url=tagihan/detail&id=" . $id_tagihan);
            exit;
        }

        $this->tagihanDao->deleteTagihan($id_tagihan);

        $_SESSION['success'] = 'Tagihan berhasil dihapus';
        header("Location: /SobatKost/index.php?url=tagihan");
        exit;
    }

    /**
     * Dashboard / Ringkasan tagihan
     */
/**
     * Menampilkan widget ringkasan tagihan di halaman awal (seperti total tagihan belum lunas, tagihan yang nunggak/overdue).
     */
    public function dashboard()
    {
        $statistik = $this->tagihanDao->getStatistik();
        $tagihanBelumLunas = $this->tagihanDao->countTagihanBelumLunas();
        $tagihanOverdue = $this->tagihanDao->countTagihanOverdue();
        $tagihanJatuhTempo = $this->tagihanDao->getTagihanJatuhTempoDalamHari(7);

        $contentView = APP_PATH . '/view/tagihan/dashboard.php';
        require_once APP_PATH . '/view/index.php';
    }

    /**
     * Kirim reminder jatuh tempo ke penyewa melalui adapter notifikasi.
     */
/**
     * Mengeksekusi penagihan otomatis.
     * Relasi: Memanggil class TagihanReminderService.php (Adapter Pattern) untuk mengirim notifikasi peringatan ke penyewa yang hampir jatuh tempo.
     */
    public function sendReminders()
    {
        $tagihanJatuhTempo = $this->tagihanDao->getTagihanJatuhTempoDalamHari(7);
        $reminderService = new TagihanReminderService();
        $totalTerkirim = $reminderService->kirimReminder($tagihanJatuhTempo);

        $_SESSION['success'] = "Reminder jatuh tempo berhasil dikirim untuk {$totalTerkirim} tagihan.";
        header("Location: /SobatKost/index.php?url=tagihan/dashboard");
        exit;
    }
}
?>
