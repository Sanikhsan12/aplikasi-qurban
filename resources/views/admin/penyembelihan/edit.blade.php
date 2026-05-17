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
                            <h1>Upload Bukti Penyembelihan</h1>
                            <p>Perbarui status penyembelihan dengan mengupload dokumentasi</p>
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
                            <form id="uploadForm" action="{{ route('admin.penyembelihan.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                                @csrf

                                <!-- Order Selection -->
                                <div class="mobile-form-group">
                                    <label for="penyembelihan_id" class="mobile-form-label required">
                                        Pilih Nama Pemilik Hewan
                                    </label>
                                    <select name="penyembelihan_id" id="penyembelihan_id" 
                                            class="mobile-form-select @error('penyembelihan_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Nama Pemilik Hewan --</option>
                                        @foreach ($penyembelihans as $item)
                                            <option value="{{ $item->id }}" {{ old('penyembelihan_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->order->user->name ?? 'Tidak diketahui' }} - Order #{{ $item->order->id ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="mobile-form-help">
                                        Pilih pemilik hewan yang akan diupload dokumentasi penyembelihannya
                                    </div>
                                    @error('penyembelihan_id')
                                        <div class="mobile-error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- File Upload -->
                                <div class="mobile-form-group">
                                    <label for="dokumentasi_penyembelihan" class="mobile-form-label required">
                                        Dokumentasi Penyembelihan
                                    </label>
                                    <div class="file-input-container">
                                        <div class="file-input-wrapper">
                                            <input type="file" name="dokumentasi_penyembelihan" 
                                                   id="dokumentasi_penyembelihan" 
                                                   class="mobile-form-input file-input @error('dokumentasi_penyembelihan') is-invalid @enderror"
                                                   accept="image/*" 
                                                   required>
                                            <button type="button" class="camera-btn" onclick="openCameraModal()">
                                                <i class="fas fa-camera"></i> Kamera
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mobile-form-help">
                                        Anda dapat mengambil foto langsung dari kamera atau memilih dari file explorer. 
                                        Format yang diterima: JPG, PNG (maks. 5MB)
                                    </div>
                                    @error('dokumentasi_penyembelihan')
                                        <div class="mobile-error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Image Preview -->
                                <div class="image-preview-container">
                                    <img id="imagePreview" class="image-preview" alt="Preview gambar">
                                </div>

                                <!-- Form Status -->
                                <div class="mobile-form-status mb-3">
                                    <i class="fas fa-info-circle mobile-form-status-icon"></i>
                                    <span>Pastikan semua data telah diisi dengan benar sebelum menyimpan</span>
                                </div>

                                <!-- Form Footer -->
                                <div class="mobile-form-footer">                                    
                                    <div class="mobile-form-actions">
                                        <button type="button" class="mobile-btn mobile-btn-secondary" onclick="window.history.back()">
                                            <i class="fas fa-times mobile-btn-icon"></i>
                                            Batal
                                        </button>
                                        <button type="submit" class="mobile-btn mobile-btn-primary" id="submitBtn">
                                            <i class="fas fa-save mobile-btn-icon"></i>
                                            Upload Dokumentasi
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
    <div id="cameraModal" class="camera-modal">
        <div class="camera-container">
            <h4 class="mb-3">Ambil Foto dengan Kamera</h4>
            <video id="cameraStream" class="camera-video" autoplay playsinline></video>
            <div class="camera-controls">
                <button id="captureBtn" class="camera-capture-btn" title="Ambil Foto">
                    <i class="fas fa-camera"></i>
                </button>
                <button id="closeCameraBtn" class="camera-close-btn">
                    <i class="fas fa-times mr-2"></i>Tutup
                </button>
            </div>
            <canvas id="cameraCanvas" style="display: none;"></canvas>
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

    <script src="{{ asset('js/admin/penyembelihan/edit.js') }}"></script>

</body>
</html>