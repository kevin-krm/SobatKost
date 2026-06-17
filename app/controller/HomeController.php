<?php
require_once APP_PATH . '/middleware/AuthMiddleware.php';
require_once APP_PATH . '/dao/PenggunaDao.php';
require_once APP_PATH . '/dao/KomplainDao.php';
require_once APP_PATH . '/dao/TagihanDao.php';
require_once APP_PATH . '/dao/KamarDao.php';
require_once APP_PATH . '/dao/BiayaOperasionalDao.php';
require_once APP_PATH . '/dao/PembayaranDao.php';

class HomeController {
    private $penggunaDao;
    private $komplainDao;
    private $tagihanDao;
    private $kamarDao;
    private $biayaOperasionalDao;
    private $pembayaranDao;

    public function __construct() {
        AuthMiddleware::check();
        $this->penggunaDao = new PenggunaDao();
        $this->komplainDao = new KomplainDao();
        $this->tagihanDao = new TagihanDao();
        $this->kamarDao = new KamarDao();
        $this->biayaOperasionalDao = new BiayaOperasionalDao();
        $this->pembayaranDao = new PembayaranDao();
    }

    private function checkAccess() {
        if (!Auth::isOwner() && !Auth::isPenjaga()) {
            return false;
        }
        return true;
    }

    /**
     * Mengatur tampilan antarmuka halaman Beranda (Dashboard), meringkas data vital seperti ketersediaan kamar dan total profit.
     */
    public function index() {
        if (!$this->checkAccess()) {
            header('Location: /SobatKost/user');
            exit;
        }

        // 1. Fetch statistics for KPI Cards (Current Month for Revenue, Unpaid Bills, and Expenses)
        $totalPenyewa = $this->penggunaDao->countPenyewaAktif();
        $roomStatus = $this->kamarDao->countRoomsByStatus();
        $totalRooms = array_sum($roomStatus);
        $occupiedRooms = $roomStatus['Terisi'] ?? 0;
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0;
        
        // Month specific KPIs
        $totalRevenue = $this->pembayaranDao->getRevenueCurrentMonth();
        $unpaidBills = $this->tagihanDao->countTagihanBelumLunasCurrentMonth();
        $totalExpenses = $this->biayaOperasionalDao->getTotalBiayaCurrentMonth();
        
        $komplainStatus = $this->komplainDao->countKomplainByStatus();
        $pendingComplaints = ($komplainStatus['Menunggu'] ?? 0) + ($komplainStatus['Diproses'] ?? 0);

        // 2. Fetch data for charts
        $occupancyChart = $roomStatus;

        // Get Available Years for Line Chart Year Selector
        $availableYears = $this->pembayaranDao->getAvailableYears();
        $defaultYear = !empty($availableYears) ? $availableYears[0] : date('Y');

        // Revenue Chart (For default Year)
        $revenueRaw = $this->pembayaranDao->getRevenueByMonthForYear($defaultYear);
        $indonesianMonths = [
            '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun',
            '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
        ];
        $revenueLabels = [];
        $revenueData = [];
        foreach ($revenueRaw as $month => $total) {
            $revenueLabels[] = $indonesianMonths[$month] . ' ' . $defaultYear;
            $revenueData[] = $total;
        }
        $revenueChart = [
            'labels' => $revenueLabels,
            'data' => $revenueData
        ];

        // Complaints Chart
        $komplainChart = $komplainStatus;

        // Operating Expenses Chart (Initially all-time)
        $biayaRaw = $this->biayaOperasionalDao->getTotalByKategori();
        $biayaChart = [
            'Listrik' => 0.0,
            'Air' => 0.0,
            'Kebersihan' => 0.0,
            'Gaji Karyawan' => 0.0,
            'Perbaikan' => 0.0,
            'Lainnya' => 0.0
        ];
        foreach ($biayaRaw as $cat => $total) {
            $biayaChart[$cat] = $total;
        }

        // Payments Verification Chart (Initially all-time)
        $pembayaranChart = $this->pembayaranDao->countByVerificationStatus();

        // Construct response
        $dashboardData = [
            'kpiCards' => [
                'totalPenyewa' => $totalPenyewa,
                'occupancyRate' => $occupancyRate,
                'totalRevenue' => $totalRevenue,
                'pendingComplaints' => $pendingComplaints,
                'unpaidBills' => $unpaidBills,
                'totalExpenses' => $totalExpenses
            ],
            'availableYears' => $availableYears,
            'occupancyChart' => $occupancyChart,
            'revenueChart' => $revenueChart,
            'komplainChart' => $komplainChart,
            'biayaChart' => $biayaChart,
            'pembayaranChart' => $pembayaranChart
        ];

        // Encode as JSON for JS consumption
        $dashboardJsonVar = json_encode($dashboardData);
        $contentView = APP_PATH . '/view/layout/dashboard_home.php';
        
        require_once APP_PATH . '/view/index.php';
    }

    // AJAX Endpoint: Get Revenue Data for Year
    /**
     * Menyediakan format data metrik pendapatan bulanan untuk dirender menjadi representasi grafik statistik.
     */
    public function getRevenueData() {
        if (!$this->checkAccess()) {
            http_response_code(403);
            exit('403 Forbidden');
        }

        $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');
        $data = $this->pembayaranDao->getRevenueByMonthForYear($year);
        
        $indonesianMonths = [
            '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun',
            '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
        ];
        
        $labels = [];
        $values = [];
        foreach ($data as $month => $total) {
            $labels[] = $indonesianMonths[$month] . ' ' . $year;
            $values[] = $total;
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'labels' => $labels,
            'data' => $values
        ]);
        exit;
    }

    // AJAX Endpoint: Get Payments Verification Data
    /**
     * Menyediakan format data status pembayaran untuk keperluan visualisasi grafik proporsi.
     */
    public function getPembayaranData() {
        if (!$this->checkAccess()) {
            http_response_code(403);
            exit('403 Forbidden');
        }

        $filterType = $_GET['filter_type'] ?? 'all';
        $year = isset($_GET['year']) && $_GET['year'] !== '' ? (int)$_GET['year'] : null;
        $month = isset($_GET['month']) && $_GET['month'] !== '' ? (int)$_GET['month'] : null;
        
        $data = $this->pembayaranDao->countByVerificationStatusFiltered($filterType, $year, $month);
        
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // AJAX Endpoint: Get Operational Expenses Data
    /**
     * Menyediakan format data persebaran biaya operasional dalam bentuk grafik visualisasi.
     */
    public function getBiayaData() {
        if (!$this->checkAccess()) {
            http_response_code(403);
            exit('403 Forbidden');
        }

        $filterType = $_GET['filter_type'] ?? 'all';
        $year = isset($_GET['year']) && $_GET['year'] !== '' ? (int)$_GET['year'] : null;
        $month = isset($_GET['month']) && $_GET['month'] !== '' ? (int)$_GET['month'] : null;
        
        $data = $this->biayaOperasionalDao->getTotalByKategoriFiltered($filterType, $year, $month);
        
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
?>