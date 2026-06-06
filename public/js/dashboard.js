/**
 * Dashboard Admin SobatKost
 * Handles Chart.js rendering, AJAX data updates, and visual adjustments
 */

// Global chart instances
let occupancyChartInstance = null;
let revenueChartInstance = null;
let complaintChartInstance = null;
let biayaChartInstance = null;
let pembayaranChartInstance = null;

// Utility function to format currency to Rupiah
function formatRupiah(amount) {
    return 'Rp ' + Number(amount).toLocaleString('id-ID', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });
}

function initDashboard(data) {
    if (!data) {
        console.error("No dashboard data provided!");
        return;
    }

    renderOccupancyChart(data.occupancyChart, data.kpiCards.occupancyRate);
    renderRevenueChart(data.revenueChart);
    renderKomplainChart(data.komplainChart);
    renderBiayaChart(data.biayaChart);
    renderPembayaranChart(data.pembayaranChart);
    
    // Setup event listeners for filtering
    setupFilters();
}

function renderOccupancyChart(chartData, rate) {
    const ctx = document.getElementById('occupancyChart');
    if (!ctx) return;

    const labels = ['Tersedia', 'Terisi', 'Perbaikan'];
    const values = [
        chartData['Tersedia'] || 0,
        chartData['Terisi'] || 0,
        chartData['Perbaikan'] || 0
    ];

    const centerTextPlugin = {
        id: 'centerText',
        afterDraw(chart) {
            const { ctx, chartArea: { top, bottom, left, right, width, height } } = chart;
            ctx.save();
            ctx.font = 'bold 28px "Segoe UI", sans-serif';
            ctx.fillStyle = '#4F46E5';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            const rateText = rate + '%';
            ctx.fillText(rateText, left + width / 2, top + height / 2 - 10);
            
            ctx.font = '600 12px "Segoe UI", sans-serif';
            ctx.fillStyle = '#64748B';
            ctx.fillText('TERISI', left + width / 2, top + height / 2 + 15);
            ctx.restore();
        }
    };

    occupancyChartInstance = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: [
                    '#10B981', // Tersedia: Emerald
                    '#4F46E5', // Terisi: Indigo
                    '#F59E0B'  // Perbaikan: Amber
                ],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 15,
                        font: {
                            family: '"Segoe UI", sans-serif',
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ` ${context.label}: ${context.raw} Kamar`;
                        }
                    }
                }
            }
        },
        plugins: [centerTextPlugin]
    });
}

function renderRevenueChart(chartData) {
    const canvas = document.getElementById('revenueChart');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');

    const gradient = ctx.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(79, 70, 229, 0.35)');
    gradient.addColorStop(1, 'rgba(79, 70, 229, 0.00)');

    revenueChartInstance = new Chart(canvas, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Pendapatan',
                data: chartData.data,
                borderColor: '#4F46E5',
                borderWidth: 3,
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#4F46E5',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ` Pendapatan: ${formatRupiah(context.raw)}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            family: '"Segoe UI", sans-serif'
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#E2E8F0'
                    },
                    ticks: {
                        callback: function(value) {
                            return formatRupiah(value);
                        },
                        font: {
                            family: '"Segoe UI", sans-serif'
                        }
                    }
                }
            }
        }
    });
}

function renderKomplainChart(chartData) {
    const ctx = document.getElementById('complaintChart');
    if (!ctx) return;

    const labels = ['Menunggu', 'Diproses', 'Selesai'];
    const values = [
        chartData['Menunggu'] || 0,
        chartData['Diproses'] || 0,
        chartData['Selesai'] || 0
    ];

    complaintChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: [
                    '#EF4444', // Menunggu: Red
                    '#3B82F6', // Diproses: Blue
                    '#10B981'  // Selesai: Emerald
                ],
                borderRadius: 6,
                borderWidth: 0,
                barThickness: 25
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ` ${context.label}: ${context.raw} Tiket`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            family: '"Segoe UI", sans-serif'
                        }
                    },
                    grid: {
                        color: '#E2E8F0'
                    }
                },
                y: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            family: '"Segoe UI", sans-serif',
                            weight: 'bold'
                        }
                    }
                }
            }
        }
    });
}

function renderBiayaChart(chartData) {
    const ctx = document.getElementById('biayaChart');
    if (!ctx) return;

    const categories = ['Listrik', 'Air', 'Kebersihan', 'Gaji Karyawan', 'Perbaikan', 'Lainnya'];
    const values = categories.map(cat => chartData[cat] || 0.0);

    biayaChartInstance = new Chart(ctx, {
        type: 'polarArea',
        data: {
            labels: categories,
            datasets: [{
                data: values,
                backgroundColor: [
                    'rgba(99, 102, 241, 0.75)',  // Indigo
                    'rgba(14, 165, 233, 0.75)',  // Sky Blue
                    'rgba(16, 185, 129, 0.75)',  // Emerald Green
                    'rgba(245, 158, 11, 0.75)',  // Amber Orange
                    'rgba(239, 68, 68, 0.75)',    // Red
                    'rgba(100, 116, 139, 0.75)'   // Slate Grey
                ],
                borderWidth: 1,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    ticks: {
                        display: false
                    },
                    grid: {
                        color: '#E2E8F0'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 12,
                        padding: 10,
                        font: {
                            family: '"Segoe UI", sans-serif',
                            size: 11
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ` ${context.label}: ${formatRupiah(context.raw)}`;
                        }
                    }
                }
            }
        }
    });
}

function renderPembayaranChart(chartData) {
    const ctx = document.getElementById('pembayaranChart');
    if (!ctx) return;

    const labels = ['Proses', 'Berhasil', 'Ditolak'];
    const values = [
        chartData['Proses'] || 0,
        chartData['Berhasil'] || 0,
        chartData['Ditolak'] || 0
    ];

    pembayaranChartInstance = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: [
                    '#F59E0B', // Proses: Amber
                    '#10B981', // Berhasil: Emerald
                    '#EF4444'  // Ditolak: Red
                ],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 15,
                        font: {
                            family: '"Segoe UI", sans-serif',
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const val = context.raw;
                            const percentage = total > 0 ? Math.round((val / total) * 100) : 0;
                            return ` ${context.label}: ${val} Pembayaran (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

// Helper function to toggle filter select dropdown visibility based on type
function toggleFilterSelectors(filterTypeSelect, yearSelect, monthSelect) {
    const filterType = filterTypeSelect.value;
    if (filterType === 'all') {
        yearSelect.classList.add('d-none');
        monthSelect.classList.add('d-none');
    } else if (filterType === 'year') {
        yearSelect.classList.remove('d-none');
        monthSelect.classList.add('d-none');
    } else if (filterType === 'month') {
        yearSelect.classList.remove('d-none');
        monthSelect.classList.remove('d-none');
    }
}

// AJAX update for Revenue Chart
function updateRevenueChart() {
    const year = document.getElementById('revenueYearSelect').value;
    const url = `/SobatKost/index.php?url=home/revenue&year=${year}`;
    
    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (revenueChartInstance) {
                revenueChartInstance.data.labels = data.labels;
                revenueChartInstance.data.datasets[0].data = data.data;
                revenueChartInstance.update();
            }
        })
        .catch(err => console.error("Error updating revenue chart:", err));
}

// AJAX update for Pembayaran Chart
function updatePembayaranChart() {
    const filterTypeSelect = document.getElementById('pembayaranFilterType');
    const yearSelect = document.getElementById('pembayaranYearSelect');
    const monthSelect = document.getElementById('pembayaranMonthSelect');
    
    toggleFilterSelectors(filterTypeSelect, yearSelect, monthSelect);
    
    const filterType = filterTypeSelect.value;
    const year = yearSelect.value;
    const month = monthSelect.value;
    
    let url = `/SobatKost/index.php?url=home/pembayaran&filter_type=${filterType}`;
    if (filterType === 'year' || filterType === 'month') {
        url += `&year=${year}`;
    }
    if (filterType === 'month') {
        url += `&month=${month}`;
    }
    
    fetch(url)
        .then(res => res.json())
        .then(data => {
            const values = [
                data['Proses'] || 0,
                data['Berhasil'] || 0,
                data['Ditolak'] || 0
            ];
            
            if (pembayaranChartInstance) {
                pembayaranChartInstance.data.datasets[0].data = values;
                pembayaranChartInstance.update();
            }
            
            // Update counts on the summary panel
            const countProses = document.getElementById('pembayaranCountProses');
            const countBerhasil = document.getElementById('pembayaranCountBerhasil');
            const countDitolak = document.getElementById('pembayaranCountDitolak');
            
            if (countProses) countProses.textContent = data['Proses'] || 0;
            if (countBerhasil) countBerhasil.textContent = data['Berhasil'] || 0;
            if (countDitolak) countDitolak.textContent = data['Ditolak'] || 0;
        })
        .catch(err => console.error("Error updating pembayaran chart:", err));
}

// AJAX update for Biaya Chart
function updateBiayaChart() {
    const filterTypeSelect = document.getElementById('biayaFilterType');
    const yearSelect = document.getElementById('biayaYearSelect');
    const monthSelect = document.getElementById('biayaMonthSelect');
    
    toggleFilterSelectors(filterTypeSelect, yearSelect, monthSelect);
    
    const filterType = filterTypeSelect.value;
    const year = yearSelect.value;
    const month = monthSelect.value;
    
    let url = `/SobatKost/index.php?url=home/biaya&filter_type=${filterType}`;
    if (filterType === 'year' || filterType === 'month') {
        url += `&year=${year}`;
    }
    if (filterType === 'month') {
        url += `&month=${month}`;
    }
    
    fetch(url)
        .then(res => res.json())
        .then(data => {
            const categories = ['Listrik', 'Air', 'Kebersihan', 'Gaji Karyawan', 'Perbaikan', 'Lainnya'];
            const values = categories.map(cat => data[cat] || 0.0);
            
            if (biayaChartInstance) {
                biayaChartInstance.data.datasets[0].data = values;
                biayaChartInstance.update();
            }
        })
        .catch(err => console.error("Error updating biaya chart:", err));
}

function setupFilters() {
    // Revenue Filter
    const revSelect = document.getElementById('revenueYearSelect');
    if (revSelect) {
        revSelect.addEventListener('change', updateRevenueChart);
    }
    
    // Pembayaran Filters
    const pemFilterType = document.getElementById('pembayaranFilterType');
    const pemYear = document.getElementById('pembayaranYearSelect');
    const pemMonth = document.getElementById('pembayaranMonthSelect');
    
    if (pemFilterType && pemYear && pemMonth) {
        pemFilterType.addEventListener('change', updatePembayaranChart);
        pemYear.addEventListener('change', updatePembayaranChart);
        pemMonth.addEventListener('change', updatePembayaranChart);
    }
    
    // Biaya Filters
    const biayaFilterType = document.getElementById('biayaFilterType');
    const biayaYear = document.getElementById('biayaYearSelect');
    const biayaMonth = document.getElementById('biayaMonthSelect');
    
    if (biayaFilterType && biayaYear && biayaMonth) {
        biayaFilterType.addEventListener('change', updateBiayaChart);
        biayaYear.addEventListener('change', updateBiayaChart);
        biayaMonth.addEventListener('change', updateBiayaChart);
    }
}
