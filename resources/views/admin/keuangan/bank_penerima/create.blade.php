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
                    <!-- Mobile-friendly form container -->
                    <div class="mobile-form-container fade-in">
                        <!-- Form Header -->
                        <div class="mobile-form-header">
                            <h1>Tambah Bank Penerima</h1>
                            <p>Tambah bank penerima pembayaran kurban</p>
                        </div>

                        <!-- Form Body -->
                        <div class="mobile-form-body">
                            <!-- Error Messages -->
                            @if ($errors->any())
                                <div class="mobile-alert mobile-alert-error">
                                    <div class="mobile-alert-content">
                                        <i class="fas fa-exclamation-circle mobile-alert-icon"></i>
                                        <div>
                                            <strong>Perhatian!</strong>
                                            Terdapat kesalahan dalam pengisian form:
                                            <ul class="mt-2 mb-0 pl-4">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Success Message -->
                            @if (session('success'))
                                <div class="mobile-alert mobile-alert-success">
                                    <div class="mobile-alert-content">
                                        <i class="fas fa-check-circle mobile-alert-icon"></i>
                                        <div>{{ session('success') }}</div>
                                    </div>
                                </div>
                            @endif

                            <!-- Edit Form -->
                            <form action="{{ route('admin.bank-penerima.store') }}" method="POST" id="bankForm">
                                @csrf

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="nama_bank" class="form-label">Nama Bank <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('nama_bank') is-invalid @enderror"
                                                id="nama_bank" name="nama_bank" value="{{ old('nama_bank') }}"
                                                placeholder="Contoh: BCA, Mandiri, BRI, dll">
                                            @error('nama_bank')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="no_rek" class="form-label">Nomor Rekening <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('no_rek') is-invalid @enderror"
                                                id="no_rek" name="no_rek" value="{{ old('no_rek') }}"
                                                placeholder="Contoh: 1234567890">
                                            @error('no_rek')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Nomor rekening harus unik</small>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="as_nama" class="form-label">Nama Pemilik Rekening <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('as_nama') is-invalid @enderror"
                                                id="as_nama" name="as_nama" value="{{ old('as_nama') }}"
                                                placeholder="Nama lengkap pemilik rekening">
                                            @error('as_nama')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mobile-form-status mb-3">
                                    <i class="fas fa-info-circle mobile-form-status-icon"></i>
                                    <span>
                                        Pastikan seluruh data telah sesuai sebelum disimpan. DATA TIDAK DAPAT
                                        DIPERBARUI SETELAH INI.
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('admin.bank-penerima.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-save me-1"></i> Simpan Data
                                    </button>
                                </div>
                            </form>
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

    <!-- Camera Modal -->
    <div id="cameraModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ambil Foto dengan Kamera</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <video id="cameraStream" class="camera-video" autoplay playsinline></video>
                    <canvas id="cameraCanvas" style="display: none;"></canvas>
                    <div id="capturedPreview" class="mt-3" style="display: none;">
                        <h6>Foto yang diambil:</h6>
                        <img id="capturedImage" class="img-fluid rounded" src="" alt="Captured Photo">
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button id="captureBtn" class="btn btn-primary">
                        <i class="fas fa-camera mr-2"></i>Ambil Foto
                    </button>
                    <button id="retakeBtn" class="btn btn-secondary" style="display: none;">
                        <i class="fas fa-redo mr-2"></i>Ambil Ulang
                    </button>
                    <button id="usePhotoBtn" class="btn btn-success" style="display: none;">
                        <i class="fas fa-check mr-2"></i>Gunakan Foto
                    </button>
                    <button id="closeCameraBtn" class="btn btn-danger" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal -->
    @include('components.admin.logout')

    <!-- Bootstrap & jQuery Scripts -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <script src="{{ asset('js/admin/distribusi/create.js') }}"></script>

</body>

</html>
