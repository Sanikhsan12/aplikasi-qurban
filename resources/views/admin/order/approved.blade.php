<!DOCTYPE html>
<html lang="id">

@include('components.admin.head')

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
                    <!-- Page Header -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h1 class="h2 mb-0 text-gray-800">
                                Pembayaran telah disetujui
                            </h1>
                        </div>
                    </div>

                    <!-- Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-custom fade-in">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle fa-lg mr-3"></i>
                                <div>
                                    <strong>Sukses!</strong> {{ session('success') }}
                                </div>
                                <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-custom fade-in">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle fa-lg mr-3"></i>
                                <div>
                                    <strong>Error!</strong> {{ session('error') }}
                                </div>
                                <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Data Table -->
                    <div class="card custom-card fade-in">
                        <div class="card-body">
                            <!-- Desktop Table View -->
                            <table class="table table-hover mb-0">
                                <h1 class="h3 mb-0 black text-center">
                                    Buka diperangkat dimobile
                                </h1>
                            </table>

                            <!-- Mobile Card View -->
                            <div class="d-md-none">
                                @forelse($orders as $item)
                                    <div class="mobile-payment-card fade-in">
                                        <div class="mobile-payment-header">
                                            <div>
                                                <span
                                                    class="font-weight-bold text-primary">#{{ $loop->iteration }}</span>
                                            </div>
                                            <div>
                                                @if ($item->status == 'menunggu verifikasi')
                                                    <span class="mobile-payment-status"
                                                        style="background-color: #fef3c7; color: #92400e;">
                                                        <i class="fas fa-clock"></i> Menunggu Verifikasi
                                                    </span>
                                                @elseif($item->status == 'disetujui')
                                                    <span class="mobile-payment-status"
                                                        style="background-color: #d1fae5; color: #065f46;">
                                                        <i class="fas fa-check-circle"></i> Terverifikasi
                                                    </span>
                                                @elseif($item->status == 'ditolak')
                                                    <span class="mobile-payment-status"
                                                        style="background-color: #fee2e2; color: #991b1b;">
                                                        <i class="fas fa-times-circle"></i> Ditolak
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mobile-payment-body">
                                            <div class="mobile-row">
                                                <div>
                                                    <div class="mobile-label">Nama Donatur</div>
                                                    <div class="mobile-value donor-name">{{ $item->user->name }}</div>
                                                </div>
                                            </div>
                                            <div class="mobile-row">
                                                <div>
                                                    <div class="mobile-label">Jenis Hewan</div>
                                                    <div class="mobile-value">
                                                        <span
                                                            class="custom-badge badge-weight">{{ $item->jenis_hewan }}</span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="mobile-label">Total Hewan</div>
                                                    <div class="mobile-value">{{ $item->total_hewan }} ekor</div>
                                                </div>
                                            </div>
                                            <div class="mobile-row">
                                                <div>
                                                    <div class="mobile-label">Total Harga</div>
                                                    <div class="mobile-value price-tag">Rp
                                                        {{ number_format($item->total_harga, 0, ',', '.') }}</div>
                                                </div>
                                            </div>
                                            @if ($item->bukti_pembayaran)
                                                <div class="mobile-row">
                                                    <div>
                                                        <div class="mobile-label">Bukti Pembayaran</div>
                                                        <div class="mobile-value">
                                                            <a href="{{ Storage::url($item->bukti_pembayaran) }}"
                                                                target="_blank" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-eye me-1"></i> Lihat Bukti
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="mobile-payment-actions">
                                            @if ($item->status == 'menunggu verifikasi')
                                                <button type="button" class="btn btn-success btn-sm flex-fill"
                                                    data-toggle="modal" data-target="#verifyModal{{ $item->id }}">
                                                    <i class="fas fa-check me-1"></i> Setujui
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm flex-fill"
                                                    data-toggle="modal" data-target="#rejectModal{{ $item->id }}">
                                                    <i class="fas fa-times me-1"></i> Tolak
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-outline-info btn-sm flex-fill"
                                                    data-toggle="modal" data-target="#statusModal{{ $item->id }}">
                                                    <i class="fas fa-info-circle me-1"></i> Detail Status
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-receipt"></i>
                                        </div>
                                        <h4 class="text-gray-700 mb-3">Belum Ada Pembayaran</h4>
                                        <p class="text-gray-500 mb-4">Tidak ada data pembayaran peserta yang perlu
                                            diverifikasi.</p>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Pagination -->
                            @if ($orders instanceof \Illuminate\Pagination\LengthAwarePaginator && $orders->hasPages())
                                <div class="d-flex justify-content-center mt-5">
                                    <nav aria-label="Page navigation">
                                        {{ $orders->onEachSide(1)->links('pagination::bootstrap-5') }}
                                    </nav>
                                </div>
                            @endif
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

    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Modals -->
    @foreach ($orders as $item)
        <!-- Verify Modal -->
        <div class="modal fade" id="verifyModal{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-check-circle me-2"></i> Verifikasi Order
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.orders.verify', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin memverifikasi order ini?</p>
                            <div class="alert alert-info">
                                <strong>Detail Order:</strong>
                                <ul class="mb-0 mt-2 pl-3">
                                    <li><strong>Nama:</strong> {{ $item->user->name }}</li>
                                    <li><strong>Jenis Hewan:</strong> {{ $item->jenis_hewan }}</li>
                                    <li><strong>Total Hewan:</strong> {{ $item->total_hewan }} ekor</li>
                                    <li><strong>Total Harga:</strong> Rp
                                        {{ number_format($item->total_harga, 0, ',', '.') }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check me-1"></i> Ya, Verifikasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-times-circle me-2"></i> Tolak Order
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.orders.reject', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin menolak order ini?</p>
                            <div class="alert alert-warning">
                                <strong>Detail Order:</strong>
                                <ul class="mb-0 mt-2 pl-3">
                                    <li><strong>Nama:</strong> {{ $item->user->name }}</li>
                                    <li><strong>Jenis Hewan:</strong> {{ $item->jenis_hewan }}</li>
                                    <li><strong>Total Hewan:</strong> {{ $item->total_hewan }} ekor</li>
                                </ul>
                            </div>
                            <div class="form-group mt-3">
                                <label for="alasan_penolakan_reject{{ $item->id }}" class="form-label required">
                                    <i class="fas fa-exclamation-circle me-1"></i>Alasan Penolakan *
                                </label>
                                <textarea class="form-control form-textarea" id="alasan_penolakan_reject{{ $item->id }}" name="alasan_penolakan"
                                    rows="4" placeholder="Berikan alasan penolakan..." required></textarea>
                                <small class="form-text text-muted">Alasan penolakan wajib diisi.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-ban me-1"></i> Ya, Tolak
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Status Detail Modal -->
        <div class="modal fade" id="statusModal{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-info-circle me-2"></i>Detail Status Order
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <strong>Status:</strong>
                            @if ($item->status == 'disetujui')
                                <span class="badge bg-success ml-2">Terverifikasi</span>
                            @elseif($item->status == 'ditolak')
                                <span class="badge bg-danger ml-2">Ditolak</span>
                            @endif
                        </div>

                        @if ($item->alasan_penolakan)
                            <div class="mb-3">
                                <strong>
                                    @if ($item->status == 'disetujui')
                                        Catatan Verifikasi:
                                    @else
                                        Alasan Penolakan:
                                    @endif
                                </strong>
                                <div class="alert alert-light mt-2">
                                    {{ $item->alasan_penolakan }}
                                </div>
                            </div>
                        @endif

                        @if ($item->verified_at && $item->verified_by)
                            <div class="mb-3">
                                <strong>Diverifikasi oleh:</strong>
                                <div class="mt-1">{{ $item->verifier->name ?? 'Admin' }}</div>
                            </div>
                            <div class="mb-3">
                                <strong>Tanggal Verifikasi:</strong>
                                <div class="mt-1">{{ $item->verified_at->format('d/m/Y H:i') }}</div>
                            </div>
                        @endif

                        @if ($item->rejected_at && $item->rejected_by)
                            <div class="mb-3">
                                <strong>Ditolak oleh:</strong>
                                <div class="mt-1">{{ $item->rejector->name ?? 'Admin' }}</div>
                            </div>
                            <div class="mb-3">
                                <strong>Tanggal Penolakan:</strong>
                                <div class="mt-1">{{ $item->rejected_at->format('d/m/Y H:i') }}</div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Bootstrap & jQuery Scripts -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <script>
        // Initialize tooltips
        $(document).ready(function() {
            // Mobile optimization for modals
            if ($(window).width() < 768) {
                $('.modal-dialog').addClass('m-2');
            }

            // Adjust modal for mobile
            $(window).resize(function() {
                if ($(window).width() < 768) {
                    $('.modal-dialog').addClass('m-2');
                } else {
                    $('.modal-dialog').removeClass('m-2');
                }
            });

            // Touch optimization for mobile
            if ('ontouchstart' in window) {
                // Make buttons more touch-friendly
                $('.btn, .action-btn').css({
                    'min-height': '44px',
                    'min-width': '44px'
                });
            }

            // Form validation for reject modals
            $('form').on('submit', function(e) {
                const rejectTextarea = $(this).find('textarea[required]');
                if (rejectTextarea.length && !rejectTextarea.val().trim()) {
                    e.preventDefault();
                    alert('Alasan penolakan harus diisi!');
                    rejectTextarea.focus();
                }
            });

            // Prevent zoom on double-tap for iOS
            document.addEventListener('touchstart', function(event) {
                if (event.touches.length > 1) {
                    event.preventDefault();
                }
            }, {
                passive: false
            });

            let lastTouchEnd = 0;
            document.addEventListener('touchend', function(event) {
                const now = (new Date()).getTime();
                if (now - lastTouchEnd <= 300) {
                    event.preventDefault();
                }
                lastTouchEnd = now;
            }, false);
        });

        // Modal show/hide events
        $(document).on('show.bs.modal', '.modal', function() {
            if ($(window).width() < 768) {
                $(this).find('.modal-dialog').addClass('m-0');
            }
        });

        $(document).on('hidden.bs.modal', '.modal', function() {
            // Clear textareas when modal is closed
            $(this).find('textarea').val('');
        });
    </script>

</body>

</html>
