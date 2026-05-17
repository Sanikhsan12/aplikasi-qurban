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
                                Ketersediaan Hewan Kurban
                            </h1>
                            <p class="text-muted mb-0">Kelola stok hewan kurban yang tersedia</p>
                        </div>
                        <div class="btn-group-responsive">
                            <a href="{{ route('admin.ketersediaan-hewan.create') }}" class="btn btn-custom shadow-sm">
                                <i class="fas fa-plus-circle fa-sm mr-2"></i>Tambah Hewan
                            </a>
                        </div>
                    </div>

                    <!-- Messages -->
                    @include('components.admin.message')

                    <!-- Data Table -->
                    <x-data-table>

                        {{-- DESKTOP TABLE --}}
                        <div class="d-none d-md-block">
                            <div class="table-responsive shadow-sm rounded">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-header bg-light">
                                        <tr>
                                            <th scope="col" class="text-center" width="5%">No</th>
                                            <th scope="col">Jenis Hewan</th>
                                            <th scope="col">Bobot (kg)</th>
                                            <th scope="col">Jumlah (ekor)</th>
                                            <th scope="col">Harga per Ekor</th>
                                            <th scope="col" class="text-center">Foto</th>
                                            <th scope="col" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse($ketersediaan as $item)
                                            <tr>
                                                <th scope="row" class="text-center align-middle">
                                                    {{ $loop->iteration }}
                                                </th>

                                                <td class="align-middle">
                                                    {{ $item->jenis_hewan }}
                                                </td>

                                                <td class="align-middle">
                                                    <span class="custom-badge badge-weight">
                                                        {{ $item->bobot_formatted }}
                                                    </span>
                                                </td>

                                                <td class="align-middle">
                                                    <span class="custom-badge badge-quantity">
                                                        {{ $item->jumlah }} ekor
                                                    </span>
                                                </td>

                                                <td class="align-middle">
                                                    <span class="price-tag">
                                                        {{ $item->harga_formatted }}
                                                    </span>
                                                </td>

                                                <td class="text-center align-middle">
                                                    @if ($item->foto)
                                                        <img src="{{ $item->foto_url }}"
                                                            alt="{{ $item->jenis_hewan }}"
                                                            class="table-photo rounded shadow-sm"
                                                            data-toggle="modal"
                                                            data-target="#imageModal{{ $item->id }}"
                                                            style="cursor: pointer; width: 60px; height: 60px; object-fit: cover;">
                                                    @else
                                                        <div class="table-photo bg-light d-inline-flex align-items-center justify-content-center rounded shadow-sm"
                                                            style="width: 60px; height: 60px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                </td>

                                   <td class="text-center align-middle">

                                        <x-action-buttons
                                            :editRoute="route('admin.ketersediaan-hewan.edit', $item->id)"
                                            :deleteRoute="route('admin.ketersediaan-hewan.destroy', $item->id)"
                                        />

                                    </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-5 text-muted">
                                                    <i class="fas fa-box-open fa-2x mb-2 d-block text-gray-300"></i>

                                                    Tidak ada data ketersediaan hewan.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- MOBILE VIEW --}}
                        <x-slot name="mobile">
                            @forelse($ketersediaan as $item)
                                <div class="card mb-3 shadow-sm">
                                    <div class="card-body">
                                        <div class="mobile-row">
                                            <div>
                                                <div class="mobile-label">No</div>
                                                <div class="mobile-value font-weight-bold">{{ $loop->iteration }}
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="mobile-label">Jenis Hewan</div>
                                                <div class="mobile-value animal-type">{{ $item->jenis_hewan }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mobile-row">
                                            <div>
                                                <div class="mobile-label">Bobot</div>
                                                <div class="mobile-value">
                                                    <span class="custom-badge badge-weight">
                                                        {{ $item->bobot_formatted }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="mobile-label">Jumlah</div>
                                                <div class="mobile-value">
                                                    <span class="custom-badge badge-quantity">
                                                        {{ $item->jumlah }} ekor
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mobile-row">
                                            <div>
                                                <div class="mobile-label">Harga per Ekor</div>
                                                <div class="mobile-value price-tag">{{ $item->harga_formatted }}
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                @if ($item->foto)
                                                    <img src="{{ $item->foto_url }}" alt="{{ $item->jenis_hewan }}"
                                                        class="mobile-photo" data-toggle="modal"
                                                        data-target="#imageModal{{ $item->id }}">
                                                @else
                                                    <div
                                                        class="mobile-photo bg-light d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-center pt-2">
                                            <a href="{{ route('admin.ketersediaan-hewan.edit', $item->id) }}"
                                                class="action-btn btn-warning mx-2" title="Edit">
                                                <i class="fas fa-edit fa-sm text-white"></i>
                                                <span class="d-none d-sm-inline ml-1">Edit</span>
                                            </a>
                                            <form action="{{ route('admin.ketersediaan-hewan.destroy', $item->id) }}"
                                                method="POST" class="d-inline" onsubmit="return confirmDelete()">
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
                                    <a href="{{ route('admin.ketersediaan-hewan.create') }}" class="btn btn-custom">
                                        <i class="fas fa-plus-circle mr-2"></i>Tambah Data Hewan
                                    </a>
                                </div>
                            @endforelse
                        </x-slot>

                        {{-- PAGINATION --}}
                        <x-slot name="pagination">
                            {{ $ketersediaan->links() }}
                        </x-slot>

                    </x-data-table>

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
