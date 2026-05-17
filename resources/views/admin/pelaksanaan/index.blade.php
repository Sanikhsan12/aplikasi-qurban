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
                            <h1 class="h3 mb-0 text-gray-800">
                                Pelaksanaan Kurban
                            </h1>
                            <p class="text-muted mb-0">Kelola jadwal pelaksanaan kurban</p>
                        </div>
                        <div class="btn-group-responsive">
                            <a href="{{ route('admin.pelaksanaan.create') }}" class="btn btn-custom shadow-sm">
                                <i class="fas fa-plus-circle fa-sm mr-2"></i>Tambah Jadwal
                            </a>
                        </div>
                    </div>

                    <!-- Messages -->
                    @include('components.admin.message')

                    <!-- Data Table -->
                    <div class="card custom-card fade-in">
                        <div class="card-body">

                            <!-- Desktop Table View -->
                            <div class="d-none d-md-block">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-header">
                                            <tr>
                                                <th width="50">No</th>
                                                <th>Tanggal Pendaftaran</th>
                                                <th>Tanggal Penutupan</th>
                                                <th>Ketuplak</th>
                                                <th>Lokasi</th>
                                                <th>Tanggal Penyembelihan</th>
                                                <th>Status</th>
                                                <th width="140" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-body">
                                            @forelse($pelaksanaan as $item)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td class="date-cell">
                                                        {{ \Carbon\Carbon::parse($item->Tanggal_Pendaftaran)->format('d M Y') }}
                                                    </td>
                                                    <td class="date-cell">
                                                        {{ \Carbon\Carbon::parse($item->Tanggal_Penutupan)->format('d M Y') }}
                                                    </td>
                                                    <td>{{ $item->Ketuplak }}</td>
                                                    <td>{{ $item->Lokasi }}</td>
                                                    <td class="date-cell">
                                                        {{ \Carbon\Carbon::parse($item->Penyembelihan)->format('d M Y') }}
                                                    </td>
                                                    <td>{{ $item->Status }}</td>
                                                    <td class="text-center">
                                                        <div class="d-inline-flex">
                                                            <a href="{{ route('admin.pelaksanaan.edit', $item->id) }}"
                                                                class="action-btn btn-warning" title="Edit"
                                                                data-toggle="tooltip">
                                                                <i class="fas fa-edit fa-xs text-white"></i>
                                                            </a>
                                                            <form
                                                                action="{{ route('admin.pelaksanaan.destroy', $item->id) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirmDelete()">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="action-btn btn-danger border-0 ml-2"
                                                                    title="Hapus" data-toggle="tooltip">
                                                                    <i class="fas fa-trash fa-xs text-white"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7">
                                                        <div class="empty-state">
                                                            <div class="empty-state-icon">
                                                                <i class="fas fa-database"></i>
                                                            </div>
                                                            <h4 class="text-gray-700 mb-3">Data Belum Tersedia</h4>
                                                            <p class="text-gray-500 mb-4">Belum ada data pelaksanaan kurban yang
                                                                tersedia. Mulai dengan menambahkan data pertama.</p>
                                                            <a href="{{ route('admin.pelaksanaan.create') }}"
                                                                class="btn btn-custom">
                                                                <i class="fas fa-plus-circle mr-2"></i>Tambah Jadwal Kurban
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Mobile Card View -->
                            <div class="d-md-none mobile-card-view">
                                @forelse($pelaksanaan as $item)
                                    <div class="card mb-3 shadow-sm">
                                        <div class="card-body">
                                            <div class="mobile-row">
                                                <div>
                                                    <div class="mobile-label">Lokasi Penyembelihan</div>
                                                    <div class="mobile-value">
                                                        <span class="custom-badge badge-weight">
                                                            {{ $item->Lokasi }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="mobile-label">Ketua Panitia</div>
                                                    <div class="mobile-value animal-type">{{ $item->Ketuplak }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mobile-row">
                                                <div>
                                                    <div class="mobile-label">Pembukaan Pendaftaran</div>
                                                    <div class="mobile-value price-tag">{{ \Carbon\Carbon::parse($item->Tanggal_Pendaftaran)->format('d M Y') }}
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="mobile-label">Penutupan Pendaftaran</div>
                                                    <div class="mobile-value">
                                                        <span class="custom-badge badge-quantity">
                                                            {{ \Carbon\Carbon::parse($item->Tanggal_Penutupan)->format('d M Y') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mobile-row">                                                
                                                <div>
                                                    <div class="mobile-label">Waktu Penyembelihan</div>
                                                    <div class="mobile-value price-tag">{{ \Carbon\Carbon::parse($item->Penyembelihan)->format('d M Y') }}
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="mobile-label">Status</div>
                                                    <div class="mobile-value">
                                                        <span class="custom-badge badge-quantity">
                                                            {{ $item->Status }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-center pt-2">
                                                <a href="{{ route('admin.pelaksanaan.edit', $item->id) }}"
                                                    class="action-btn btn-warning mx-2" title="Edit">
                                                    <i class="fas fa-edit fa-sm text-white"></i>
                                                    <span class="d-none d-sm-inline ml-1">Edit</span>
                                                </a>
                                                <form
                                                    action="{{ route('admin.pelaksanaan.destroy', $item->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirmDelete()">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn btn-danger border-0 mx-2"
                                                        title="Hapus">
                                                        <i class="fas fa-trash fa-sm text-white"></i>
                                                        <span class="d-none d-sm-inline ml-1">Hapus</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-database"></i>
                                        </div>
                                        <h4 class="text-gray-700 mb-3">Data Belum Tersedia</h4>
                                        <p class="text-gray-500 mb-4">Belum ada data hewan kurban yang tersedia.</p>
                                        <a href="{{ route('admin.ketersediaan-hewan.create') }}"
                                            class="btn btn-custom">
                                            <i class="fas fa-plus-circle mr-2"></i>Tambah Data Hewan
                                        </a>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Pagination -->
                            @if ($pelaksanaan instanceof \Illuminate\Pagination\LengthAwarePaginator && $pelaksanaan->hasPages())
                                <div class="d-flex justify-content-center mt-5">
                                    <nav aria-label="Page navigation">
                                        {{ $pelaksanaan->onEachSide(1)->links('pagination::bootstrap-5') }}
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

    <!-- Bootstrap & jQuery Scripts -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <script>
        // Confirm delete function
        function confirmDelete() {
            return confirm('Apakah Anda yakin ingin menghapus data?');
        }

        // Initialize tooltips
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip({
                trigger: 'hover'
            });

            // Mobile optimization for modals
            if ($(window).width() < 768) {
                $('.modal-dialog').addClass('m-0');
            }

            // Adjust modal for mobile
            $(window).resize(function() {
                if ($(window).width() < 768) {
                    $('.modal-dialog').addClass('m-0');
                } else {
                    $('.modal-dialog').removeClass('m-0');
                }
            });

            // Touch optimization for mobile
            if ('ontouchstart' in window) {
                // Make buttons more touch-friendly
                $('.btn, .action-btn').css({
                    'min-height': '44px',
                    'min-width': '44px'
                });

                // Increase touch target for table rows
                $('.table tbody tr').css('padding', '12px 0');
            }

            // Close dropdowns when clicking outside on mobile
            $(document).on('click', function(event) {
                if (!$(event.target).closest('.dropdown').length) {
                    $('.dropdown-menu').removeClass('show');
                }
            });

            // Smooth scroll for mobile
            $('a[href^="#"]').on('click', function(e) {
                if ($(window).width() < 768) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: $($(this).attr('href')).offset().top - 20
                    }, 300);
                }
            });
        });

        // Image modal optimization
        $(document).on('show.bs.modal', '.image-modal', function() {
            if ($(window).width() < 768) {
                $(this).find('.modal-dialog').addClass('m-2');
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
    </script>

</body>

</html>
