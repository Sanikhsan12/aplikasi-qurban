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
                                <form method="POST" action="{{ route('logout') }}" style="display: none;"
                                    id="logoutForm">
                                    @csrf
                                </form>
                                <a href="#" class="dropdown-link"
                                    onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="dashboard-container">
        <h1 class="section-title">Dashboard Pengguna</h1>
        <p class="muted" style="margin-bottom: 1.5rem;">Halaman ini menampilkan data kurban yang terhubung ke database.
        </p>

        <div class="dashboard-grid">
            <aside>
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

                        {{-- TRANSFER: PILIH BANK --}}
                        <div class="form-group" id="bank-select-group" style="display:none">
                            <label>Pilih Bank *</label>
                            <select id="bank_id" name="bank_id" class="input">
                                <option value="">Pilih Bank Tujuan Transfer</option>
                                @foreach ($bankPenerima as $bank)
                                    <option value="{{ $bank->id }}" data-nama-bank="{{ $bank->nama_bank }}"
                                        data-no-rek="{{ $bank->no_rek }}" data-as-nama="{{ $bank->as_nama }}"
                                        {{ old('bank_id') == $bank->id ? 'selected' : '' }}>
                                        {{ $bank->nama_bank }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bank_id')
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

                        {{-- BUKTI PEMBAYARAN --}}
                        <div class="form-group" id="bukti-group" style="display:none">
                            <label>Bukti Pembayaran *</label>
                            <input type="file" name="bukti_pembayaran" class="input"
                                accept=".jpg,.jpeg,.png,.webp,.pdf">
                            @error('bukti_pembayaran')
                                <small class="error">{{ $message }}</small>
                            @enderror
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
                        <p class="muted">
                            Pendaftaran dibuka dari
                            <strong>{{ \Carbon\Carbon::parse($pelaksanaan->Tanggal_Pendaftaran)->format('d M Y') }}</strong>
                            hingga
                            <strong>{{ \Carbon\Carbon::parse($pelaksanaan->Tanggal_Penutupan)->format('d M Y') }}</strong>
                        </p>
                    </div>
                @endif

                {{-- INFORMASI BANK TERPILIH --}}
                <div class="form-group" id="bank-info-group" style="display:none">
                    <div class="bank-info-container" id="bank-detail-container">
                        <!-- Informasi bank akan dimuat dinamis di sini -->
                    </div>
                </div>

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
            </aside>

            <section>
                {{-- Jadwal Pelaksanaan Kurban --}}
                <div class="card">
                    {{-- DESKTOP VIEW --}}
<div class="d-none d-lg-block">

    {{-- Jadwal Pelaksanaan Kurban --}}
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title mb-1">Jadwal Pelaksanaan Kurban</h3>
            <p class="muted mb-0">Informasi tanggal, waktu, dan lokasi penyembelihan.</p>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal Pendaftaran</th>
                        <th>Tanggal Penutupan</th>
                        <th>Lokasi</th>
                        <th>Jadwal Penyembelihan</th>
                        <th>Ketua Pelaksana</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($pelaksanaanKurban as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->Tanggal_Pendaftaran)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->Tanggal_Penutupan)->format('d M Y') }}</td>
                            <td>{{ $item->Lokasi }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->Penyembelihan)->format('d M Y') }}</td>
                            <td>{{ $item->Ketuplak }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Jadwal penyembelihan belum ditetapkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Status Pembayaran --}}
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title mb-1">Status Pembayaran</h3>
            <p class="muted mb-0">Status pembayaran dan informasi transaksi.</p>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Donatur</th>
                        <th>Jenis Hewan</th>
                        <th>Total Hewan</th>
                        <th>Total Harga</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th>Bukti</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($detailPembayaran as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->user->name ?? '-' }}</td>
                            <td>{{ $row->jenis_hewan ?? '-' }}</td>
                            <td>{{ $row->total_hewan ?? '-' }}</td>
                            <td>
                                Rp {{ number_format($row->total_harga, 0, ',', '.') }}
                            </td>
                            <td>{{ $row->tipe_pendaftaran ?? '-' }}</td>

                            <td>
                                <span class="status-badge status-{{ strtolower($row->status ?? 'pending') }}">
                                    {{ $row->status ?? '-' }}
                                </span>
                            </td>

                            <td>
                                @if ($row->bukti_pembayaran)
                                    <img src="{{ asset('storage/' . $row->bukti_pembayaran) }}"
                                        class="desktop-thumbnail"
                                        alt="Bukti Pembayaran">
                                @else
                                    <span class="text-muted">Belum ada</span>
                                @endif
                            </td>
                        </tr>

                        @if ($row->alasan_penolakan)
                            <tr>
                                <td colspan="8">
                                    <div class="alert alert-danger mb-0">
                                        <strong>Alasan Penolakan:</strong>
                                        {{ $row->alasan_penolakan }}
                                    </div>
                                </td>
                            </tr>
                        @endif

                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Tidak ada riwayat transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Dokumentasi Penyembelihan --}}
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title mb-1">Dokumentasi Penyembelihan</h3>
            <p class="muted mb-0">Dokumentasi penyembelihan hewan kurban.</p>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Donatur</th>
                        <th>Jenis Hewan</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Berat</th>
                        <th>Daging</th>
                        <th>Dokumentasi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($penyembelihan as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->order->user->name ?? '-' }}</td>
                            <td>{{ $row->order->jenis_hewan ?? '-' }}</td>

                            <td>
                                <span class="status-badge status-{{ strtolower($row->status ?? 'pending') }}">
                                    {{ $row->status ?? '-' }}
                                </span>
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($row->pelaksanaan->Penyembelihan)->format('d M Y') }}
                            </td>

                            <td>
                                {{ $row->order->berat_hewan ? number_format($row->order->berat_hewan, 1) . ' kg' : '-' }}
                            </td>

                            <td>
                                {{ $row->order->perkiraan_daging ? number_format($row->order->perkiraan_daging, 1) . ' kg' : '-' }}
                            </td>

                            <td>
                                @if ($row->dokumentasi_penyembelihan)
                                    <img src="{{ asset('storage/' . $row->dokumentasi_penyembelihan) }}"
                                        class="desktop-thumbnail"
                                        alt="Dokumentasi">
                                @else
                                    <span class="text-muted">Belum ada</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Tidak ada data penyembelihan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Distribusi Daging --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-1">Riwayat Distribusi Daging</h3>
            <p class="muted mb-0">Catatan distribusi daging kurban.</p>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal Penyembelihan</th>
                        <th>Google Drive</th>
                        <th>Dokumentasi</th>
                        <th>Tanggal Upload</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($distribusi as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($item->pelaksanaan->Penyembelihan)->format('d M Y') }}
                            </td>

                            <td>
                                @if ($item->link_gdrive)
                                    <a href="{{ $item->link_gdrive }}"
                                        target="_blank"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-external-link-alt"></i>
                                        Buka Drive
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>

                            <td>
                                @if ($item->dokumentasi && count($item->dokumentasi) > 0)
                                    <button type="button"
                                        class="btn btn-dark btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#imageModal{{ $item->id }}">

                                        <i class="fas fa-images"></i>
                                        {{ count($item->dokumentasi) }} Gambar
                                    </button>
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Belum ada riwayat distribusi daging.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
                    {{-- Mobile Card View --}}
                    <div class="mobile-card-view">
                        <div class="card-header mb-3">
                            <h3 class="card-title">Jadwal Pelaksanaan Kurban</h3>
                            <p class="muted">Informasi tanggal, waktu, dan lokasi penyembelihan.</p>
                        </div>
                        <div class="data-grid">
                            @forelse ($pelaksanaanKurban as $pelaksanaan)
                                <div class="data-card">
                                    <div class="data-row">
                                        <span class="data-label">Tanggal Pendaftaran</span>
                                        <span
                                            class="data-value">{{ \Carbon\Carbon::parse($pelaksanaan->Tanggal_Pendaftaran)->format('d M Y') }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Tanggal Penutupan</span>
                                        <span
                                            class="data-value">{{ \Carbon\Carbon::parse($pelaksanaan->Tanggal_Penutupan)->format('d M Y') }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Lokasi</span>
                                        <span class="data-value">{{ $pelaksanaan->Lokasi }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Jadwal Penyembelihan</span>
                                        <span
                                            class="data-value">{{ \Carbon\Carbon::parse($pelaksanaan->Penyembelihan)->format('d M Y') }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Ketua Pelaksana</span>
                                        <span class="data-value">{{ $pelaksanaan->Ketuplak }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="data-card text-center text-muted">Jadwal penyembelihan belum
                                    ditetapkan.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Status Pembayaran --}}
                <div class="card">

                    {{-- Mobile Card View --}}
                    <div class="mobile-card-view">
                        <div class="card-header mb-3">
                            <h3 class="card-title">Status Pembayaran</h3>
                            <p class="muted">Status pembayaran dan informasi transaksi.</p>
                        </div>
                        <div class="data-grid">
                            @forelse ($detailPembayaran as $row)
                                <div class="data-card">
                                    <div class="data-row">
                                        <span class="data-label">Nama Donatur</span>
                                        <span class="data-value">{{ $row->user->name ?? '-' }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Jenis Hewan</span>
                                        <span class="data-value">{{ $row->jenis_hewan ?? '-' }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Total Hewan</span>
                                        <span class="data-value">{{ $row->total_hewan ?? '-' }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Total Harga</span>
                                        <span class="data-value">Rp.
                                            {{ number_format($row->total_harga, 0, ',', '.') ?? '-' }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Tipe Pendaftaran</span>
                                        <span class="data-value">{{ $row->tipe_pendaftaran ?? '-' }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Status</span>
                                        <span class="data-value">
                                            <span
                                                class="status-badge status-{{ strtolower($row->status ?? 'pending') }}">
                                                {{ $row->status ?? '-' }}
                                            </span>
                                        </span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Bukti Pembayaran</span>
                                        <span class="data-value">
                                            @if ($row->bukti_pembayaran)
                                                <img src="{{ asset('storage/' . $row->bukti_pembayaran) }}"
                                                    alt="Bukti Pembayaran" style="max-width: 80px;">
                                            @else
                                                <span class="text-muted">Belum ada foto</span>
                                            @endif
                                        </span>
                                    </div>
                                    @if ($row->alasan_penolakan)
                                        <div class="data-row">
                                            <span class="data-label">Alasan Penolakan</span>
                                            <span class="data-value text-danger">{{ $row->alasan_penolakan }}</span>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="data-card text-center text-muted">Tidak ada riwayat transaksi.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Dokumentasi Penyembelihan --}}
                <div class="card">

                    {{-- Mobile Card View --}}
                    <div class="mobile-card-view">
                        <div class="card-header mb-3">
                            <h3 class="card-title">Dokumentasi Penyembelihan</h3>
                            <p class="muted">Dokumentasi penyembelihan hewan kurban.</p>
                        </div>
                        <div class="data-grid">
                            @forelse ($penyembelihan as $row)
                                <div class="data-card">
                                    <div class="data-row">
                                        <span class="data-label">Nama Donatur</span>
                                        <span class="data-value">{{ $row->order->user->name ?? '-' }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Jenis Hewan</span>
                                        <span class="data-value">{{ $row->order->jenis_hewan ?? '-' }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Status Hewan</span>
                                        <span class="data-value">
                                            <span
                                                class="status-badge status-{{ strtolower($row->status ?? 'pending') }}">
                                                {{ $row->status ?? '-' }}
                                            </span>
                                        </span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Waktu Penyembelihan</span>
                                        <span
                                            class="data-value">{{ \Carbon\Carbon::parse($row->pelaksanaan->Penyembelihan)->format('d M Y') ?? '-' }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Berat Hewan</span>
                                        <span
                                            class="data-value">{{ $row->order->berat_hewan ? number_format($row->order->berat_hewan, 1) . ' kg' : '-' }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Perkiraan Daging</span>
                                        <span
                                            class="data-value">{{ $row->order->perkiraan_daging ? number_format($row->order->perkiraan_daging, 1) . ' kg' : '-' }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Dokumentasi</span>
                                        <span class="data-value">
                                            @if ($row->dokumentasi_penyembelihan)
                                                <img src="{{ asset('storage/' . $row->dokumentasi_penyembelihan) }}"
                                                    alt="Foto penyembelihan" style="max-width: 80px;">
                                            @else
                                                <span class="text-muted">Belum ada foto</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="data-card text-center text-muted">Tidak ada data penyembelihan untuk
                                    hewan Anda.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Riwayat Distribusi Daging --}}
                <div class="card">

                    {{-- Mobile Card View --}}
                    <div class="mobile-card-view">
                        <div class="card-header mb-3">
                            <h3 class="card-title">Riwayat Distribusi Daging</h3>
                            <p class="muted">Catatan distribusi daging kurban yang telah dilakukan.</p>
                        </div>
                        <div class="data-grid">
                            @forelse ($distribusi as $item)
                                <div class="data-card">
                                    <div class="data-row">
                                        <span class="data-label">Tanggal Penyembelihan</span>
                                        <span
                                            class="data-value">{{ \Carbon\Carbon::parse($item->pelaksanaan->Penyembelihan)->format('d M Y') }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Link Google Drive</span>
                                        <span class="data-value">
                                            @if ($item->link_gdrive)
                                                <a href="{{ $item->link_gdrive }}" target="_blank">
                                                    Buka Drive
                                                </a>
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Dokumentasi</span>
                                        <span class="data-value">
                                            @if ($item->dokumentasi && count($item->dokumentasi) > 0)
                                                <button type="button" class="btn btn-sm btn-dark view-images-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#imageModal{{ $item->id }}"
                                                    style="width: 100%;">
                                                    <i class="fas fa-eye"></i> Lihat Dokumentasi
                                                    <span
                                                        class="badge bg-light text-dark ml-1">{{ count($item->dokumentasi) }}</span>
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Tanggal Upload</span>
                                        <span class="data-value">
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="data-card text-center text-muted">Belum ada riwayat distribusi daging.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>
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
