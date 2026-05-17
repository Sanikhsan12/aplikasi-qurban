<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Dashboard Manajemen Kurban">
    <meta name="author" content="">

    <title>Dashboard Pengelolaan Kurban</title>

    <!-- Custom fonts -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles -->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin/style.css') }}">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('components.admin.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('components.admin.topbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h1 class="h3 mb-0 text-gray-800">
                                <i class="fas fa-tachometer-alt text-primary mr-2"></i>Dashboard Manajemen Kurban
                            </h1>
                            <p class="text-muted mb-0">Ringkasan statistik dan aktivitas sistem kurban</p>
                        </div>
                    </div>

                    <!-- Messages -->
                    @include('components.admin.message')

                    <!-- Quick Stats Row -->
                    <div class="row mb-4">
                        <!-- Total Peserta -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card dashboard-card border-left-primary">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="metric-label">Total Peserta Kurban</div>
                                            <div class="metric-value text-gray-800">{{ $total_peserta ?? 0 }}</div>
                                            <div class="metric-change positive mt-2">
                                                <i class="fas fa-arrow-up mr-1"></i>Total seluruh peserta
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="card-icon text-primary">
                                                <i class="fas fa-users"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Hewan -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card dashboard-card border-left-success">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="metric-label">Total Hewan Kurban</div>
                                            <div class="metric-value text-gray-800">{{ $total_hewan ?? 0 }}</div>
                                            <div class="metric-change positive mt-2">
                                                <i class="fas fa-check-circle mr-1"></i>Semua jenis hewan
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="card-icon text-success">
                                                <i class="fas fa-paw"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Daging -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card dashboard-card border-left-info">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="metric-label">Daging Terdistribusi</div>
                                            <div class="metric-value text-gray-800">
                                                {{ $total_berat_daging ? number_format($total_berat_daging, 1) . ' kg' : '0 kg' }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="card-icon text-info">
                                                <i class="fas fa-weight"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Pendapatan -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card dashboard-card border-left-warning">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="metric-label">Total Pendapatan</div>
                                            <div class="metric-value text-gray-800">
                                                Rp{{ $total_pendapatan ? number_format($total_pendapatan, 0, ',', '.') : '0' }}
                                            </div>
                                            <div class="metric-change positive mt-2">
                                                <i class="fas fa-chart-line mr-1"></i>Total pendapatan
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="card-icon text-warning">
                                                <i class="fas fa-coins"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content Row -->
                    <div class="row">
                        <!-- Status Pembayaran Chart -->
                        <div class="col-xl-8 col-lg-7 mb-4">
                            <div class="card dashboard-card">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-credit-card mr-2"></i>Status Pembayaran Peserta
                                    </h6>
                                    <span class="badge badge-primary">Total: {{ $orders['total'] ?? 0 }} Order</span>
                                </div>
                                <div class="card-body">
                                    <!-- Disetujui -->
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <span class="font-weight-bold text-success">
                                                    <i class="fas fa-check-circle mr-1"></i>Disetujui
                                                </span>
                                                <small class="text-muted ml-2">
                                                    {{ $orders['disetujui']['count'] ?? 0 }} order
                                                </small>
                                            </div>
                                            <div class="text-right">
                                                <span class="font-weight-bold text-dark">
                                                    {{ $paymentStats['disetujui']['percent'] ?? 0 }}%
                                                </span>
                                                <div class="text-success small">
                                                    Rp{{ number_format($orders['disetujui']['total_nominal'] ?? 0, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress progress-thin">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: {{ $paymentStats['disetujui']['percent'] ?? 0 }}%">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Menunggu Verifikasi -->
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <span class="font-weight-bold text-warning">
                                                    <i class="fas fa-clock mr-1"></i>Menunggu Verifikasi
                                                </span>
                                                <small class="text-muted ml-2">
                                                    {{ $orders['menunggu_verifikasi']['count'] ?? 0 }} order
                                                </small>
                                            </div>
                                            <div class="text-right">
                                                <span class="font-weight-bold text-dark">
                                                    {{ $paymentStats['menunggu verifikasi']['percent'] ?? 0 }}%
                                                </span>
                                                <div class="text-warning small">
                                                    Rp{{ number_format($orders['menunggu_verifikasi']['total_nominal'] ?? 0, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress progress-thin">
                                            <div class="progress-bar bg-warning" role="progressbar"
                                                style="width: {{ $paymentStats['menunggu verifikasi']['percent'] ?? 0 }}%">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Ditolak -->
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <span class="font-weight-bold text-danger">
                                                    <i class="fas fa-times-circle mr-1"></i>Ditolak
                                                </span>
                                                <small class="text-muted ml-2">
                                                    {{ $orders['ditolak']['count'] ?? 0 }} order
                                                </small>
                                            </div>
                                            <div class="text-right">
                                                <span class="font-weight-bold text-dark">
                                                    {{ $paymentStats['ditolak']['percent'] ?? 0 }}%
                                                </span>
                                                <div class="text-danger small">
                                                    Rp{{ number_format($orders['ditolak']['total_nominal'] ?? 0, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress progress-thin">
                                            <div class="progress-bar bg-danger" role="progressbar"
                                                style="width: {{ $paymentStats['ditolak']['percent'] ?? 0 }}%">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Summary Grid -->
                                    <div class="row text-center mt-4 pt-3 border-top">
                                        <div class="col-4">
                                            <div class="summary-card">
                                                <div class="text-success font-weight-bold">
                                                    {{ $orders['disetujui']['count'] ?? 0 }}
                                                </div>
                                                <small class="text-muted">Disetujui</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="summary-card">
                                                <div class="text-warning font-weight-bold">
                                                    {{ $orders['menunggu_verifikasi']['count'] ?? 0 }}
                                                </div>
                                                <small class="text-muted">Menunggu</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="summary-card">
                                                <div class="text-danger font-weight-bold">
                                                    {{ $orders['ditolak']['count'] ?? 0 }}
                                                </div>
                                                <small class="text-muted">Ditolak</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions & Stats -->
                        <div class="col-xl-4 col-lg-5 mb-4">
                            <div class="card dashboard-card">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-bolt mr-2"></i>Aksi Cepat
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.pelaksanaan.index') }}" class="quick-action-btn">
                                            <i class="fas fa-calendar-alt text-primary"></i>
                                            <span>Kelola Jadwal Penyembelihan</span>
                                        </a>
                                        <a href="{{ route('admin.distribusi.index') }}" class="quick-action-btn">
                                            <i class="fas fa-truck text-success"></i>
                                            <span>Lihat Distribusi Daging</span>
                                        </a>
                                        {{-- <a href="{{ route('admin.peserta.index') }}" class="quick-action-btn"> --}}
                                        <a href="#" class="quick-action-btn">
                                            <i class="fas fa-users text-info"></i>
                                            <span>Kelola Data Peserta</span>
                                        </a>
                                        <a href="{{ route('admin.ketersediaan-hewan.index') }}"
                                            class="quick-action-btn">
                                            <i class="fas fa-paw text-warning"></i>
                                            <span>Stok Hewan Kurban</span>
                                        </a>
                                    </div>

                                    <!-- Recent Activities -->
                                    <div class="mt-4 pt-3 border-top">
                                        <h6 class="font-weight-bold text-dark mb-3">
                                            <i class="fas fa-history mr-2"></i>Aktivitas Terbaru
                                        </h6>
                                        @if (isset($recent_activities) && count($recent_activities) > 0)
                                            @foreach ($recent_activities as $activity)
                                                <div class="activity-item">
                                                    <div
                                                        class="activity-icon bg-{{ $activity['color'] ?? 'primary' }} text-white">
                                                        <i
                                                            class="{{ $activity['icon'] ?? 'fas fa-info-circle' }}"></i>
                                                    </div>
                                                    <div>
                                                        <div class="font-weight-bold">{{ $activity['title'] }}</div>
                                                        <small class="text-muted">{{ $activity['time'] }}</small>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center py-3">
                                                <i class="fas fa-info-circle text-muted fa-2x mb-2"></i>
                                                <p class="text-muted mb-0">Belum ada aktivitas terbaru</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Stats Row -->
                    <div class="row">
                        <!-- Jenis Hewan Distribution -->
                        <div class="col-xl-6 col-lg-6 mb-4">
                            <div class="card dashboard-card">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-chart-pie mr-2"></i>Distribusi Jenis Hewan
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="jenisHewanChart"></canvas>
                                    </div>
                                    @if (isset($hewan_stats) && count($hewan_stats) > 0)
                                        <div class="row mt-3 text-center">
                                            @foreach ($hewan_stats as $stat)
                                                <div class="col">
                                                    <div class="animal-card">
                                                        <div class="animal-icon text-{{ $stat['color'] }}">
                                                            <i class="{{ $stat['icon'] }}"></i>
                                                        </div>
                                                        <div class="font-weight-bold">{{ $stat['count'] }}</div>
                                                        <small class="text-muted">{{ $stat['label'] }}</small>
                                                        <div class="mt-2">
                                                            <span class="badge badge-{{ $stat['color'] }}">
                                                                {{ $stat['percentage'] }}%
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Target vs Realisasi -->
                        <div class="col-xl-6 col-lg-6 mb-4">
                            <div class="card dashboard-card">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-bullseye mr-2"></i>Target vs Realisasi
                                    </h6>
                                    <span class="badge badge-success">{{ $achievement_rate ?? 0 }}% Tercapai</span>
                                </div>
                                <div class="card-body">
                                    <!-- Target Peserta -->
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="font-weight-bold">Target Peserta</span>
                                            <span class="font-weight-bold">{{ $target_peserta ?? 0 }} /
                                                {{ $actual_peserta ?? 0 }}</span>
                                        </div>
                                        <div class="progress progress-thin">
                                            <div class="progress-bar bg-info" role="progressbar"
                                                style="width: {{ $peserta_achievement ?? 0 }}%">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Target Hewan -->
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="font-weight-bold">Target Hewan</span>
                                            <span class="font-weight-bold">{{ $target_hewan ?? 0 }} /
                                                {{ $actual_hewan ?? 0 }}</span>
                                        </div>
                                        <div class="progress progress-thin">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: {{ $hewan_achievement ?? 0 }}%">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Target Pendapatan -->
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="font-weight-bold">Target Pendapatan</span>
                                            <span class="font-weight-bold">
                                                Rp{{ number_format($actual_pendapatan ?? 0, 0, ',', '.') }} /
                                                Rp{{ number_format($target_pendapatan ?? 0, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <div class="progress progress-thin">
                                            <div class="progress-bar bg-warning" role="progressbar"
                                                style="width: {{ $pendapatan_achievement ?? 0 }}%">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Summary Grid -->
                                    <div class="row text-center mt-4">
                                        <div class="col-4">
                                            <div class="p-3 bg-light rounded">
                                                <div class="font-weight-bold text-info">
                                                    {{ $peserta_achievement ?? 0 }}%</div>
                                                <small class="text-muted">Peserta</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="p-3 bg-light rounded">
                                                <div class="font-weight-bold text-success">
                                                    {{ $hewan_achievement ?? 0 }}%</div>
                                                <small class="text-muted">Hewan</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="p-3 bg-light rounded">
                                                <div class="font-weight-bold text-warning">
                                                    {{ $pendapatan_achievement ?? 0 }}%</div>
                                                <small class="text-muted">Pendapatan</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Sistem Manajemen Kurban {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    @include('components.admin.logout')

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <!-- Chart.js -->
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Dashboard Custom Script -->
    <script>
        $(document).ready(function() {
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Jenis Hewan Chart
            var ctx = document.getElementById('jenisHewanChart').getContext('2d');
            var jenisHewanChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($chart_labels ?? ['Domba', 'Kambing', 'Sapi']) !!},
                    datasets: [{
                        data: {!! json_encode($chart_data ?? [30, 40, 30]) !!},
                        backgroundColor: [
                            '#4e73df',
                            '#1cc88a',
                            '#36b9cc',
                            '#f6c23e',
                            '#e74a3b'
                        ],
                        hoverBackgroundColor: [
                            '#2e59d9',
                            '#17a673',
                            '#2c9faf',
                            '#dda20a',
                            '#be2617'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    cutoutPercentage: 70,
                    legend: {
                        display: false
                    },
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var label = data.labels[tooltipItem.index];
                                var value = data.datasets[0].data[tooltipItem.index];
                                var total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                var percentage = Math.round((value / total) * 100);
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            });

            // Auto-refresh data every 30 seconds
            setInterval(function() {
                updateDashboardStats();
            }, 30000);

            // Function to update dashboard stats via AJAX
            function updateDashboardStats() {
                $.ajax({
                    url: '{{ route('admin/dashboard') }}',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Update card values
                        updateCardValue('#totalPeserta', data.total_peserta);
                        updateCardValue('#totalHewan', data.total_hewan);
                        updateCardValue('#totalDaging', data.total_daging + ' kg');
                        updateCardValue('#totalPendapatan', 'Rp' + formatNumber(data.total_pendapatan));

                        // Update progress bars
                        updateProgressBar('.progress-disetujui', data.payment_stats.disetujui.percent);
                        updateProgressBar('.progress-menunggu', data.payment_stats.menunggu.percent);
                        updateProgressBar('.progress-ditolak', data.payment_stats.ditolak.percent);

                        // Update chart data
                        jenisHewanChart.data.datasets[0].data = data.chart_data;
                        jenisHewanChart.update();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating dashboard:', error);
                    }
                });
            }

            // Helper function to update card values with animation
            function updateCardValue(selector, newValue) {
                var element = $(selector);
                element.fadeOut(200, function() {
                    $(this).text(newValue).fadeIn(200);
                });
            }

            // Helper function to update progress bars
            function updateProgressBar(selector, newValue) {
                $(selector).css('width', newValue + '%')
                    .attr('aria-valuenow', newValue);
            }

            // Helper function to format numbers
            function formatNumber(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
            }

            // Initialize all charts
            initCharts();
        });

        function initCharts() {
            // Additional chart initializations can be added here
        }
    </script>
</body>

</html>
