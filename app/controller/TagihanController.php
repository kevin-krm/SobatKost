<?php
require_once APP_PATH . '/dao/TagihanDao.php';
require_once APP_PATH . '/dao/KontraKDao.php';
require_once APP_PATH . '/dao/KamarDao.php';
require_once APP_PATH . '/model/TagihanFactory.php';

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
    public function create()
    {
        $kontraKDao = new KontraKDao();
        $kontrakList = $kontraKDao->getKontrakAktif();

        $contentView = APP_PATH . '/view/tagihan/create.php';
        require_once APP_PATH . '/view/index.php';
    }

    /**
     * Store tagihan baru ke database
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /SobatKost/index.php?url=tagihan");
            exit;
        }

        $id_kontrak = $_POST['id_kontrak'] ?? null;
        $biaya_tambahan = $_POST['biaya_tambahan'] ?? 0;

        if (!$id_kontrak) {
            $_SESSION['error'] = 'ID Kontrak tidak valid';
            header("Location: /SobatKost/index.php?url=tagihan/create");
            exit;
        }

        // Ambil data kontrak
        $kontraKDao = new \KontraKDao();
        $kontrak = $kontraKDao->getKontrakById($id_kontrak);

        if (!$kontrak) {
            $_SESSION['error'] = 'Kontrak tidak ditemukan';
            header("Location: /SobatKost/index.php?url=tagihan/create");
            exit;
        }

        // Ambil harga kamar
        $kamarDao = new KamarDao();
        $kamar = $kamarDao->getKamarById($kontrak->getIdKamar());

        if (!$kamar) {
            $_SESSION['error'] = 'Data kamar tidak ditemukan';
            header("Location: /SobatKost/index.php?url=tagihan/create");
            exit;
        }

        // Gunakan Factory untuk membuat tagihan
        $tagihan = TagihanFactory::createTagihan(
            $kontrak->getTipeSewa(),
            $id_kontrak,
            $kamar->getHargaDasar(),
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
    public function dashboard()
    {
        $statistik = $this->tagihanDao->getStatistik();
        $tagihanBelumLunas = $this->tagihanDao->countTagihanBelumLunas();
        $tagihanOverdue = $this->tagihanDao->countTagihanOverdue();

        $contentView = APP_PATH . '/view/tagihan/dashboard.php';
        require_once APP_PATH . '/view/index.php';
    }
}
?>
