<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Dashboard User - Manajemen Kurban</title>
    <meta name="description" content="Dashboard pengguna untuk pendaftaran dan monitoring kurban">

    <!-- CSS Links -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/distribusi/index.css') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Responsive Improvements -->
    <link rel="stylesheet" href="{{ asset('css/responsive-improvements.css') }}">
</head>

<body>
    <nav class="nav-container">
        <div class="nav-main">
            <div class="nav-content">
                <!-- Logo -->
                <a href="{{ route('peserta.dashboard') }}" class="nav-logo">
                    <div class="logo-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <div class="logo-text">
                        Manajemen <span class="text-gold">Kurban</span>
                    </div>
                </a>

                <!-- Mobile Toggle -->
                <button class="mobile-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Navigation Links -->
                <div class="nav-links" id="navLinks">



                    <!-- Theme Toggle -->
                    <button class="theme-toggle" id="themeToggle" title="Toggle Dark/Light Mode">
                        <i class="fas fa-moon" id="themeIcon"></i>
                    </button>

                    <!-- User Dropdown -->
                    <div class="user-dropdown">
                        <button class="user-trigger" id="userTrigger">
                            <div class="user-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down dropdown-icon"></i>
                        </button>

                        <div class="dropdown-menu" id="dropdownMenu">
                            <div class="dropdown-header">
                                <div class="dropdown-user">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <div class="user-info">
                                        <span class="user-fullname">{{ Auth::user()->name }}</span>
                                        <span class="user-email">{{ Auth::user()->email }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="dropdown-links">
                                <a href="{{ route('profile.edit') }}" class="dropdown-link">
                                    <i class="fas fa-user"></i> Profil Saya
                                </a>

                                @if (Auth::user()->role === 'admin_kurban')
                                    <div class="dropdown-divider"></div>
                                    <a href="{{ route('admin.dashboard') }}" class="dropdown-link">
                                        <i class="fas fa-cog"></i> Admin Dashboard
                                    </a>
                                @endif

                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}" style="display: none;" id="logoutForm">
                                    @csrf
                                </form>
                                <a href="#" class="dropdown-link" style="color: red; font-weight: bold; display: flex; align-items: center; padding: 12px 16px; background-color: #fee2e2; border-radius: 6px; margin: 0.5rem;" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                                    <i class="fas fa-sign-out-alt" style="color: red;"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="container-fluid py-4" style="max-width: 1400px; margin: 0 auto;">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h1 class="section-title mb-1">Dashboard Pengguna</h1>
                <p class="muted mb-0">Halaman ini menampilkan data kurban yang terhubung ke database.</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- LEFT COLUMN: Form Pendaftaran (Col-LG-4) -->
            <div class="col-lg-4">
                {{-- Form Tambah Peserta --}}
                @if ($isOpen)
                    <form method="POST" action="{{ route('peserta.order.store') }}" enctype="multipart/form-data"
                        class="card stack" id="orderForm" data-old-tipe="{{ old('tipe_pendaftaran', '') }}"
                        data-old-hewan="{{ old('ketersediaan_hewan_id', '') }}"
                        data-old-berat="{{ old('berat_hewan', '') }}"
                        data-old-daging="{{ old('perkiraan_daging', '') }}"
                        data-old-harga="{{ old('total_harga', '') }}" data-old-jenis="{{ old('jenis_hewan', '') }}"
                        data-old-bank="{{ old('bank_id', '') }}">
                        @csrf

                        {{-- HIDDEN FIELDS --}}
                        <input type="hidden" name="berat_hewan" id="berat_hewan_hidden"
                            value="{{ old('berat_hewan') }}">
                        <input type="hidden" name="perkiraan_daging" id="perkiraan_daging_hidden"
                            value="{{ old('perkiraan_daging') }}">
                        <input type="hidden" name="total_harga" id="total_harga_hidden"
                            value="{{ old('total_harga') }}">
                        <input type="hidden" name="jenis_hewan" id="jenis_hewan_hidden"
                            value="{{ old('jenis_hewan') }}">

                        {{-- TIPE PENDAFTARAN --}}
                        <div class="form-group">
                            <label for="tipe_pendaftaran">Tipe Pendaftaran *</label>
                            <select id="tipe_pendaftaran" name="tipe_pendaftaran" class="input" required>
                                <option value="">Pilih</option>
                                <option value="transfer"
                                    {{ old('tipe_pendaftaran') == 'transfer' ? 'selected' : '' }}>
                                    Transfer Uang
                                </option>
                                <option value="kirim langsung"
                                    {{ old('tipe_pendaftaran') == 'kirim langsung' ? 'selected' : '' }}>
                                    Kirim Hewan ke DKM
                                </option>
                            </select>
                            @error('tipe_pendaftaran')
                                <small class="error">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- TRANSFER: PILIH HEWAN --}}
                        <div class="form-group" id="transfer-group" style="display:none">
                            <label>Pilih Hewan *</label>
                            <select id="ketersediaan_hewan_id" name="ketersediaan_hewan_id" class="input">
                                <option value="">Pilih Hewan</option>
                                @foreach ($ketersediaan_hewan as $hewan)
                                    @if ($hewan->jumlah > 0)
                                        <option value="{{ $hewan->id }}" data-jenis="{{ $hewan->jenis_hewan }}"
                                            data-berat="{{ $hewan->bobot }}" data-harga="{{ $hewan->harga }}"
                                            {{ old('ketersediaan_hewan_id') == $hewan->id ? 'selected' : '' }}>
                                            {{ $hewan->jenis_hewan }} ({{ $hewan->bobot }} kg) - Rp
                                            {{ number_format($hewan->harga, 0, ',', '.') }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('ketersediaan_hewan_id')
                                <small class="error">{{ $message }}</small>
                            @enderror
                        </div>


                        {{-- KIRIM LANGSUNG --}}
                        <div class="form-group" id="kirim-group" style="display:none">
                            <label>Jenis Hewan *</label>
                            <input type="text" name="jenis_hewan_input" id="jenis_hewan_input" class="input"
                                placeholder="Contoh: Sapi" value="{{ old('jenis_hewan_input') }}">
                            @error('jenis_hewan')
                                <small class="error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group" id="berat-kirim-group" style="display:none">
                            <label>Perkiraan Bobot (kg) *</label>
                            <input type="number" step="0.1" min="1" id="berat_kirim_input"
                                class="input" value="{{ old('berat_kirim_input') }}">
                            @error('berat_hewan')
                                <small class="error">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- JUMLAH HEWAN --}}
                        <div class="form-group">
                            <label>Jumlah Hewan *</label>
                            <input type="number" name="total_hewan" id="total_hewan" class="input" min="1"
                                max="1" value="{{ old('total_hewan', 1) }}" required readonly>
                            @error('total_hewan')
                                <small class="error">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- INFO OTOMATIS --}}
                        <div class="form-group" style="display:none">
                            <label>Berat Total</label>
                            <input type="text" id="berat_total_display" class="input" readonly>
                        </div>

                        <div class="form-group">
                            <label>Perkiraan Daging **</label>
                            <input type="text" id="perkiraan_daging_display" class="input" readonly>
                        </div>

                        <div class="form-group">
                            <label>Total Harga</label>
                            <input type="text" id="total_harga_display" class="input" readonly>
                        </div>

                        {{-- PESERTA PATUNGAN (SAPI) --}}
                        <div id="peserta-group" style="display:none;">
                            <div class="form-group">
                                <label><strong>Peserta Patungan (Sapi)</strong></label>
                                <p class="muted" style="font-size:12px;">Satu sapi untuk 7 orang. Nama pembeli otomatis menjadi peserta 1. Isi 6 nama peserta lainnya.</p>
                            </div>
                            @for ($i = 2; $i <= 7; $i++)
                                <div class="form-group">
                                    <label>Peserta {{ $i }}</label>
                                    <input type="text" name="peserta_{{ $i }}" id="peserta_{{ $i }}" class="input"
                                        placeholder="Nama peserta {{ $i }}" value="{{ old("peserta_{$i}") }}">
                                </div>
                            @endfor
                        </div>

                        {{-- SUBMIT --}}
                        <div class="actions">
                            <button class="btn btn-gold" type="submit" id="submitBtn">
                                Daftar Sekarang
                            </button>
                        </div>
                    </form>
                @else
                    <div class="card form-card" style="padding:1.5rem; margin-bottom:1.25rem;">
                        <h1 class="form-title" style="color:red;">Pendaftaran Belum Dibuka / Sudah Ditutup!</h1>
                        <br>
                        @if ($pelaksanaan)
                            <p class="muted">
                                Pendaftaran dibuka dari
                                <strong>{{ \Carbon\Carbon::parse($pelaksanaan->Tanggal_Pendaftaran)->format('d M Y') }}</strong>
                                hingga
                                <strong>{{ \Carbon\Carbon::parse($pelaksanaan->Tanggal_Penutupan)->format('d M Y') }}</strong>
                            </p>
                        @else
                            <p class="muted">Jadwal pelaksanaan kurban belum ditetapkan oleh panitia.</p>
                        @endif
                    </div>
                @endif


                {{-- Catatan --}}
                <div class="card form-card">
                    <h3 class="card-title">Catatan!</h3>
                    <p class="muted">
                        <strong>*</strong> Tipe pendaftaran<strong> Transfer Uang</strong> adalah membeli hewan
                        kurban melalui DKM
                    </p>
                    <br>
                    <p class="muted">
                        <strong>*</strong> Tipe pendaftaran<strong> Kirim Hewan Ke DKM</strong> adalah membawa hewan
                        kurban langsung ke masjid sebelum waktu penyembelihan
                    </p>
                    <br>
                    <p class="muted">
                        <strong>*</strong> Jumlah hewan sekali pendaftaran dibatasi hanya<strong> 1 (satu)</strong>,
                        lakukan pendaftaran ulang untuk hewan tambahan
                    </p>
                    <br>
                    <p class="muted">
                        <strong>**</strong> Perhitungan perkiraan berat daging bersih diperoleh dari
                        <a href="https://www.holycowsteak.com/blogs/story/cara-pembagian-daging-kurban"
                            target="_blank" class="text-blue-600 underline hover:text-blue-800">
                            sini
                        </a>
                    </p>
                </div>
            </div>
            <!-- END LEFT COLUMN -->

            <!-- RIGHT COLUMN: Tables and Info (Col-LG-8) -->
            <div class="col-lg-8">
                {{-- Jadwal Pelaksanaan Kurban --}}
                <div class="mb-4">
                    <h3 class="section-title mb-1" style="font-size: 1.25rem; color: var(--foreground);">Jadwal Pelaksanaan</h3>
                    <p class="text-muted mb-3">Informasi tanggal, waktu, dan lokasi penyembelihan.</p>
                    <div class="row g-3">
                        @forelse ($pelaksanaanKurban as $item)
                            <div class="col-md-6">
                                <div class="modern-card p-3 h-100 mb-0">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background-color: var(--primary) !important;">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0" style="font-weight: 600; font-size: 1rem; color: var(--foreground);">{{ $item->Lokasi }}</h5>
                                            <small class="text-muted">Ketua: {{ $item->Ketuplak }}</small>
                                        </div>
                                    </div>
                                    <div class="timeline-item mb-2 p-2">
                                        <small class="text-muted d-block">Pendaftaran</small>
                                        <strong style="color: var(--foreground);">{{ \Carbon\Carbon::parse($item->Tanggal_Pendaftaran)->format('d M') }} - {{ \Carbon\Carbon::parse($item->Tanggal_Penutupan)->format('d M Y') }}</strong>
                                    </div>
                                    <div class="timeline-item mb-0 p-2">
                                        <small class="text-muted d-block">Penyembelihan</small>
                                        <strong style="color: var(--foreground);">{{ \Carbon\Carbon::parse($item->Penyembelihan)->format('d M Y') }}</strong>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="modern-card text-center p-4 mb-0">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Jadwal penyembelihan belum ditetapkan oleh panitia.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Status Pembayaran --}}
                <div class="mb-4">
                    <h3 class="section-title mb-1" style="font-size: 1.25rem; color: var(--foreground);">Status Transaksi Saya</h3>
                    <p class="text-muted mb-3">Pantau status pendaftaran dan pembayaran kurban Anda.</p>
                    <div class="row g-3">
                        @forelse ($detailPembayaran as $row)
                            <div class="col-12">
                                <div class="ticket-card mb-0">
                                    <div class="ticket-header">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-receipt" style="color: var(--primary);"></i>
                                            <span style="font-weight: 600; color: var(--foreground);">{{ $row->jenis_hewan ?? '-' }} ({{ $row->total_hewan ?? '-' }} Ekor)</span>
                                        </div>
                                        <span class="status-badge status-{{ strtolower($row->status ?? 'pending') }}">
                                            {{ $row->status ?? '-' }}
                                        </span>
                                    </div>
                                    <div class="ticket-body">
                                        <div>
                                            <small class="text-muted d-block">Total Tagihan</small>
                                            <strong style="font-size: 1.1rem; color: var(--foreground);">Rp {{ number_format($row->total_harga, 0, ',', '.') }}</strong>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Tipe Pendaftaran</small>
                                            <strong style="color: var(--foreground);">{{ $row->tipe_pendaftaran ?? '-' }}</strong>
                                        </div>
                                        <div class="col-span-2 mt-2" style="grid-column: span 2;">
                                            @if ($row->tipe_pendaftaran === 'transfer' && $row->status === 'menunggu verifikasi')
                                                <a href="{{ route('peserta.payment.show', $row->id) }}" class="btn btn-primary w-100 mb-2" style="background-color: var(--primary); border-color: var(--primary);">
                                                    <i class="fas fa-credit-card me-2"></i> Lanjutkan Pembayaran
                                                </a>
                                            @endif

                                            @if ($row->status === 'disetujui' && $row->kontrak)
                                                <a href="{{ route('peserta.order.invoice', $row->id) }}" class="btn btn-outline-success w-100 mb-2" style="border-color: #28a745; color: #28a745;">
                                                    <i class="fas fa-file-contract me-2"></i> Download Surat Kontrak
                                                </a>
                                            @endif

                                            @if ($row->sertifikat && $row->sertifikat->count() > 0)
                                                @foreach ($row->sertifikat as $sertifikat)
                                                    <a href="{{ route('peserta.sertifikat.download', $sertifikat->id) }}" class="btn btn-outline-info w-100 mb-2" style="border-color: #17a2b8; color: #17a2b8;">
                                                        <i class="fas fa-award me-2"></i> Download Sertifikat {{ $row->sertifikat->count() > 1 ? '- ' . $sertifikat->nama_peserta : '' }}
                                                    </a>
                                                @endforeach
                                            @endif
                                            
                                            @if ($row->bukti_pembayaran && !json_decode($row->bukti_pembayaran))
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="{{ asset('storage/' . $row->bukti_pembayaran) }}" alt="Bukti Pembayaran" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border);">
                                                    <span class="text-success" style="color: var(--primary) !important;"><i class="fas fa-check-circle me-1"></i> Bukti Terunggah (Manual)</span>
                                                </div>
                                            @endif
                                            @if ($row->alasan_penolakan)
                                                <div class="alert alert-danger py-2 mt-2 mb-0" style="font-size: 0.9rem;">
                                                    <strong>Ditolak:</strong> {{ $row->alasan_penolakan }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="modern-card text-center p-4 mb-0">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada riwayat pendaftaran.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Dokumentasi & Distribusi --}}
                <div class="mb-4">
                    <h3 class="section-title mb-1" style="font-size: 1.25rem; color: var(--foreground);">Dokumentasi & Distribusi</h3>
                    <p class="text-muted mb-3">Hasil penyembelihan hewan Anda dan penyalurannya.</p>
                    
                    @forelse ($penyembelihan as $row)
                        <div class="modern-card mb-3 p-0 overflow-hidden">
                            <div class="row g-0">
                                <div class="col-md-4 bg-light d-flex align-items-center justify-content-center p-3" style="border-right: 1px solid var(--border);">
                                    @if ($row->dokumentasi_penyembelihan)
                                        <img src="{{ asset('storage/' . $row->dokumentasi_penyembelihan) }}" class="img-fluid rounded shadow-sm" alt="Penyembelihan">
                                    @else
                                        <div class="text-center text-muted">
                                            <i class="fas fa-image fa-3x mb-2"></i>
                                            <p class="mb-0 small">Belum ada foto</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-8 p-3 p-md-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="mb-0" style="font-weight: 600; color: var(--foreground);">{{ $row->order->jenis_hewan ?? '-' }} Anda</h5>
                                        <span class="status-badge status-{{ strtolower($row->status ?? 'pending') }}">{{ $row->status ?? '-' }}</span>
                                    </div>
                                    <div class="row g-3 mb-0">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Tanggal Potong</small>
                                            <strong style="color: var(--foreground);">{{ \Carbon\Carbon::parse($row->pelaksanaan->Penyembelihan)->format('d M Y') }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Berat & Daging</small>
                                            <strong style="color: var(--foreground);">{{ $row->order->berat_hewan ? number_format($row->order->berat_hewan, 1) . ' kg' : '-' }}</strong> <span class="text-muted mx-1">/</span> <span style="color: var(--primary);">{{ $row->order->perkiraan_daging ? number_format($row->order->perkiraan_daging, 1) . ' kg' : '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="modern-card text-center p-4 mb-3">
                            <i class="fas fa-camera-retro fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada dokumentasi penyembelihan untuk hewan Anda.</p>
                        </div>
                    @endforelse

                    {{-- Galeri Distribusi --}}
                    @if(count($distribusi) > 0)
                        <h6 class="mb-3 mt-4" style="font-weight: 600; color: var(--foreground);">Galeri Distribusi Kepada Mustahik</h6>
                        <div class="gallery-grid">
                            @foreach ($distribusi as $item)
                                @if ($item->dokumentasi && count($item->dokumentasi) > 0)
                                    <div class="gallery-item position-relative" data-bs-toggle="modal" data-bs-target="#imageModal{{ $item->id }}">
                                        <img src="{{ asset('storage/' . $item->dokumentasi[0]->file_path) }}" alt="Distribusi">
                                        <div class="position-absolute bottom-0 start-0 w-100 p-2" style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                                            <span class="text-white small"><i class="fas fa-images"></i> +{{ count($item->dokumentasi) }}</span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="mt-3">
                            @foreach ($distribusi as $item)
                                @if ($item->link_gdrive)
                                    <a href="{{ $item->link_gdrive }}" target="_blank" class="btn btn-outline-primary btn-sm me-2 mb-2" style="border-color: var(--primary); color: var(--primary);">
                                        <i class="fab fa-google-drive me-1"></i> Drive ({{ \Carbon\Carbon::parse($item->pelaksanaan->Penyembelihan)->format('d M') }})
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <!-- END RIGHT COLUMN -->
        </div>
    </main>

    <!-- Image Modals -->
    @foreach ($distribusi as $item)
        @if ($item->dokumentasi && count($item->dokumentasi) > 0)
            <div class="modal fade dark-modal" id="imageModal{{ $item->id }}" tabindex="-1" role="dialog"
                aria-labelledby="imageModalLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content bg-dark text-light">
                        <div class="modal-header border-secondary">
                            <h5 class="modal-title" id="imageModalLabel{{ $item->id }}">
                                <i class="fas fa-images mr-2"></i>Dokumentasi Distribusi
                                <small class="text-light ml-2">
                                    ({{ optional($item->pelaksanaan)->Penyembelihan
                                        ? \Carbon\Carbon::parse($item->pelaksanaan->Penyembelihan)->format('d M Y')
                                        : 'Tanggal tidak tersedia' }})
                                </small>
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body bg-dark">
                            <!-- Image Carousel -->
                            <div id="carousel{{ $item->id }}" class="carousel slide dark-carousel"
                                data-bs-ride="carousel">
                                <!-- Indicators -->
                                @if (count($item->dokumentasi) > 1)
                                    <div class="carousel-indicators">
                                        @foreach ($item->dokumentasi as $index => $foto)
                                            <button type="button" data-bs-target="#carousel{{ $item->id }}"
                                                data-bs-slide-to="{{ $index }}"
                                                class="{{ $index == 0 ? 'active' : '' }}"
                                                aria-label="Slide {{ $index + 1 }}"></button>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Slides -->
                                <div class="carousel-inner">
                                    @foreach ($item->dokumentasi as $index => $foto)
                                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                            <div class="image-container text-center bg-black">
                                                <img src="{{ asset('storage/' . $foto->file_path) }}"
                                                    class="img-fluid modal-image-dark"
                                                    alt="Dokumentasi {{ $index + 1 }}">
                                            </div>
                                            <div class="carousel-caption d-none d-md-block">
                                                <p class="bg-black d-inline-block px-3 py-2 rounded">Gambar
                                                    {{ $index + 1 }} dari {{ count($item->dokumentasi) }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Controls -->
                                @if (count($item->dokumentasi) > 1)
                                    <button class="carousel-control-prev" type="button"
                                        data-bs-target="#carousel{{ $item->id }}" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        data-bs-target="#carousel{{ $item->id }}" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer border-secondary">
                            <div class="d-flex justify-content-between w-100">
                                <div>
                                    <span class="badge bg-dark border border-light">
                                        <i class="fas fa-image mr-1"></i> {{ count($item->dokumentasi) }} Gambar
                                    </span>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">
                                        <i class="fas fa-times mr-1"></i> Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/user.js') }}"></script>
    <script src="{{ asset('js/admin/distribusi/index.js') }}"></script>
</body>

</html>
