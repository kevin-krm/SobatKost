<?php
require_once APP_PATH . '/view/layout/header.php';
require_once APP_PATH . '/view/layout/sidebar.php';

$kpis = $dashboardData['kpiCards'];
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h2 class="fw-bold mb-1 text-slate" style="color: #1E293B;">Dashboard</h2>
        <p class="text-muted mb-0">Selamat datang kembali, <strong style="color: #4F46E5;"><?= htmlspecialchars($_SESSION['user']['nama'] ?? 'Admin') ?></strong>!
    </div>
    <div class="col-md-6 text-md-end mt-2 mt-md-0">
        <span class="badge bg-white text-dark border p-2 shadow-sm rounded-pill">
            <i class="bi bi-calendar3 me-2 text-indigo"></i><?= date('d M Y') ?>
        </span>
    </div>
</div>

<!-- KPI Cards Section -->
<div class="row g-3 mb-4">
    <!-- Card 1: Penyewa Aktif -->
    <div class="col-12 col-sm-6 col-md-4 col-xl">
        <div class="card dashboard-kpi-card h-100 border-0 shadow-sm kpi-indigo">
            <div class="card-body d-flex align-items-center">
                <div class="kpi-icon-wrapper me-3">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <h6 class="kpi-label text-muted mb-1">Penyewa Aktif</h6>
                    <h3 class="kpi-value mb-0 fw-bold"><?= number_format($kpis['totalPenyewa'], 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
    </div>
    <!-- Card 2: Komplain Pending -->
    <div class="col-12 col-sm-6 col-md-4 col-xl">
        <div class="card dashboard-kpi-card h-100 border-0 shadow-sm kpi-rose">
            <div class="card-body d-flex align-items-center">
                <div class="kpi-icon-wrapper me-3">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <h6 class="kpi-label text-muted mb-1">Komplain Pending</h6>
                    <h3 class="kpi-value mb-0 fw-bold"><?= number_format($kpis['pendingComplaints'], 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
    </div>
    <!-- Card 3: Belum Dibayar Bulan Ini -->
    <div class="col-12 col-sm-6 col-md-4 col-xl">
        <div class="card dashboard-kpi-card h-100 border-0 shadow-sm kpi-orange">
            <div class="card-body d-flex align-items-center">
                <div class="kpi-icon-wrapper me-3">
                    <i class="bi bi-receipt-cutoff"></i>
                </div>
                <div>
                    <h6 class="kpi-label text-muted mb-1">Belum Dibayar <?= date('F Y') ?></h6>
                    <h3 class="kpi-value mb-0 fw-bold"><?= number_format($kpis['unpaidBills'], 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
    </div>
    <!-- Card 4: Pendapatan Bulan Ini -->
    <div class="col-12 col-sm-6 col-md-4 col-xl">
        <div class="card dashboard-kpi-card h-100 border-0 shadow-sm kpi-amber">
            <div class="card-body d-flex align-items-center">
                <div class="kpi-icon-wrapper me-3">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div>
                    <h6 class="kpi-label text-muted mb-1">Pendapatan <?= date('F Y') ?></h6>
                    <h3 class="kpi-value mb-0 fw-bold">Rp <?= number_format($kpis['totalRevenue'], 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
    </div>
    <!-- Card 5: Pengeluaran Bulan Ini -->
    <div class="col-12 col-sm-6 col-md-4 col-xl">
        <div class="card dashboard-kpi-card h-100 border-0 shadow-sm kpi-slate">
            <div class="card-body d-flex align-items-center">
                <div class="kpi-icon-wrapper me-3">
                    <i class="bi bi-graph-down-arrow"></i>
                </div>
                <div>
                    <h6 class="kpi-label text-muted mb-1">Pengeluaran <?= date('F Y') ?></h6>
                    <h3 class="kpi-value mb-0 fw-bold">Rp <?= number_format($kpis['totalExpenses'], 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section Row 1 -->
<div class="row mb-4">
    <!-- Occupancy Rate (Doughnut) -->
    <div class="col-12 col-lg-5 mb-4 mb-lg-0">
        <div class="card dashboard-chart-card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title fw-bold mb-3 text-slate">
                    <i class="bi bi-pie-chart-fill me-2 text-indigo"></i>Status Ketersediaan Kamar
                </h5>
                <div class="chart-container" style="position: relative; height: 280px; width: 100%;">
                    <canvas id="occupancyChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Revenue (Line) -->
    <div class="col-12 col-lg-7">
        <div class="card dashboard-chart-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title fw-bold mb-0 text-slate">
                        <i class="bi bi-graph-up-arrow me-2 text-indigo"></i>Pendapatan Bulanan
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <label for="revenueYearSelect" class="form-label mb-0 small text-muted">Tahun:</label>
                        <select id="revenueYearSelect" class="form-select form-select-sm" style="width: 100px;">
                            <?php foreach ($dashboardData['availableYears'] as $tahun): ?>
                                <option value="<?= $tahun ?>"><?= $tahun ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="chart-container" style="position: relative; height: 280px; width: 100%;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section Row 2 -->
<div class="row mb-4">
    <!-- Complaints Horizontal Bar -->
    <div class="col-12 col-lg-5 mb-4 mb-lg-0">
        <div class="card dashboard-chart-card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title fw-bold mb-3 text-slate">
                    <i class="bi bi-tools me-2 text-indigo"></i>Status Tiket Komplain
                </h5>
                <div class="chart-container" style="position: relative; height: 280px; width: 100%;">
                    <canvas id="complaintChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Operating Expenses Polar Area -->
    <div class="col-12 col-lg-7">
        <div class="card dashboard-chart-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                    <h5 class="card-title fw-bold mb-0 text-slate">
                        <i class="bi bi-wallet2 me-2 text-indigo"></i>Distribusi Biaya Operasional
                    </h5>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <select id="biayaFilterType" class="form-select form-select-sm" style="width: 120px;">
                            <option value="all">Keseluruhan</option>
                            <option value="year">Per Tahun</option>
                            <option value="month">Per Bulan</option>
                        </select>
                        
                        <select id="biayaYearSelect" class="form-select form-select-sm d-none" style="width: 90px;">
                            <?php foreach ($dashboardData['availableYears'] as $tahun): ?>
                                <option value="<?= $tahun ?>"><?= $tahun ?></option>
                            <?php endforeach; ?>
                        </select>
                        
                        <select id="biayaMonthSelect" class="form-select form-select-sm d-none" style="width: 100px;">
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                            <option value="7">Juli</option>
                            <option value="8">Agustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>
                </div>
                <div class="chart-container" style="position: relative; height: 280px; width: 100%;">
                    <canvas id="biayaChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section Row 3 -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card dashboard-chart-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                    <h5 class="card-title fw-bold mb-0 text-slate">
                        <i class="bi bi-credit-card-2-back-fill me-2 text-indigo"></i>Status Verifikasi Pembayaran
                    </h5>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <select id="pembayaranFilterType" class="form-select form-select-sm" style="width: 120px;">
                            <option value="all">Keseluruhan</option>
                            <option value="year">Per Tahun</option>
                            <option value="month">Per Bulan</option>
                        </select>
                        
                        <select id="pembayaranYearSelect" class="form-select form-select-sm d-none" style="width: 90px;">
                            <?php foreach ($dashboardData['availableYears'] as $tahun): ?>
                                <option value="<?= $tahun ?>"><?= $tahun ?></option>
                            <?php endforeach; ?>
                        </select>
                        
                        <select id="pembayaranMonthSelect" class="form-select form-select-sm d-none" style="width: 100px;">
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                            <option value="7">Juli</option>
                            <option value="8">Agustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <div class="chart-container" style="position: relative; height: 260px; width: 100%;">
                            <canvas id="pembayaranChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <h6 class="fw-bold mb-3 text-slate"><i class="bi bi-info-circle-fill me-2 text-indigo"></i>Ringkasan Verifikasi</h6>
                            <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                                <li class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                                    <span><span class="badge bg-warning text-dark me-2" style="width: 80px;">Proses</span> Menunggu Verifikasi</span>
                                    <span class="fw-bold text-dark fs-5" id="pembayaranCountProses"><?= $dashboardData['pembayaranChart']['Proses'] ?? 0 ?></span>
                                </li>
                                <li class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                                    <span><span class="badge bg-success text-white me-2" style="width: 80px;">Berhasil</span> Pembayaran Valid</span>
                                    <span class="fw-bold text-dark fs-5" id="pembayaranCountBerhasil"><?= $dashboardData['pembayaranChart']['Berhasil'] ?? 0 ?></span>
                                </li>
                                <li class="d-flex justify-content-between align-items-center">
                                    <span><span class="badge bg-danger text-white me-2" style="width: 80px;">Ditolak</span> Ditolak / Tidak Valid</span>
                                    <span class="fw-bold text-dark fs-5" id="pembayaranCountDitolak"><?= $dashboardData['pembayaranChart']['Ditolak'] ?? 0 ?></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once APP_PATH . '/view/layout/footer.php';
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= BASE_URL ?>/public/js/dashboard.js"></script>
<script>
    const dashboardData = <?= $dashboardJsonVar ?>;
    
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof initDashboard === "function") {
            initDashboard(dashboardData);
        }
    });
</script>
