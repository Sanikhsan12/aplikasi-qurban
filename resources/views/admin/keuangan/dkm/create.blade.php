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

                                    <form id="animalForm" action="{{ route('admin.dana-dkm.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <!-- Section 1: Jenis Hewan -->
                                        <div class="form-section">
                                            <div class="row">
                                                <div class="col-md-6">

                                                    <div class="mb-3">
                                                        <label class="form-label required">
                                                            <i class="fas fa-cow"></i>Sumber Dana
                                                        </label>
                                                        <input type="text" class="form-control form-control-custom"
                                                            id="sumber_dana" name="sumber_dana" required
                                                            placeholder="Contoh: Kas DKM, Kas Zakat."
                                                            value="{{ old('sumber_dana') }}">
                                                        <div class="form-help">Sumber dana berasal untuk kegiatan
                                                            kurban
                                                            misal Kas DKM</div>
                                                        @error('sumber_dana')
                                                            <div class="form-error">
                                                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>

                                                    <!-- sumber dana -->
                                                    <div class="mb-3">
                                                        <label class="form-label required">
                                                            <i class=""></i>Nominal Dana
                                                        </label>
                                                        <div class="input-group input-group-custom">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Rp</span>
                                                            </div>
                                                            <input type="number" name="jumlah_dana"
                                                                class="form-control form-control-custom"
                                                                id="jumlah_dana" value="{{ old('jumlah_dana') }}"
                                                                min="0" required placeholder="Contoh: 25000000">
                                                        </div>
                                                        <div class="form-help">
                                                            <span id="harga_formatted"
                                                                class="font-weight-bold text-success"> Rp 0</span>
                                                        </div>
                                                        @error('jumlah_dana')
                                                            <div class="form-error">
                                                                <i
                                                                    class="fas fa-exclamation-circle"></i>{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>


                                                    <div class="mb-3">
                                                        <label class="form-label required">
                                                            <i class="fas fa-cow"></i>Keterangan
                                                        </label>
                                                        <textarea name="keterangan" id="keterangan" class="form-control form-control-custom" rows="3" required>
                                                            {{ old('keterangan') }}
                                                        </textarea>
                                                        {{-- <input type="text" class="form-control form-control-custom"
                                                            id="keterangan" name="keterangan" required
                                                            placeholder="Contoh: Kas DKM, Kas Zakat."
                                                            value="{{ old('keterangan') }}"> --}}
                                                        <div class="form-help">Misal untuk operasional kurban</div>
                                                        @error('keterangan')
                                                            <div class="form-error">
                                                                <i
                                                                    class="fas fa-exclamation-circle"></i>{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>

                                                </div>
                                            </div>
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

    <!-- Custom Scripts for this page -->
    {{-- <script>
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
                $('#sumber_dana').val('');
            });

            // Custom jenis hewan input
            $('#sumber_dana').on('input', function() {
                const value = $(this).val().trim();
                if (value) {
                    $('.animal-type-badge').removeClass('active');
                    $('#jenis_hewan').val(value);
                }
            });

            // Format harga input
            $('#jumlah_dana').on('input', function() {
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
                const harga = $('#jumlah_dana').val() || 0;
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
                    $('#jumlah_dana').trigger('input');
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
    </script> --}}
</body>

</html>
