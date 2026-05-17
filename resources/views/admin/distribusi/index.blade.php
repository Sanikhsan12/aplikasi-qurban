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
                                Dokumentasi Distribusi
                            </h1>
                            <p class="text-muted mb-0">Kelola hasil distribusi dengan menambahkan dokumentasinya</p>
                        </div>
                        <div class="btn-group-responsive">
                            <a href="{{ route('admin.distribusi.create') }}" class="btn btn-custom shadow-sm">
                                <i class="fas fa-plus-circle fa-sm mr-2"></i>Tambah Dokumentasi Distribusi
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
    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover align-middle mb-0">

            <thead class="table-header bg-light">
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Tanggal Penyembelihan</th>
                    <th>Google Drive</th>
                    <th class="text-center">Dokumentasi</th>
                    <th>Tanggal Upload</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($distribusi as $item)
                    <tr>

                        {{-- NOMOR --}}
                        <td class="text-center align-middle">
                            {{ $loop->iteration }}
                        </td>

                        {{-- TANGGAL PENYEMBELIHAN --}}
                        <td class="align-middle">
                            <span class="custom-badge badge-weight">

                                {{ optional($item->pelaksanaan)->Penyembelihan
                                    ? \Carbon\Carbon::parse($item->pelaksanaan->Penyembelihan)->format('d M Y')
                                    : '-' }}

                            </span>
                        </td>

                        {{-- GOOGLE DRIVE --}}
                        <td class="align-middle">

                            @if ($item->link_gdrive)

                                <a href="{{ $item->link_gdrive }}"
                                    target="_blank"
                                    class="btn btn-sm btn-outline-primary">

                                    <i class="fas fa-external-link-alt mr-1"></i>
                                    Buka Drive
                                </a>

                            @else

                                <span class="text-muted">
                                    -
                                </span>

                            @endif

                        </td>

                        {{-- DOKUMENTASI --}}
                        <td class="text-center align-middle">

                            @if ($item->dokumentasi && count($item->dokumentasi) > 0)

                                <button type="button"
                                    class="btn btn-dark btn-sm"
                                    data-toggle="modal"
                                    data-target="#imageModal{{ $item->id }}">

                                    <i class="fas fa-eye mr-1"></i>
                                    Lihat

                                    <span class="badge badge-light ml-1">
                                        {{ count($item->dokumentasi) }}
                                    </span>

                                </button>

                            @else

                                <span class="text-muted">
                                    Tidak Ada
                                </span>

                            @endif

                        </td>

                        {{-- TANGGAL UPLOAD --}}
                        <td class="align-middle">

                            <span class="custom-badge badge-quantity">

                                {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}

                            </span>

                        </td>

                        {{-- AKSI --}}
                        <td class="text-center align-middle">

                            <x-action-buttons
                                :editRoute="route('admin.distribusi.edit', $item->id)"
                                :deleteRoute="route('admin.distribusi.destroy', $item->id)"
                            />

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">

                            <i class="fas fa-box-open fa-2x mb-3 d-block text-gray-300"></i>

                            Tidak ada data dokumentasi distribusi.

                        </td>
                    </tr>

                @endforelse
            </tbody>

        </table>
    </div>
</div>

                            <!-- Mobile Card View -->
                            <div class="d-md-none mobile-card-view">
                                @forelse($distribusi as $item)
                                    <div class="card mb-3 shadow-sm">
                                        <div class="card-body">
                                            <div class="mobile-row">
                                                <div>
                                                    <div class="mobile-label">Tanggal Penyembelihan</div>
                                                    <div class="mobile-value">
                                                        <span class="custom-badge badge-weight">
                                                            {{ optional($item->pelaksanaan)->Penyembelihan
                                                                ? \Carbon\Carbon::parse($item->pelaksanaan->Penyembelihan)->format('d M Y')
                                                                : '-' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="mobile-label">Link Google Drive</div>
                                                    <div class="mobile-value animal-type">
                                                        @if ($item->link_gdrive)
                                                            <a href="{{ $item->link_gdrive }}" target="_blank"
                                                                class="btn btn-sm btn-outline-primary btn-block">
                                                                <i class="fas fa-external-link-alt mr-1"></i>Drive
                                                            </a>
                                                        @else
                                                            -
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mobile-row">
                                                <div>
                                                    <div class="mobile-label">Dokumentasi</div>
                                                    <div class="mobile-value price-tag">
                                                        @if ($item->dokumentasi && count($item->dokumentasi) > 0)
                                                            <button type="button"
                                                                class="btn btn-sm btn-dark view-images-btn"
                                                                data-toggle="modal"
                                                                data-target="#imageModal{{ $item->id }}"
                                                                style="width: 100%;">
                                                                <i class="fas fa-eye"></i> Lihat Dokumentasi
                                                                <span
                                                                    class="badge badge-light ml-1">{{ count($item->dokumentasi) }}</span>
                                                            </button>
                                                        @else
                                                            -
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="mobile-label">Tanggal Upload</div>
                                                    <div class="mobile-value">
                                                        <span class="custom-badge badge-quantity">
                                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                                                        </span>
                                                    </div>
                                                </div>
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
                            </div>

                            <!-- Pagination -->
                            @if ($distribusi instanceof \Illuminate\Pagination\LengthAwarePaginator && $distribusi->hasPages())
                                <div class="d-flex justify-content-center mt-5">
                                    <nav aria-label="Page navigation">
                                        {{ $distribusi->onEachSide(1)->links('pagination::bootstrap-5') }}
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

    <!-- Image Modals -->
    @include('components.modal-distribusi')

    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap & jQuery Scripts -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <script src="{{ asset('js/admin/distribusi/index.js') }}"></script>

</body>

</html>
