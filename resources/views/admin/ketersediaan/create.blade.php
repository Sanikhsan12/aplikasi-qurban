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
                                <i class="fas fa-plus-circle text-primary mr-2"></i>Tambah Hewan Kurban
                            </h1>
                            <p class="text-muted mb-0">Isi form berikut untuk menambahkan data hewan kurban baru</p>
                        </div>
                        <div>
                            <a href="{{ route('admin.ketersediaan-hewan.index') }}" class="btn btn-secondary shadow-sm">
                                <i class="fas fa-arrow-left fa-sm mr-2"></i>Kembali ke Daftar
                            </a>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="info-card animate-fade-in">
                        <div class="row">
                            <div class="col-lg-1 text-center">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="col-lg-11">
                                <h5 class="font-weight-bold text-gray-800 mb-2">Informasi Penting</h5>
                                <p class="mb-2 text-gray-600">Pastikan data yang dimasukkan akurat dan sesuai dengan
                                    kondisi hewan sebenarnya.</p>
                                <ul class="text-gray-600 mb-0">
                                    <li>Bobot harus dalam satuan kilogram (kg)</li>
                                    <li>Harga dalam satuan Rupiah (Rp)</li>
                                    <li>Foto yang diupload maksimal 2MB dengan format JPG/PNG</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Form Container -->
                    <div class="row animate-fade-in">
                        <div class="col-lg-12">
                            <div class="card form-card">
                                <!-- Form Header -->
                                <div class="form-header">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <i class="fas fa-paw fa-2x text-white"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-weight-bold mb-1">Form Tambah Hewan Kurban</h4>
                                            <p class="mb-0 opacity-75">Lengkapi semua data berikut</p>
                                        </div>
                                    </div>
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

                                    <form id="animalForm" action="{{ route('admin.ketersediaan-hewan.store') }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <!-- Section 1: Jenis Hewan -->
                                        <div class="form-section">
                                            <h5 class="section-title">
                                                <i class="fas fa-tag"></i>Jenis Hewan
                                            </h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label required">
                                                        <i class="fas fa-cow"></i>Pilih Jenis Hewan
                                                    </label>

                                                    <!-- Jenis Hewan Selection -->
                                                    <div class="mb-3">
                                                        <div class="d-flex flex-wrap">
                                                            @foreach (['Sapi', 'Kambing', 'Domba', 'Kerbau', 'Unta'] as $jenis)
                                                                <div class="animal-type-badge @if (old('jenis_hewan') == $jenis) active @endif"
                                                                    data-value="{{ $jenis }}">
                                                                    @if ($jenis == 'Sapi')
                                                                        <i class="fas fa-cow mr-1"></i>
                                                                    @elseif($jenis == 'Kambing')
                                                                        <i class="fas fa-sheep mr-1"></i>
                                                                    @elseif($jenis == 'Domba')
                                                                        <i class="fas fa-sheep mr-1"></i>
                                                                    @elseif($jenis == 'Kerbau')
                                                                        <i class="fas fa-buffalo mr-1"></i>
                                                                    @elseif($jenis == 'Unta')
                                                                        <i class="fas fa-camel mr-1"></i>
                                                                    @endif
                                                                    {{ $jenis }}
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <input type="hidden" name="jenis_hewan" id="jenis_hewan"
                                                            value="{{ old('jenis_hewan') }}" required>
                                                        @error('jenis_hewan')
                                                            <div class="form-error">
                                                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>

                                                    <!-- Custom Jenis Hewan -->
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            <i class="fas fa-edit"></i>Atau Tulis Sendiri
                                                        </label>
                                                        <input type="text" class="form-control form-control-custom"
                                                            id="jenis_hewan_custom"
                                                            placeholder="Contoh: Kambing Jawa, Sapi Limosin, dll."
                                                            value="{{ old('jenis_hewan') }}">
                                                        <div class="form-help">Kosongkan jika sudah memilih jenis di
                                                            atas</div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label required">
                                                            <i class="fas fa-hashtag"></i>Jumlah Hewan
                                                        </label>
                                                        <div class="input-group input-group-custom">
                                                            <input type="number" name="jumlah"
                                                                class="form-control form-control-custom"
                                                                value="{{ old('jumlah') }}" min="1"
                                                                max="1000" required placeholder="Contoh: 5">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">ekor</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-help">Jumlah minimal 1 ekor</div>
                                                        @error('jumlah')
                                                            <div class="form-error">
                                                                <i
                                                                    class="fas fa-exclamation-circle"></i>{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Section 2: Spesifikasi Hewan -->
                                        <div class="form-section">
                                            <h5 class="section-title">
                                                <i class="fas fa-weight"></i>Spesifikasi Hewan
                                            </h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label required">
                                                            <i class="fas fa-weight-hanging"></i>Bobot Hewan
                                                        </label>
                                                        <div class="input-group input-group-custom">
                                                            <input type="number" name="bobot"
                                                                class="form-control form-control-custom"
                                                                value="{{ old('bobot') }}" step="0.1"
                                                                min="0" max="1000" required
                                                                placeholder="Contoh: 150.5">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">kg</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-help">Bobot dalam kilogram (min 0 kg, max 1000
                                                            kg)</div>
                                                        @error('bobot')
                                                            <div class="form-error">
                                                                <i
                                                                    class="fas fa-exclamation-circle"></i>{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label required">
                                                            <i class="fas fa-money-bill-wave"></i>Harga per Ekor
                                                        </label>
                                                        <div class="input-group input-group-custom">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Rp</span>
                                                            </div>
                                                            <input type="number" name="harga"
                                                                class="form-control form-control-custom"
                                                                id="harga_input" value="{{ old('harga') }}"
                                                                min="0" required
                                                                placeholder="Contoh: 25000000">
                                                        </div>
                                                        <div class="form-help">
                                                            Harga satuan: <span id="harga_formatted"
                                                                class="font-weight-bold text-success">Rp 0</span>
                                                        </div>
                                                        @error('harga')
                                                            <div class="form-error">
                                                                <i
                                                                    class="fas fa-exclamation-circle"></i>{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Section 3: Foto Hewan -->
                                        <div class="form-section mb-5">
                                            <h5 class="section-title">
                                                <i class="fas fa-camera"></i>Foto Hewan
                                            </h5>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <label class="form-label">
                                                        <i class="fas fa-image"></i>Upload Foto Hewan
                                                    </label>

                                                    <!-- Photo Upload Area - DIPERBAIKI -->
                                                    <div class="photo-upload-area" id="photoUploadArea">
                                                        <div class="photo-preview-container"
                                                            id="photoPreviewContainer">
                                                            <img src="" alt="Preview" class="photo-preview"
                                                                id="photoPreview">
                                                            <button type="button" class="photo-remove-btn"
                                                                id="photoRemoveBtn">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>

                                                        <div id="uploadPlaceholder">
                                                            <div class="upload-icon">
                                                                <i class="fas fa-cloud-upload-alt"></i>
                                                            </div>
                                                            <div class="upload-text font-weight-bold">
                                                                Klik atau tarik file ke sini
                                                            </div>
                                                            <div class="upload-hint">
                                                                Format: JPG, PNG, GIF (Maks. 2MB)
                                                            </div>

                                                            <!-- TAMBAHKAN TOMBOL BROWSE YANG JELAS -->
                                                            <div class="mt-3">
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input"
                                                                        id="foto_traditional" name="foto"
                                                                        accept="image/*"
                                                                        onchange="previewTraditionalFile(this)">
                                                                    <label class="custom-file-label"
                                                                        for="foto_traditional" id="fileLabel">
                                                                        <i class="fas fa-upload mr-2"></i>Pilih file
                                                                        foto...
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Input file TIDAK di-hide sepenuhnya -->
                                                        <input type="file" name="foto" id="foto_input"
                                                            class="d-none" accept="image/*">
                                                    </div>

                                                    @error('foto')
                                                        <div class="form-error mt-2">
                                                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                    </form>
                                </div>

                                <!-- Form Footer -->
                                <div class="form-footer">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <a href="{{ route('admin.ketersediaan-hewan.index') }}"
                                                class="back-link">
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

    <script>
        window.formOldData = {
            jenis_hewan: "{{ old('jenis_hewan') }}",
            harga: "{{ old('harga') }}"
        };
    </script>

    <!-- Custom Scripts for this page -->
    <script>
        // Tambahkan di script section

        // Tombol browse untuk drag & drop area
        $('#browseButton').click(function() {
            $('#foto_input').click();
        });

        // Handle traditional file input
        function previewTraditionalFile(input) {
            const fileLabel = $('#fileLabel');

            if (input.files && input.files[0]) {
                const file = input.files[0];

                // Update label
                fileLabel.html('<i class="fas fa-file-image mr-2"></i>' + file.name);

                // Juga update drag & drop area
                handleFile(file);

                // Juga set ke hidden input untuk drag & drop
                $('#foto_input').prop('files', input.files);
            } else {
                fileLabel.html('<i class="fas fa-upload mr-2"></i>Pilih file foto...');
            }
        }

        // Function untuk handle file (sama untuk kedua metode)
        function handleFile(file) {
            // Check file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB');
                return false;
            }

            // Check file type
            if (!file.type.match('image.*')) {
                alert('Hanya file gambar yang diperbolehkan');
                return false;
            }

            // Create preview
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#photoPreview').attr('src', e.target.result);
                $('#uploadPlaceholder').hide();
                $('#photoPreview').addClass('active');
                $('#photoPreviewContainer').show();
            }
            reader.readAsDataURL(file);

            return true;
        }

        // Juga perbaiki event handler untuk drag & drop area
        $('#foto_input').change(function(e) {
            if (this.files && this.files[0]) {
                handleFile(this.files[0]);
            }
        });

        // selesai
        $(document).ready(function() {
            // Animal type selection
            $('.animal-type-badge').click(function() {
                const value = $(this).data('value');

                // Update active state
                $('.animal-type-badge').removeClass('active');
                $(this).addClass('active');

                // Update hidden input and clear custom input
                $('#jenis_hewan').val(value);
                $('#jenis_hewan_custom').val('');
            });

            // Custom jenis hewan input
            $('#jenis_hewan_custom').on('input', function() {
                const value = $(this).val().trim();
                if (value) {
                    $('.animal-type-badge').removeClass('active');
                    $('#jenis_hewan').val(value);
                }
            });

            // Format harga input
            $('#harga_input').on('input', function() {
                const harga = $(this).val();
                if (harga) {
                    const formatted = 'Rp ' + parseInt(harga).toLocaleString('id-ID');
                    $('#harga_formatted').text(formatted);
                    calculateTotals();
                } else {
                    $('#harga_formatted').text('Rp 0');
                    calculateTotals();
                }
            });

            // Calculate totals
            function calculateTotals() {
                const harga = $('#harga_input').val() || 0;
                const jumlah = $('input[name="jumlah"]').val() || 0;
                const bobot = $('input[name="bobot"]').val() || 0;

                // Total value
                const totalValue = harga * jumlah;
                $('#total_value').text('Rp ' + totalValue.toLocaleString('id-ID'));

                // Estimated meat (40% of weight)
                const estimatedMeat = (bobot * jumlah * 0.4).toFixed(1);
                $('#estimated_meat').text(estimatedMeat + ' kg');
            }

            // Update totals when inputs change
            $('input[name="jumlah"], input[name="bobot"]').on('input', calculateTotals);

            // Photo upload functionality
            const photoUploadArea = $('#photoUploadArea');
            const fotoInput = $('#foto_input');
            const photoPreview = $('#photoPreview');
            const uploadPlaceholder = $('#uploadPlaceholder');
            const photoPreviewContainer = $('#photoPreviewContainer');

            // Click to upload
            photoUploadArea.click(function() {
                fotoInput.click();
            });

            // Drag and drop
            photoUploadArea.on('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).addClass('dragover');
            });

            photoUploadArea.on('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('dragover');
            });

            photoUploadArea.on('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('dragover');

                const files = e.originalEvent.dataTransfer.files;
                if (files.length > 0) {
                    handleFile(files[0]);
                }
            });

            // File input change
            fotoInput.change(function(e) {
                if (this.files && this.files[0]) {
                    handleFile(this.files[0]);
                }
            });

            // Handle file selection
            function handleFile(file) {
                // Check file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB');
                    return;
                }

                // Check file type
                if (!file.type.match('image.*')) {
                    alert('Hanya file gambar yang diperbolehkan');
                    return;
                }

                // Create preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.attr('src', e.target.result);
                    uploadPlaceholder.hide();
                    photoPreview.addClass('active');
                    photoPreviewContainer.show();
                }
                reader.readAsDataURL(file);
            }

            // Remove photo
            $('#photoRemoveBtn').click(function(e) {
                e.stopPropagation();
                fotoInput.val('');
                photoPreview.attr('src', '');
                photoPreview.removeClass('active');
                photoPreviewContainer.hide();
                uploadPlaceholder.show();
            });

            // Form validation and submission
            $('#animalForm').submit(function(e) {
                // Basic validation
                const jenisHewan = $('#jenis_hewan').val();
                if (!jenisHewan) {
                    alert('Pilih jenis hewan terlebih dahulu');
                    e.preventDefault();
                    return;
                }

                // Show loading overlay
                $('#loadingOverlay').show();

                // Disable submit button
                $('#submitBtn').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...');
            });

            // Reset form
            $('button[type="reset"]').click(function() {
                // Reset active badges
                $('.animal-type-badge').removeClass('active');

                // Reset photo preview
                fotoInput.val('');
                photoPreview.attr('src', '');
                photoPreview.removeClass('active');
                photoPreviewContainer.hide();
                uploadPlaceholder.show();

                // Reset calculations
                $('#harga_formatted').text('Rp 0');
                $('#total_value').text('Rp 0');
                $('#estimated_meat').text('0 kg');

                // Enable submit button if it was disabled
                $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save mr-2"></i>Simpan Data');
            });

            $(document).ready(function() {

                // Set initial values from old input
                if (window.formOldData && window.formOldData.jenis_hewan) {
                    const oldJenis = window.formOldData.jenis_hewan;

                    $('.animal-type-badge').each(function() {
                        if ($(this).data('value') === oldJenis) {
                            $(this).addClass('active');
                            $('#jenis_hewan').val(oldJenis);
                        }
                    });
                }

                if (window.formOldData && window.formOldData.harga) {
                    $('#harga_input').trigger('input');
                }

                // Auto-hide alerts after 5 seconds
                setTimeout(function() {
                    $('.alert').alert('close');
                }, 5000);

            });


            // Input validation
            $('input[type="number"]').on('input', function() {
                const min = $(this).attr('min');
                const max = $(this).attr('max');
                const value = parseFloat($(this).val());

                if (min && value < min) {
                    $(this).val(min);
                }

                if (max && value > max) {
                    $(this).val(max);
                }
            });

            // Add animation to form sections on scroll
            function animateOnScroll() {
                $('.form-section').each(function() {
                    const elementTop = $(this).offset().top;
                    const elementBottom = elementTop + $(this).outerHeight();
                    const viewportTop = $(window).scrollTop();
                    const viewportBottom = viewportTop + $(window).height();

                    if (elementBottom > viewportTop && elementTop < viewportBottom) {
                        $(this).css({
                            'opacity': '1',
                            'transform': 'translateY(0)'
                        });
                    }
                });
            }

            // Initial animation
            animateOnScroll();
            $(window).scroll(animateOnScroll);
        });
    </script>
</body>

</html>
