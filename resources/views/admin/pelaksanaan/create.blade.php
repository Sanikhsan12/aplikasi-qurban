<!DOCTYPE html>
<html lang="en">

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


    {{-- css kostum --}}
    <link href="{{ asset('css/admin/pelaksanaan/create.css') }}" rel="stylesheet">
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
            <div id="content" >

                <!-- Topbar -->
                @include('components.admin.topbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Tambah Pelaksanaan Kurban</h1>
                    </div>

                    <div class="">
                        <!-- Area Chart -->
                        <div class="">
                            <div class="card shadow mb-4">
                                <div class="form-body">
                                    @if ($errors->any())
                                        <div class="alert alert-error">
                                            <ul style="margin-left: 20px;">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    @if (session('error'))
                                        <div class="alert alert-error">
                                            {{ session('error') }}
                                        </div>
                                    @endif

                                    <form action="{{ route('admin.pelaksanaan.store') }}" method="POST"
                                        id="pelaksanaanForm">
                                        @csrf

                                        <div class="form-row">
                                            <div class="form-col">
                                                <div class="form-group">
                                                    <label for="Tanggal_Pendaftaran" class="required">Tanggal
                                                        Pendaftaran</label>
                                                    <input type="date" id="Tanggal_Pendaftaran"
                                                        name="Tanggal_Pendaftaran"
                                                        value="{{ old('Tanggal_Pendaftaran') }}" required>
                                                    <div class="form-help">
                                                        Tanggal dimulainya pendaftaran peserta
                                                    </div>
                                                    @error('Tanggal_Pendaftaran')
                                                        <div class="error-message">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-col">
                                                <div class="form-group">
                                                    <label for="Tanggal_Penutupan" class="required">Tanggal
                                                        Penutupan</label>
                                                    <input type="date" id="Tanggal_Penutupan"
                                                        name="Tanggal_Penutupan" value="{{ old('Tanggal_Penutupan') }}"
                                                        required>
                                                    <div class="form-help">
                                                        Tanggal terakhir pendaftaran
                                                    </div>
                                                    @error('Tanggal_Penutupan')
                                                        <div class="error-message">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="Ketuplak" class="required">Nama Ketuplak</label>
                                            <input type="text" id="Ketuplak" name="Ketuplak"
                                                value="{{ old('Ketuplak') }}" placeholder="Masukkan nama ketuplak"
                                                required>
                                            <div class="form-help">
                                                Nama penanggung jawab pelaksanaan
                                            </div>
                                            @error('Ketuplak')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="Lokasi" class="required">Lokasi Pelaksanaan</label>
                                            <input type="text" id="Lokasi" name="Lokasi"
                                                value="{{ old('Lokasi') }}" placeholder="Masukkan lokasi pelaksanaan"
                                                required>
                                            <div class="form-help">
                                                Tempat dilaksanakannya kegiatan
                                            </div>
                                            @error('Lokasi')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="Penyembelihan" class="required">Tanggal Penyembelihan</label>
                                            <input type="date" id="Penyembelihan" name="Penyembelihan"
                                                value="{{ old('Penyembelihan') }}" required>
                                            <div class="form-help">
                                                Tanggal pelaksanaan penyembelihan
                                            </div>
                                            @error('Penyembelihan')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </form>
                                </div>

                                <div class="form-footer">
                                    <div class="form-actions"> 
                                        <a href="{{ route('admin.pelaksanaan.index') }}" class="btn btn-secondary">
                                            Reset From
                                        </a>
                                    </div>
                                    <div class="form-actions">
                                        
                                        <button type="submit" form="pelaksanaanForm" class="btn btn-primary">
                                            Simpan Data
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
                        <div class="">

                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">

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
    </div>

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
</body>

</html>
