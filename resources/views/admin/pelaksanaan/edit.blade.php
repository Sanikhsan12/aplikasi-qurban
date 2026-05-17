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
    <link href="{{ asset('css/admin/pelaksanaan/edit.css') }}" rel="stylesheet">
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
                    <!-- Mobile-friendly form container -->
                    <div class="mobile-form-container fade-in">
                        <!-- Form Header -->
                        <div class="mobile-form-header">
                            <h1>Edit Jadwal Pelaksanaan</h1>
                            <p>Perbarui informasi jadwal kegiatan kurban</p>
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
                            <form id="mobilePelaksanaanForm"
                                action="{{ route('admin.pelaksanaan.update', $pelaksanaan->id) }}" method="POST"
                                novalidate>
                                @csrf
                                @method('PUT')

                                <!-- Date Row -->
                                <div class="mobile-form-row">
                                    <div class="mobile-form-col">
                                        <div class="mobile-form-group">
                                            <label for="Tanggal_Pendaftaran" class="mobile-form-label required">
                                                Tanggal Pendaftaran
                                            </label>
                                            <div class="mobile-date-wrapper">
                                                <input type="date" id="Tanggal_Pendaftaran"
                                                    name="Tanggal_Pendaftaran"
                                                    class="mobile-form-input @error('Tanggal_Pendaftaran') is-invalid @enderror"
                                                    value="{{ old('Tanggal_Pendaftaran', optional($pelaksanaan->Tanggal_Pendaftaran)->format('Y-m-d')) }}"
                                                    required>
                                            </div>
                                            <div class="mobile-form-help">
                                                Tanggal dimulainya pendaftaran peserta
                                            </div>
                                            @error('Tanggal_Pendaftaran')
                                                <div class="mobile-error-message">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mobile-form-col">
                                        <div class="mobile-form-group">
                                            <label for="Tanggal_Penutupan" class="mobile-form-label required">
                                                Tanggal Penutupan
                                            </label>
                                            <div class="mobile-date-wrapper">
                                                <input type="date" id="Tanggal_Penutupan" name="Tanggal_Penutupan"
                                                    class="mobile-form-input @error('Tanggal_Penutupan') is-invalid @enderror"
                                                    value="{{ old('Tanggal_Penutupan', optional($pelaksanaan->Tanggal_Penutupan)->format('Y-m-d')) }}"
                                                    required>
                                            </div>
                                            <div class="mobile-form-help">
                                                Tanggal terakhir pendaftaran
                                            </div>
                                            @error('Tanggal_Penutupan')
                                                <div class="mobile-error-message">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Ketuplak Field -->
                                <div class="mobile-form-group">
                                    <label for="Ketuplak" class="mobile-form-label required">
                                        Nama Ketuplak
                                    </label>
                                    <input type="text" id="Ketuplak" name="Ketuplak"
                                        class="mobile-form-input @error('Ketuplak') is-invalid @enderror"
                                        value="{{ old('Ketuplak', $pelaksanaan->Ketuplak) }}"
                                        placeholder="Masukkan nama ketuplak" required>
                                    <div class="mobile-form-help">
                                        Nama penanggung jawab pelaksanaan
                                    </div>
                                    @error('Ketuplak')
                                        <div class="mobile-error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Lokasi Field -->
                                <div class="mobile-form-group">
                                    <label for="Lokasi" class="mobile-form-label required">
                                        Lokasi Pelaksanaan
                                    </label>
                                    <input type="text" id="Lokasi" name="Lokasi"
                                        class="mobile-form-input @error('Lokasi') is-invalid @enderror"
                                        value="{{ old('Lokasi', $pelaksanaan->Lokasi) }}"
                                        placeholder="Masukkan lokasi pelaksanaan" required>
                                    <div class="mobile-form-help">
                                        Tempat dilaksanakannya kegiatan
                                    </div>
                                    @error('Lokasi')
                                        <div class="mobile-error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Penyembelihan Date -->
                                <div class="mobile-form-group">
                                    <label for="Penyembelihan" class="mobile-form-label required">
                                        Tanggal Penyembelihan
                                    </label>
                                    <div class="mobile-date-wrapper">
                                        <input type="date" id="Penyembelihan" name="Penyembelihan"
                                            class="mobile-form-input @error('Penyembelihan') is-invalid @enderror"
                                            value="{{ old('Penyembelihan', optional($pelaksanaan->Penyembelihan)->format('Y-m-d')) }}"
                                            required>
                                    </div>
                                    <div class="mobile-form-help">
                                        Tanggal pelaksanaan penyembelihan
                                    </div>
                                    @error('Penyembelihan')
                                        <div class="mobile-error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Status Pelaksanaan --}}
                                <div class="mobile-form-group">
                                    <label for="Status" class="mobile-form-label required">
                                        Status Pelaksanaan
                                    </label>
                                    <select name="Status" id="Status"
                                        class="mobile-form-select @error('Status') is-invalid @enderror" required>
                                        <option value="">{{ $pelaksanaan->Status }}</option>
                                        <option value="Active">Active</option>
                                        <option value="Closed">Closed</option>
                                    </select>
                                    <div class="mobile-form-help">
                                        Status pelaksanaan kurban
                                    </div>
                                    @error('Status')
                                        <div class="mobile-error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Form Status -->
                                <div class="mobile-form-status mb-3">
                                    <i class="fas fa-info-circle mobile-form-status-icon"></i>
                                    <span>Pastikan semua data telah diisi dengan benar sebelum menyimpan</span>
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
                                            Simpan Perubahan
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

    <!-- Page level plugins -->
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Custom Script for Mobile Form -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('mobilePelaksanaanForm');
            const submitBtn = document.getElementById('submitBtn');
            const today = new Date().toISOString().split('T')[0];

            // Set min date for date inputs
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                input.min = today;
                input.addEventListener('focus', function() {
                    this.showPicker && this.showPicker();
                });
            });

            // Form validation
            form.addEventListener('submit', function(e) {
                let isValid = true;
                const requiredFields = form.querySelectorAll('[required]');

                requiredFields.forEach(field => {
                    field.classList.remove('is-invalid', 'is-valid');

                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.add('is-valid');
                    }
                });

                // Date validation
                const pendaftaran = document.getElementById('Tanggal_Pendaftaran').value;
                const penutupan = document.getElementById('Tanggal_Penutupan').value;
                const penyembelihan = document.getElementById('Penyembelihan').value;

                if (pendaftaran && penutupan && pendaftaran > penutupan) {
                    alert('Tanggal pendaftaran tidak boleh lebih dari tanggal penutupan');
                    isValid = false;
                }

                if (penyembelihan && penutupan && penyembelihan < penutupan) {
                    alert('Tanggal penyembelihan tidak boleh kurang dari tanggal penutupan');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    // Scroll to first error
                    const firstError = form.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstError.focus();
                    }
                } else {
                    // Show loading state
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                    submitBtn.disabled = true;
                    submitBtn.classList.add('loading');
                }
            });

            // Real-time validation
            const inputs = form.querySelectorAll('.mobile-form-input');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.hasAttribute('required') && !this.value.trim()) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    } else if (this.value.trim()) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                });

                input.addEventListener('input', function() {
                    if (this.value.trim()) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                });
            });

            // Touch device optimizations
            if ('ontouchstart' in window) {
                // Increase touch target size for form elements
                const formElements = document.querySelectorAll('.mobile-form-input, .mobile-btn');
                formElements.forEach(el => {
                    el.style.minHeight = '44px';
                });

                // Better date picker for mobile
                dateInputs.forEach(input => {
                    input.addEventListener('touchstart', function(e) {
                        e.preventDefault();
                        this.showPicker && this.showPicker();
                    }, {
                        passive: false
                    });
                });
            }

            // Auto-format dates for better UX
            function formatDateInput(input) {
                if (input.value) {
                    const date = new Date(input.value);
                    const formatted = date.toLocaleDateString('id-ID', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    // Show formatted date as placeholder-like text
                    const wrapper = input.parentElement;
                    const existingInfo = wrapper.querySelector('.date-formatted');
                    if (existingInfo) existingInfo.remove();

                    const info = document.createElement('div');
                    info.className = 'date-formatted mobile-form-help';
                    info.textContent = `Dipilih: ${formatted}`;
                    wrapper.appendChild(info);
                }
            }

            dateInputs.forEach(input => {
                input.addEventListener('change', function() {
                    formatDateInput(this);
                });
                // Initial format
                if (input.value) {
                    formatDateInput(input);
                }
            });

            // Prevent accidental navigation
            let formChanged = false;
            form.querySelectorAll('.mobile-form-input').forEach(input => {
                const initialValue = input.value;
                input.addEventListener('input', () => {
                    formChanged = input.value !== initialValue;
                });
            });

            window.addEventListener('beforeunload', (e) => {
                if (formChanged) {
                    e.preventDefault();
                    e.returnValue = 'Perubahan yang belum disimpan akan hilang. Yakin ingin keluar?';
                }
            });

            // Handle back button with saved changes
            const backBtn = document.querySelector('.mobile-back-btn');
            backBtn.addEventListener('click', (e) => {
                if (formChanged) {
                    e.preventDefault();
                    if (confirm('Perubahan yang belum disimpan akan hilang. Yakin ingin kembali?')) {
                        window.location.href = backBtn.href;
                    }
                }
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + S to save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                document.getElementById('submitBtn').click();
            }

            // Escape to cancel
            if (e.key === 'Escape') {
                window.history.back();
            }
        });
    </script>

</body>

</html>
