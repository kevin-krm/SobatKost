<?php
require_once APP_PATH . '/dao/BiayaOperasionalDao.php';

class KeuanganController {
    public function index() {
        $dao = new BiayaOperasionalDao();

        $pagePengeluaran = isset($_GET['page_pengeluaran']) ? (int) $_GET['page_pengeluaran'] : 1;
        $pagePemasukan = isset($_GET['page_pemasukan']) ? (int) $_GET['page_pemasukan'] : 1;
        $pagePengeluaran = max(1, $pagePengeluaran);
        $pagePemasukan = max(1, $pagePemasukan);
        $limit = 10;
        $offsetPengeluaran = ($pagePengeluaran - 1) * $limit;
        $offsetPemasukan = ($pagePemasukan - 1) * $limit;

        $filterType = $_GET['filter_type'] ?? 'all';
        if (!in_array($filterType, ['all', 'year', 'month'])) {
            $filterType = 'all';
        }

        $bulanFilter = isset($_GET['bulan']) && preg_match('/^\d{4}-\d{2}$/', $_GET['bulan']) ? $_GET['bulan'] : date('Y-m');
        $tahunFilter = isset($_GET['tahun']) && preg_match('/^\d{4}$/', $_GET['tahun']) ? $_GET['tahun'] : date('Y');
        $filterValue = null;

        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        if ($filterType === 'month') {
            $filterValue = $bulanFilter;
            [$tahun, $bulan] = explode('-', $bulanFilter);
            $labelPeriode = ($namaBulan[$bulan] ?? $bulan) . ' ' . $tahun;
        } elseif ($filterType === 'year') {
            $filterValue = $tahunFilter;
            $labelPeriode = 'Tahun ' . $tahunFilter;
        } else {
            $labelPeriode = 'Keseluruhan';
        }

        $biayaList = $dao->getBiayaPage($limit, $offsetPengeluaran, $filterType, $filterValue);
        $totalData = $dao->countBiaya($filterType, $filterValue);
        $totalPagePengeluaran = ceil($totalData / $limit);
        $pemasukanList = $dao->getPemasukanPage($limit, $offsetPemasukan, $filterType, $filterValue);
        $totalPemasukanData = $dao->countPemasukan($filterType, $filterValue);
        $totalPagePemasukan = ceil($totalPemasukanData / $limit);

        $ringkasan = $dao->getRingkasanKeuangan($filterType, $filterValue);

        $contentView = APP_PATH . '/view/keuangan/index.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function store() {
        $kategori_biaya = $_POST['kategori_biaya'];
        $jumlah_biaya = $_POST['jumlah_biaya'];
        $keterangan = $_POST['keterangan'];

        $biaya = new BiayaOperasional(null, $kategori_biaya, $jumlah_biaya, null, $keterangan);
        $dao = new BiayaOperasionalDao();
        $dao->insertBiaya($biaya);

        header("Location: /SobatKost/index.php?url=keuangan");
        exit;
    }

    public function create() {
        $contentView = APP_PATH . '/view/keuangan/create.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function edit($id) {
        $dao = new BiayaOperasionalDao();
        $biaya = $dao->getBiayaById($id);

        if (!$biaya) {
            echo "<h3>Data biaya operasional tidak ditemukan</h3>";
            exit;
        }

        $contentView = APP_PATH . '/view/keuangan/edit.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function update($id) {
        $kategori_biaya = $_POST['kategori_biaya'];
        $jumlah_biaya = $_POST['jumlah_biaya'];
        $tanggal_pengeluaran = $_POST['tanggal_pengeluaran'];
        $keterangan = $_POST['keterangan'];

        $biaya = new BiayaOperasional($id, $kategori_biaya, $jumlah_biaya, $tanggal_pengeluaran, $keterangan);
        $dao = new BiayaOperasionalDao();
        $dao->updateBiaya($biaya);

        header("Location: /SobatKost/index.php?url=keuangan");
        exit;
    }

    public function delete($id) {
        $dao = new BiayaOperasionalDao();
        $dao->deleteBiaya($id);
        header("Location: /SobatKost/index.php?url=keuangan");
        exit;
    }
}
?>
