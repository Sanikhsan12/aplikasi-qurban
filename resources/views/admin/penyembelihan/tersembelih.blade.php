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
                                Data Penyembelihan Hewan Kurban
                            </h1>
                            <p class="text-muted mb-0">Kelola status dan dokumentasi penyembelihan</p>
                        </div>
                    </div>

                    <!-- Messages -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-custom fade-in">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle fa-lg mr-3"></i>
                                <div>
                                    <strong>Perhatian!</strong> Terdapat kesalahan dalam data:
                                    <ul class="mb-0 mt-2 pl-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    @endif

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
                            <div class="d-none d-md-block">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th width="50">No</th>
                                                <th>Jadwal Penyembelihan</th>
                                                <th>Nama Donatur</th>
                                                <th>Status</th>
                                                <th>Dokumentasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($penyembelihan as $item)
                                                <tr class="fade-in">
                                                    <td class="font-weight-bold">{{ $loop->iteration }}</td>
                                                    <td>
                                                        <span class="text-primary">
                                                            {{ \Carbon\Carbon::parse($item->pelaksanaan->Penyembelihan)->format('d M Y') ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="donor-name">{{ $item->order->user->name ?? '-' }}</span>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $statusClass = '';
                                                            if ($item->status == 'menunggu penyembelihan') {
                                                                $statusClass = 'badge-in-progress';
                                                            } elseif ($item->status == 'tersembelih') {
                                                                $statusClass = 'badge-completed';
                                                            }
                                                        @endphp
                                                        <span class="custom-badge {{ $statusClass }}">
                                                            {{ ucfirst(str_replace('_', ' ', $item->status)) ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if ($item->dokumentasi_penyembelihan)
                                                            <img src="{{ asset('storage/' . $item->dokumentasi_penyembelihan) }}"
                                                                alt="Foto penyembelihan" class="photo-thumbnail"
                                                                data-toggle="modal"
                                                                data-target="#imageModal{{ $item->id }}">
                                                        @else
                                                            <span class="text-muted small">
                                                                <i class="fas fa-image mr-1"></i> Belum ada foto
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-5">
                                                        <div class="empty-state">
                                                            <div class="empty-state-icon">
                                                                <i class="fas fa-calendar-times"></i>
                                                            </div>
                                                            <h4 class="text-gray-700 mb-3">Belum Ada Jadwal</h4>
                                                            <p class="text-gray-500 mb-4">Tidak ada data penyembelihan
                                                                yang tersedia.</p>
                                                            <a href="{{ route('admin.penyembelihan.create') }}"
                                                                class="btn btn-custom">
                                                                <i class="fas fa-plus-circle mr-2"></i>Tambah Data
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
                            <div class="d-md-none">
                                @forelse ($penyembelihan as $item)
                                    @php
                                        $statusClass = '';
                                        if ($item->status == 'menunggu penyembelihan') {
                                            $statusClass = 'badge-in-progress';
                                        } elseif ($item->status == 'tersembelih') {
                                            $statusClass = 'badge-completed';
                                        }
                                    @endphp

                                    <div class="mobile-card-view card mb-3 shadow-sm fade-in">
                                        <div class="card-body">
                                            <div class="mobile-row">
                                                <div>
                                                    <div class="mobile-label">No</div>
                                                    <div class="mobile-value font-weight-bold">{{ $loop->iteration }}
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="mobile-label">Status</div>
                                                    <div class="mobile-value">
                                                        <span class="custom-badge {{ $statusClass }}">
                                                            {{ ucfirst(str_replace('_', ' ', $item->status)) ?? '-' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mobile-row">
                                                <div>
                                                    <div class="mobile-label">Jadwal Penyembelihan</div>
                                                    <div class="mobile-value text-primary">
                                                        {{ \Carbon\Carbon::parse($item->pelaksanaan->Penyembelihan)->format('d M Y') ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mobile-row">
                                                <div>
                                                    <div class="mobile-label">Nama Donatur</div>
                                                    <div class="mobile-value donor-name">
                                                        {{ $item->order->user->name ?? '-' }}</div>
                                                </div>
                                            </div>

                                            <div class="mobile-row">
                                                <div>
                                                    <div class="mobile-label">Dokumentasi</div>
                                                    <div class="mobile-value">
                                                        @if ($item->dokumentasi_penyembelihan)
                                                            <img src="{{ asset('storage/' . $item->dokumentasi_penyembelihan) }}"
                                                                alt="Foto penyembelihan" class="mobile-photo"
                                                                data-toggle="modal"
                                                                data-target="#imageModal{{ $item->id }}">
                                                        @else
                                                            <span class="text-muted small">
                                                                <i class="fas fa-image"></i> Belum ada
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-calendar-times"></i>
                                        </div>
                                        <h4 class="text-gray-700 mb-3">Belum Ada Jadwal</h4>
                                        <p class="text-gray-500 mb-4">Tidak ada data penyembelihan yang tersedia.</p>
                                        <a href="{{ route('admin.penyembelihan.create') }}" class="btn btn-custom">
                                            <i class="fas fa-plus-circle mr-2"></i>Tambah Data
                                        </a>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Pagination -->
                            @if ($penyembelihan instanceof \Illuminate\Pagination\LengthAwarePaginator && $penyembelihan->hasPages())
                                <div class="d-flex justify-content-center mt-5">
                                    <nav aria-label="Page navigation">
                                        {{ $penyembelihan->onEachSide(1)->links('pagination::bootstrap-5') }}
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

    <!-- Image Modals -->
    @foreach ($penyembelihan as $item)
        @if ($item->dokumentasi_penyembelihan)
            <div class="modal fade" id="imageModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-image mr-2"></i>Dokumentasi Penyembelihan
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="{{ asset('storage/' . $item->dokumentasi_penyembelihan) }}"
                                alt="Dokumentasi Penyembelihan" class="img-fluid rounded">
                            <div class="mt-3 text-left">
                                <p><strong>Donatur:</strong> {{ $item->order->user->name ?? '-' }}</p>
                                <p><strong>Tanggal:</strong>
                                    {{ \Carbon\Carbon::parse($item->pelaksanaan->Penyembelihan)->format('d M Y') ?? '-' }}
                                </p>
                                <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $item->status)) ?? '-' }}
                                </p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times mr-2"></i>Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <!-- Logout Modal -->
    @include('components.admin.logout')

    <!-- Bootstrap & jQuery Scripts -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/admin/penyembelihan/tersembelih.js') }}"></
</body>

</html>
