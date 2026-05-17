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


    {{-- css kostum --}}
    <link href="{{ asset('css/admin/ketersediaan/create.css') }}" rel="stylesheet">
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
                    <!-- Loading Overlay -->
                    <div class="loading-overlay" id="loadingOverlay">
                        <div class="loading-spinner"></div>
                        <div class="text-primary font-weight-bold">Menyimpan Data...</div>
                    </div>

                    <!-- Page Header -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4 animate-fade-in">
                        <div>
                            <h1 class="h3 mb-0 text-gray-800">
                                <i class="fas fa-plus-circle text-primary mr-2"></i>Tambah Kas Kurban
                            </h1>
                            <p class="text-muted mb-0">Isi form berikut untuk menambahkan kas keuangan kegiatan kurban
                            </p>
                        </div>
                    </div>

                    <!-- Form Container -->
                    <div class="row animate-fade-in">
                        <div class="col-lg-12">
                            <div class="card form-card">
                                <!-- Form Header -->
                                <div class="form-header">
                                    {{-- <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <i class="fas fa-paw fa-2x text-white"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-weight-bold mb-1">Form Tambah Kas Kurban</h4>
                                            <p class="mb-0 opacity-75">Lengkapi semua data berikut</p>
                                        </div>
                                    </div> --}}
                                </div>

                                <!-- Form Body -->
                                <div class="form-body">
                                    <!-- Error Messages -->
                                    @if ($errors->any())
                                        <div class="alert alert-danger alert-custom mb-4">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-exclamation-triangle fa-lg mr-3 text-danger"></i>
                                                <div>
                                                    <strong class="d-block">Terdapat kesalahan dalam pengisian
                                                        form:</strong>
                                                    <ul class="mb-0 mt-2 pl-3">
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (session('success'))
                                        <div class="alert alert-success alert-custom mb-4">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-check-circle fa-lg mr-3 text-success"></i>
                                                <div>
                                                    <strong>Sukses!</strong> {{ session('success') }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (session('error'))
                                        <div class="alert alert-danger alert-custom mb-4">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-times-circle fa-lg mr-3 text-danger"></i>
                                                <div>
                                                    <strong>Error!</strong> {{ session('error') }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <form id="animalForm" action="{{ route('admin.dana-operasional.store') }}" method="POST">
                                        @csrf

                                        <!-- Sumber Dana -->
                                        <div class="mb-3">
                                            <label class="form-label required">Sumber Dana</label>
                                            <select name="id_dkm" class="form-control" required>
                                                <option value="">-- Pilih Sumber Dana --</option>
                                                @foreach ($danaDkm as $dkm)
                                                    <option value="{{ $dkm->id }}"
                                                        {{ old('id_dkm') == $dkm->id ? 'selected' : '' }}>
                                                        {{ $dkm->sumber_dana }}
                                                        (Saldo: Rp {{ number_format($dkm->jumlah_dana, 0, ',', '.') }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_dkm')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Keperluan -->
                                        <div class="mb-3">
                                            <label class="form-label required">Keperluan</label>
                                            <input type="text" name="keperluan" class="form-control"
                                                value="{{ old('keperluan') }}" required>
                                            @error('keperluan')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Jumlah Pengeluaran -->
                                        <div class="mb-3">
                                            <label class="form-label required">Jumlah Pengeluaran</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" name="jumlah_pengeluaran" class="form-control"
                                                    min="0" value="{{ old('jumlah_pengeluaran') }}" required>
                                            </div>
                                            @error('jumlah_pengeluaran')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Keterangan -->
                                        <div class="mb-3">
                                            <label class="form-label">Keterangan</label>
                                            <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                                            @error('keterangan')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </form>

                                </div>

                                <!-- Form Footer -->
                                <div class="form-footer">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <a href="{{ route('admin.ketersediaan-hewan.index') }}" class="back-link">
                                                <i class="fas fa-arrow-left mr-1"></i>Kembali ke Daftar
                                            </a>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="reset" form="animalForm" class="btn btn-reset">
                                                <i class="fas fa-redo mr-2"></i>Reset Form
                                            </button>
                                            <button type="submit" form="animalForm" class="btn btn-submit"
                                                id="submitBtn">
                                                <i class="fas fa-save mr-2"></i>Simpan Data
                                            </button>
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

    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap & jQuery Scripts -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

</body>

</html>
