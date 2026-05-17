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
                    <!-- Loading Overlay -->
                    <div class="loading-overlay" id="loadingOverlay" style="display: none;">
                        <div class="spinner-custom"></div>
                        <div class="mt-3 text-primary font-weight-bold">Menyimpan perubahan...</div>
                    </div>

                    <!-- Messages -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-custom fade-in">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle fa-lg mr-3"></i>
                                <div>
                                    <strong>Perhatian!</strong> Terdapat kesalahan dalam pengisian form.
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <button type="button" class="close ml-auto" data-dismiss="alert">
                                    <span>&times;</span>
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
                                <button type="button" class="close ml-auto" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Form Edit -->
                    <form action="{{ route('admin.ketersediaan-hewan.update', $hewan->id) }}" method="POST"
                        enctype="multipart/form-data" id="editForm" class="fade-in">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Left Column - Form Inputs -->
                            <div class="col-lg-8">
                                <!-- Informasi Dasar -->
                                <div class="form-section">
                                    <h3 class="form-section-title">
                                        <i class="fas fa-info-circle"></i>Informasi Dasar
                                    </h3>

                                    <div class="info-box">
                                        <i class="fas fa-lightbulb"></i>
                                        <span>Isi semua informasi dasar hewan kurban dengan lengkap dan benar.</span>
                                    </div>

                                    <div class="form-group-custom">
                                        <label for="jenis_hewan" class="form-label required-field">Jenis Hewan</label>
                                        <select name="jenis_hewan" id="jenis_hewan"
                                            class="form-control form-control-custom" required>
                                            <option value="">Pilih Jenis Hewan</option>
                                            <option value="{{ $hewan->jenis_hewan }}" selected>{{ $hewan->jenis_hewan }}</option>
                                            <option value="Sapi"
                                                {{ old('jenis_hewan', $hewan->jenis_hewan) == 'Sapi' ? 'selected' : '' }}>
                                                Sapi</option>
                                            <option value="Kambing"
                                                {{ old('jenis_hewan', $hewan->jenis_hewan) == 'Kambing' ? 'selected' : '' }}>
                                                Kambing</option>
                                            <option value="Domba"
                                                {{ old('jenis_hewan', $hewan->jenis_hewan) == 'Domba' ? 'selected' : '' }}>
                                                Domba</option>
                                            <option value="Unta"
                                                {{ old('jenis_hewan', $hewan->jenis_hewan) == 'Unta' ? 'selected' : '' }}>
                                                Unta</option>
                                        </select>
                                        @error('jenis_hewan')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror
                                        <div class="hint-text">Pilih jenis hewan kurban yang sesuai</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group-custom">
                                                <label for="bobot" class="form-label required-field">Bobot
                                                    (Kg)</label>
                                                <input type="number" name="bobot" id="bobot"
                                                    class="form-control form-control-custom"
                                                    value="{{ old('bobot', $hewan->bobot) }}" min="1"
                                                    max="1000" step="0.1" required>
                                                @error('bobot')
                                                    <div class="error-message">{{ $message }}</div>
                                                @enderror
                                                <div class="hint-text">Masukkan bobot dalam kilogram</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group-custom">
                                                <label for="jumlah" class="form-label required-field">Jumlah
                                                    (Ekor)</label>
                                                <input type="number" name="jumlah" id="jumlah"
                                                    class="form-control form-control-custom"
                                                    value="{{ old('jumlah', $hewan->jumlah) }}" min="1"
                                                    max="1000" required>
                                                @error('jumlah')
                                                    <div class="error-message">{{ $message }}</div>
                                                @enderror
                                                <div class="hint-text">Masukkan jumlah hewan yang tersedia</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group-custom">
                                        <label for="harga" class="form-label required-field">Harga per Ekor
                                            (Rp)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light">Rp</span>
                                            </div>
                                            <input type="text" name="harga" id="harga"
                                                class="form-control form-control-custom"
                                                value="{{ old('harga', number_format($hewan->harga, 0, ',', '.')) }}"
                                                required>
                                        </div>
                                        @error('harga')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror
                                        <div class="hint-text">Masukkan harga tanpa titik atau koma</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column - Foto & Status -->
                            <div class="col-lg-4">
                                <!-- Foto Hewan -->
                                <div class="form-section">
                                    <h3 class="form-section-title">
                                        <i class="fas fa-camera"></i>Foto Hewan
                                    </h3>

                                    <div class="preview-image-container" id="imagePreviewContainer">
                                        @if ($hewan->foto)
                                            <div class="current-image">
                                                <button type="button" class="remove-image-btn" id="removeImageBtn">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <img src="{{ $hewan->foto_url }}" alt="Preview"
                                                    class="image-preview" id="imagePreview">
                                                <input type="hidden" name="current_foto"
                                                    value="{{ $hewan->foto }}">
                                            </div>
                                            <p class="text-success mb-2">
                                                <i class="fas fa-check-circle"></i> Foto tersedia
                                            </p>
                                        @else
                                            <div class="upload-icon">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                            </div>
                                            <p class="text-muted mb-2">Belum ada foto</p>
                                        @endif

                                        <label for="foto" class="btn btn-outline-primary cursor-pointer">
                                            <i class="fas fa-upload mr-2"></i>Upload Foto Baru
                                            <input type="file" name="foto" id="foto" class="d-none"
                                                accept="image/*">
                                        </label>

                                        <div class="hint-text mt-2">
                                            Ukuran maksimal: 5MB<br>
                                            Format: JPG, PNG, JPEG
                                        </div>
                                        @error('foto')
                                            <div class="error-message mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-section mt-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('admin.ketersediaan-hewan.index') }}"
                                        class="btn btn-cancel btn-lg">
                                        <i class="fas fa-times mr-2"></i>Batal
                                    </a>
                                </div>
                                <div class="btn-group-responsive">
                                    <button type="button" class="btn btn-warning btn-lg mr-2" onclick="resetForm()">
                                        <i class="fas fa-redo mr-2"></i>Reset
                                    </button>
                                    <button type="submit" class="btn btn-custom btn-lg" id="submitBtn">
                                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
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

    <!-- File Upload -->
    <script src="https://cdn.jsdelivr.net/npm/filepond@4.30.4/dist/filepond.min.js"></script>

    <script>
        // Format currency input
        document.getElementById('harga').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            if (value) {
                e.target.value = formatRupiah(value);
            }
            calculateTotal();
        });

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        // Calculate total value
        function calculateTotal() {
            const harga = document.getElementById('harga').value.replace(/[^\d]/g, '') || 0;
            const jumlah = document.getElementById('jumlah').value || 0;
            const total = harga * jumlah;

            document.getElementById('totalNilai').textContent = 'Rp ' + formatRupiah(total);
        }

        // Attach calculation events
        document.getElementById('harga').addEventListener('input', calculateTotal);
        document.getElementById('jumlah').addEventListener('input', calculateTotal);

        // Image preview
        document.getElementById('foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imagePreview');
            const container = document.getElementById('imagePreviewContainer');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (!preview) {
                        // Create new image preview
                        const img = document.createElement('img');
                        img.id = 'imagePreview';
                        img.className = 'image-preview';
                        img.src = e.target.result;
                        img.alt = 'Preview';

                        // Create remove button
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'remove-image-btn';
                        removeBtn.id = 'removeImageBtn';
                        removeBtn.innerHTML = '<i class="fas fa-times"></i>';

                        // Create wrapper
                        const wrapper = document.createElement('div');
                        wrapper.className = 'current-image';
                        wrapper.appendChild(removeBtn);
                        wrapper.appendChild(img);

                        // Clear container and add new content
                        container.innerHTML = '';
                        container.appendChild(wrapper);

                        // Add upload button again
                        const uploadBtn = document.createElement('label');
                        uploadBtn.className = 'btn btn-outline-primary cursor-pointer mt-2';
                        uploadBtn.innerHTML = '<i class="fas fa-upload mr-2"></i>Ganti Foto';
                        uploadBtn.htmlFor = 'foto';

                        container.appendChild(uploadBtn);

                        // Add hint text
                        const hint = document.createElement('div');
                        hint.className = 'hint-text mt-2';
                        hint.innerHTML = 'Ukuran maksimal: 5MB<br>Format: JPG, PNG, JPEG';
                        container.appendChild(hint);

                        // Add event listener to remove button
                        document.getElementById('removeImageBtn').addEventListener('click', removeImage);
                    } else {
                        preview.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        // Remove image function
        function removeImage() {
            const fotoInput = document.getElementById('foto');
            const removeFotoInput = document.getElementById('remove_foto');

            if (fotoInput) {
                fotoInput.value = '';
            }

            if (removeFotoInput) {
                removeFotoInput.value = '1';
            }

            const container = document.getElementById('imagePreviewContainer');
            if (container) {
                container.innerHTML = `
            <div class="upload-icon">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <p class="text-muted mb-2">Foto akan dihapus</p>
            <label for="foto" class="btn btn-outline-primary cursor-pointer">
                <i class="fas fa-upload mr-2"></i>Upload Foto Baru
                <input type="file" name="foto" id="foto" class="d-none" accept="image/*">
            </label>
            <div class="hint-text mt-2">
                Ukuran maksimal: 5MB<br>
                Format: JPG, PNG, JPEG
            </div>
        `;

                // Re-attach event listener
                document.getElementById('foto').addEventListener('change', handleImageUpload);
            }
        }

        // Attach remove image event if button exists
        if (document.getElementById('removeImageBtn')) {
            document.getElementById('removeImageBtn').addEventListener('click', removeImage);
        }

        // Form submission
        document.getElementById('editForm').addEventListener('submit', function(e) {
            // Format harga before submission
            const hargaInput = document.getElementById('harga');
            hargaInput.value = hargaInput.value.replace(/[^\d]/g, '');

            // Show loading
            document.getElementById('loadingOverlay').style.display = 'flex';
            document.getElementById('submitBtn').disabled = true;
        });

        // Reset form function
        function resetForm() {
            if (confirm('Apakah Anda yakin ingin mengembalikan semua perubahan?')) {
                document.getElementById('editForm').reset();
                calculateTotal();

                // Reset image preview if it was changed
                if (!document.getElementById('imagePreview') && {{ $hewan->foto ? 'true' : 'false' }}) {
                    // Reload page to reset everything
                    window.location.reload();
                }
            }
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);

        // Initialize tooltips
        $(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });

        // Initialize FilePond if needed
        if (typeof FilePond !== 'undefined') {
            FilePond.parse(document.body);
        }

        // Form validation
        function validateForm() {
            const jenisHewan = document.getElementById('jenis_hewan').value;
            const bobot = document.getElementById('bobot').value;
            const jumlah = document.getElementById('jumlah').value;
            const harga = document.getElementById('harga').value;

            if (!jenisHewan || !bobot || !jumlah || !harga) {
                alert('Mohon lengkapi semua field yang wajib diisi!');
                return false;
            }

            return true;
        }
    </script>
</body>

</html>
