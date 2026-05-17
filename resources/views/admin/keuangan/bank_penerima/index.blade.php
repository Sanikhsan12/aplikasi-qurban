<!DOCTYPE html>
<html lang="en">

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
                <div class="container-fluid px  -0 px-md-3">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h1 class="h3 mb-0 text-gray-800">
                                Bank Penerima Pembayaran Kurban
                            </h1>
                            <p class="text-muted mb-0">Kelola nama bank penerima dana pembayaran kurban</p>
                        </div>
                        <div class="btn-group-responsive">
                            <a href="{{ route('admin.bank-penerima.create') }}" class="btn btn-custom shadow-sm">
                                <i class="fas fa-plus-circle fa-sm mr-2"></i>Tambah Bank
                            </a>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow mb-4">
                                <!-- Card Header -->
                                <!-- Card Body -->
                                <div class="card-body">
                                    @include('components.admin.message')

                                    <!-- Table Container -->
                                    <div class="table-responsive">
                                        <!-- Desktop Table View -->
<div class="d-none d-md-block">
    <div class="table-responsive shadow-sm rounded">

        <table class="table table-hover align-middle mb-0">

            <thead class="bg-light">
                <tr>
                    <th class="text-center" width="5%">
                        No
                    </th>

                    <th>
                        Nama Bank
                    </th>

                    <th>
                        Nomor Rekening
                    </th>

                    <th>
                        Atas Nama
                    </th>

                    <th class="text-center" width="15%">
                        Aksi
                    </th>
                </tr>
            </thead>

            <tbody>

                @forelse ($bank as $item)

                    <tr>

                        {{-- NOMOR --}}
                        <td class="text-center align-middle">
                            {{ $loop->iteration }}
                        </td>

                        {{-- NAMA BANK --}}
                        <td class="align-middle">

                            <span class="font-weight-bold text-dark">
                                {{ $item->nama_bank ?? '-' }}
                            </span>

                        </td>

                        {{-- NOMOR REKENING --}}
                        <td class="align-middle">

                            <span class="custom-badge badge-weight">
                                {{ $item->no_rek ?? '-' }}
                            </span>

                        </td>

                        {{-- ATAS NAMA --}}
                        <td class="align-middle">

                            <span class="text-primary font-weight-bold">
                                {{ $item->as_nama ?? '-' }}
                            </span>

                        </td>

                        {{-- AKSI --}}
                        <td class="text-center align-middle">

                            <x-action-buttons
                                :editRoute="route('admin.bank-penerima.edit', $item->id)"
                                :deleteRoute="route('admin.bank-penerima.destroy', $item->id)"
                            />

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">

                            <i class="fas fa-database fa-2x mb-3 d-block text-gray-300"></i>

                            Tidak ada data bank penerima.

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>
</div>
                                        <!-- Mobile Table View -->
                                        <div class="d-md-none">
                                            @forelse ($bank as $item)
                                                <div class="card mb-3">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <small class="text-muted d-block">No</small>
                                                                <strong>{{ $loop->iteration }}</strong>
                                                            </div>
                                                            <div class="col-6 text-right">
                                                                <small class="text-muted d-block">Nama Bank</small>
                                                                <strong>{{ $item->nama_bank ?? '-' }}</strong>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="mb-2">
                                                            <small class="text-muted d-block">Nomor Rekening</small>
                                                            <strong>{{ $item->no_rek ?? '-' }}</strong>
                                                        </div>
                                                        <div class="mb-2">
                                                            <small class="text-muted d-block">Atas Nama</small>
                                                            <strong
                                                                class="text-primary">{{ $item->as_nama ?? '-' }}</strong>
                                                        </div>
                                                        <div class="d-flex justify-content-center pt-2">
                                                            <form
                                                                action="{{ route('admin.ketersediaan-hewan.destroy', $item->id) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirmDelete()">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="action-btn btn-danger border-0 mx-2"
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
                                                    <i class="fas fa-database"></i>
                                                    <p class="mt-2 mb-0">Tidak ada data yang tersedia</p>
                                                </div>
                                            @endforelse
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
                        <span>Copyright &copy; Your Website 2021</span>
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

    <!-- Page level plugins -->
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>

    <script>
        // Script untuk responsivitas tambahan
        $(document).ready(function() {
            // Tambahkan label untuk responsive table pada layar sangat kecil
            function setupResponsiveTable() {
                if ($(window).width() <= 576) {
                    $('.table-custom td').each(function() {
                        var header = $(this).closest('table').find('th').eq($(this).index());
                        $(this).attr('data-label', header.text());
                    });
                }
            }

            // Panggil fungsi saat resize window
            $(window).resize(setupResponsiveTable);
            setupResponsiveTable();
        });
    </script>
</body>

</html>
