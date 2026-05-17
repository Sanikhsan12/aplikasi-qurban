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
                            <h1>Upload Bukti Distribusi</h1>
                            <p>Lakukan bukti pendistribusian daging kurban</p>
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
                            <form id="uploadForm" action="{{ route('admin.distribusi.store') }}" method="POST"
                                enctype="multipart/form-data" novalidate>
                                @csrf

                                <!-- Order Selection -->
                                <div class="mobile-form-group">
                                    <label for="order_id" class="mobile-form-label required">
                                        Pilih Tanggal Penyembelihan
                                    </label>

                                    <select name="pelaksanaan_id" class="mobile-form-select" required>
                                        <option value="">-- Pilih Pelaksanaan --</option>

                                        @forelse ($pelaksanaans as $pelaksanaan)
                                            <option value="{{ $pelaksanaan->id }}">
                                                {{ \Carbon\Carbon::parse($pelaksanaan->penyembelihan)->format('d M Y') }}
                                            </option>
                                        @empty
                                            <option value="" disabled>
                                                Tidak ada penyembelihan yang belum terdistribusi
                                            </option>
                                        @endforelse
                                    </select>


                                    <div class="mobile-form-help">
                                        Pilih waktu penyembelihan untuk distribusi ini
                                    </div>

                                    @error('order_id')
                                        <div class="mobile-error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Link Google Drive (Opsional) -->
                                <div class="mobile-form-group">
                                    <label for="link_gdrive" class="mobile-form-label required">
                                        Link Google Drive (WAJIB)
                                    </label>

                                    <input type="url" name="link_gdrive" id="link_gdrive"
                                        class="mobile-form-input @error('link_gdrive') is-invalid @enderror"
                                        placeholder="https://drive.google.com/..." value="{{ old('link_gdrive') }}">

                                    <div class="mobile-form-help">
                                        Upload semua foto dokumentasi pendistribusian pada google drive untuk menghemat
                                        penyimpanan aplikasi!
                                    </div>

                                    @error('link_gdrive')
                                        <div class="mobile-error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Multi File Upload -->
                                <div class="mobile-form-group">
                                    <label for="dokumentasi" class="mobile-form-label required">
                                        Dokumentasi Distribusi Daging
                                    </label>

                                    <div class="file-input-container">
                                        <div class="file-input-wrapper">
                                            <input type="file" name="dokumentasi[]" id="dokumentasi"
                                                class="mobile-form-input file-input @error('dokumentasi') is-invalid @enderror"
                                                accept="image/*" multiple required>
                                            <input type="hidden" name="camera_images" id="cameraImagesInput">

                                            <button type="button" class="camera-btn" id="openCameraBtn">
                                                <i class="fas fa-camera"></i> Kamera
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mobile-form-help">
                                        Anda dapat memilih lebih dari satu gambar (direkomendasikan 5 foto).
                                        Format: JPG, PNG, WEBP (maks. 5MB / file)
                                    </div>

                                    @error('dokumentasi')
                                        <div class="mobile-error-message">{{ $message }}</div>
                                    @enderror

                                    @error('dokumentasi.*')
                                        <div class="mobile-error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Image Preview Container -->
                                <div class="image-preview-container" id="previewContainer">
                                    <!-- Previews will be inserted here by JavaScript -->
                                </div>

                                <!-- Form Status -->
                                <div class="mobile-form-status mb-3">
                                    <i class="fas fa-info-circle mobile-form-status-icon"></i>
                                    <span>
                                        Pastikan seluruh dokumentasi telah sesuai sebelum disimpan. DATA TIDAK DAPAT
                                        DIPERBARUI SETELAH INI.
                                    </span>
                                </div>

                                <!-- Form Footer -->
                                <div class="mobile-form-footer">
                                    <div class="mobile-form-actions">
                                        <button type="button" class="mobile-btn mobile-btn-secondary"
                                            onclick="window.history.back()">
                                            <i class="fas fa-times mobile-btn-icon"></i>
                                            Batal
                                        </button>

                                        <button type="submit" class="mobile-btn mobile-btn-primary" id="submitBtn">
                                            <i class="fas fa-save mobile-btn-icon"></i>
                                            Simpan Distribusi
                                        </button>
                                    </div>
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
