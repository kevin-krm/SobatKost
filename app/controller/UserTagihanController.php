<?php
require_once APP_PATH . '/dao/TagihanDao.php';
require_once APP_PATH . '/dao/PembayaranDao.php';
require_once APP_PATH . '/dao/KontraKDao.php';

class UserTagihanController
{
    private $tagihanDao;
    private $pembayaranDao;

    public function __construct()
    {
        $this->tagihanDao = new TagihanDao();
        $this->pembayaranDao = new PembayaranDao();
    }

    /**
     * Tampilkan daftar tagihan untuk penyewa
     */
    public function index()
    {
        $id_pengguna = $_SESSION['user']['id'];
        
        // Get kontrak penyewa
        $kontraKDao = new KontraKDao();
        $kontrakList = $kontraKDao->getKontrakByPengguna($id_pengguna);
        
        // Ambil semua tagihan dari kontrak penyewa ini
        $tagihanList = [];
        foreach ($kontrakList as $kontrak) {
            $tagihan_kontrak = $this->tagihanDao->getTagihanByKontrakId($kontrak->getIdKontrak());
            $tagihanList = array_merge($tagihanList, $tagihan_kontrak);
        }

        // Sort by created_at descending
        usort($tagihanList, function($a, $b) {
            return strtotime($b->getCreatedAt()) - strtotime($a->getCreatedAt());
        });

        $statistik = [
            'total_tagihan' => count($tagihanList),
            'total_belum_lunas' => count(array_filter($tagihanList, fn($t) => $t->getStatusTagihan() === 'Belum Lunas')),
            'total_lunas' => count(array_filter($tagihanList, fn($t) => $t->getStatusTagihan() === 'Lunas')),
            'total_overdue' => count(array_filter($tagihanList, fn($t) => $t->isOverdue())),
            'total_terhutang' => array_sum(array_map(fn($t) => $t->getStatusTagihan() === 'Belum Lunas' ? $t->getTotalTagihan() : 0, $tagihanList))
        ];

        $contentView = APP_PATH . '/view/user/tagihan/index.php';
        require_once APP_PATH . '/view/user/index.php';
    }

    /**
     * Detail tagihan untuk penyewa
     */
    public function detail($id_tagihan)
    {
        $tagihan = $this->tagihanDao->getTagihanById($id_tagihan);

        if (!$tagihan) {
            $_SESSION['error'] = 'Tagihan tidak ditemukan';
            header("Location: /SobatKost/index.php?url=user/tagihan");
            exit;
        }

        // Cek apakah tagihan milik penyewa
        $contr = new KontraKDao();
        $kontrak = $contr->getKontrakById($tagihan->getIdKontrak());
        
        if ($kontrak->getIdPengguna() !== $_SESSION['user']['id']) {
            $_SESSION['error'] = 'Anda tidak memiliki akses ke tagihan ini';
            header("Location: /SobatKost/index.php?url=user/tagihan");
            exit;
        }

        $pembayaranList = $this->pembayaranDao->getPembayaranByTagihanId($id_tagihan);

        $contentView = APP_PATH . '/view/user/tagihan/detail.php';
        require_once APP_PATH . '/view/user/index.php';
    }
}
?>
