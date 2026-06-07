<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kurban - Sistem Pengelolaan Ibadah Kurban</title>
    <meta name="description"
        content="Sistem manajemen kurban untuk pengelolaan hewan kurban, pendaftaran peserta, dan distribusi daging kurban secara transparan dan mudah.">
    <meta name="keywords" content="kurban, qurban, idul adha, manajemen kurban, hewan kurban, daging kurban">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="container header-content">
            <div class="logo">
                <div class="logo-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                    </svg>
                </div>
                <div class="logo-text">
                    <span>Manajemen</span> <span class="text-primary">Kurban</span>
                </div>
            </div>
            <nav class="nav-desktop">
                @if (Route::has('login'))
                    {{-- <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10"> --}}
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log
                                in</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                            @endif
                        @endauth
                        {{--
                    </div> --}}
                @endif
            </nav>
            <button class="menu-toggle" onclick="toggleMobileMenu()">
                <svg id="menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>
        <nav class="nav-mobile" id="mobileNav">
            <a href="{{ route('login') }}">Masuk</a>
            <a href="{{ route('register') }}">Daftar</a>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero p-40 relative">
        <div class="hero-bg"></div>
        <div class="container hero-content">
            <div class="hero-badge">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span>Idul Adha 1446 H</span>
            </div>
            <h1 class="hero-title">
                Sistem Manajemen <br>
                <span class="gold-text">Kurban</span>
            </h1>
            <p class="hero-subtitle">
                Kelola ibadah kurban dengan mudah dan transparan. Dari pendaftaran hingga distribusi daging kurban
                kepada yang berhak.
            </p>
            <div class="hero-buttons m-4">
                {{-- <a href="{{ route('kurban.create') }}" class="btn btn-gold"> --}}
                <a href="#" class="btn btn-gold m-5">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path
                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z" />
                    </svg>
                    Daftar Kurban
                </a>
            </div>
        </div>
        <div class="hero-decoration hero-decoration-1"></div>
        <div class="hero-decoration hero-decoration-2"></div>
        <div class="hero-decoration hero-decoration-3"></div>
    </section>

    <!-- Stats Section -->
    <section id="dashboard" class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Dashboard <span class="text-primary">Kurban</span></h2>
                <p class="section-subtitle">Pantau perkembangan program kurban secara real-time</p>
            </div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon bg-primary">🐄</div>
                    <p class="stat-label">Total Hewan</p>
                    <p class="stat-value">24</p>
                    <p class="stat-sublabel">Sapi & Kambing</p>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-gold">👥</div>
                    <p class="stat-label">Peserta Kurban</p>
                    <p class="stat-value">156</p>
                    <p class="stat-sublabel">Terdaftar</p>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-emerald">📦</div>
                    <p class="stat-label">Distribusi</p>
                    <p class="stat-value">480</p>
                    <p class="stat-sublabel">Paket Daging</p>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-gold-dark">💰</div>
                    <p class="stat-label">Dana Terkumpul</p>
                    <p class="stat-value">Rp 312jt</p>
                    <p class="stat-sublabel">Total Kontribusi</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Animal Section -->
    <section id="hewan" class="section section-alt">
        <div class="container">
            <div class="section-header-row">
                <div>
                    <h2 class="section-title">Hewan <span class="text-primary">Kurban</span></h2>
                    <p class="section-subtitle">Daftar hewan kurban yang tersedia</p>
                </div>
            </div>

            <!-- Animal Cards -->
            <div class="animal-grid" id="animalGrid">
                <!-- Cards will be generated by JavaScript -->
            </div>
        </div>
    </section>

    <!-- Deteksi Uang Section -->
    <section id="deteksi-uang" class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Deteksi <span class="text-primary">Uang Asli / Palsu</span></h2>
                <p class="section-subtitle">Gunakan kamera atau upload gambar untuk mendeteksi keaslian uang dengan YOLO</p>
            </div>

            <div class="deteksi-container">
                <div class="deteksi-row">
                    <div class="deteksi-col">
                        <div class="deteksi-card">
                            <div class="deteksi-card-header">
                                <h3><i class="fas fa-camera"></i> Kamera</h3>
                            </div>
                            <div class="deteksi-card-body">
                                <video id="video" class="deteksi-video" autoplay playsinline></video>
                                <canvas id="canvas" class="deteksi-canvas" style="display:none"></canvas>
                            </div>
                            <div class="deteksi-card-footer">
                                <button class="btn-camera" onclick="bukaKamera()">
                                    <i class="fas fa-camera"></i> Buka Kamera
                                </button>
                                <button class="btn-capture" onclick="ambilFoto()" disabled id="btnCapture">
                                    <i class="fas fa-camera-retro"></i> Ambil Foto
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="deteksi-col">
                        <div class="deteksi-card">
                            <div class="deteksi-card-header">
                                <h3><i class="fas fa-image"></i> Upload Gambar</h3>
                            </div>
                            <div class="deteksi-card-body">
                                <div class="upload-wrapper" id="uploadWrapper">
                                    <div class="upload-area" id="uploadArea">
                                        <div class="upload-content" id="uploadContent">
                                            <div class="upload-icon-wrap">
                                                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                            </div>
                                            <p class="upload-title">Seret & lepas gambar di sini</p>
                                            <p class="upload-subtitle">atau</p>
                                            <button type="button" class="btn-upload-select" onclick="document.getElementById('fileInput').click()">
                                                <i class="fas fa-folder-open"></i> Pilih File
                                            </button>
                                            <p class="upload-hint">JPEG, PNG, WEBP &mdash; Maks 5MB</p>
                                        </div>
                                        <input type="file" id="fileInput" accept="image/*" hidden onchange="previewFile(event)">
                                    </div>
                                    <div class="upload-preview" id="uploadPreview" style="display:none">
                                        <div class="preview-image-wrap">
                                            <img id="preview" class="preview-image" />
                                            <button type="button" class="preview-remove" onclick="removeFile()" title="Hapus gambar">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="preview-info">
                                            <div class="preview-info-left">
                                                <i class="fas fa-file-image"></i>
                                                <span id="fileName">image.jpg</span>
                                            </div>
                                            <span class="preview-size" id="fileSize">0 KB</span>
                                        </div>
                                    </div>
                                </div>
                                <canvas id="resultCanvas" class="deteksi-canvas" style="display:none"></canvas>
                            </div>
                            <div class="deteksi-card-footer">
                                <button class="btn-detect" onclick="deteksiUang()" id="btnDetect" disabled>
                                    <i class="fas fa-search"></i> Deteksi Uang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="hasilDeteksi" class="deteksi-hasil" style="display:none">
                    <div class="hasil-card">
                        <div class="hasil-icon warning" id="hasilIcon">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                        <div class="hasil-text">
                            <h3 id="hasilLabel">Memproses...</h3>
                            <p id="hasilConfidence">Confidence: -</p>
                            <div class="confidence-bar" id="confidenceBar" style="display:none">
                                <div class="confidence-fill" style="width:0%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="logo">
                        <div class="logo-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                            </svg>
                        </div>
                        <div class="logo-text">Manajemen Kurban</div>
                    </div>
                    <p>Sistem pengelolaan ibadah kurban yang transparan dan mudah digunakan untuk masjid dan komunitas
                        muslim.</p>
                </div>
                <div class="footer-contact">
                    <h4>Kontak</h4>
                    <ul>
                        <li>Masjid Al-Ikhlas</li>
                        <li>Jl. Raya Masjid No. 123</li>
                        <li>Telp: (021) 1234-5678</li>
                        <li>Email: kurban@masjid.id</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2024 Manajemen Kurban. Hak cipta dilindungi.</p>
                <p>Dibuat dengan ❤️ setulus hati untuk umat</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/deteksi-uang.js') }}"></script>

    <style>
        .deteksi-container { max-width: 900px; margin: 0 auto; }
        .deteksi-row { display: flex; gap: 20px; flex-wrap: wrap; }
        .deteksi-col { flex: 1; min-width: 280px; }
        .deteksi-card { background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; transition: box-shadow 0.3s; }
        .deteksi-card:hover { box-shadow: 0 8px 30px rgba(0,0,0,0.12); }
        .deteksi-card-header { padding: 16px 20px; border-bottom: 1px solid #f0f0f0; }
        .deteksi-card-header h3 { margin: 0; font-size: 16px; display: flex; align-items: center; gap: 8px; color: #1a1a2e; }
        .deteksi-card-body { padding: 20px; min-height: 220px; display: flex; align-items: center; justify-content: center; }
        .deteksi-card-footer { padding: 12px 20px; border-top: 1px solid #f0f0f0; display: flex; gap: 8px; flex-wrap: wrap; }
        .deteksi-video { width: 100%; max-height: 300px; border-radius: 12px; background: #000; }
        .deteksi-canvas { display: none; }

        /* Upload Area */
        .upload-wrapper { width: 100%; }
        .upload-area {
            text-align: center; padding: 44px 20px 40px;
            border: 2px dashed #d4d4d8;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
            background: linear-gradient(135deg, #fefcf5 0%, #fff 100%);
            position: relative;
            overflow: hidden;
        }
        .upload-area::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(184,134,11,0.04) 0%, rgba(184,134,11,0.02) 100%);
            opacity: 0;
            transition: opacity 0.35s;
        }
        .upload-area:hover { border-color: #b8860b; background: #fefbf0; }
        .upload-area:hover::before { opacity: 1; }
        .upload-area.drag-over {
            border-color: #b8860b;
            background: linear-gradient(135deg, #fef6e0 0%, #fefbf0 100%);
            transform: scale(1.015);
            box-shadow: 0 0 0 4px rgba(184,134,11,0.1), 0 8px 30px rgba(184,134,11,0.1);
        }
        .upload-area.drag-over .upload-icon {
            transform: translateY(-4px) scale(1.1);
        }
        .upload-content { position: relative; z-index: 1; }
        .upload-icon-wrap {
            width: 72px; height: 72px;
            margin: 0 auto 16px;
            background: linear-gradient(135deg, rgba(184,134,11,0.1) 0%, rgba(184,134,11,0.05) 100%);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            transition: transform 0.35s;
        }
        .upload-icon { font-size: 32px; color: #b8860b; transition: transform 0.35s; }
        .upload-area:hover .upload-icon { transform: translateY(-4px); }
        .upload-title { font-size: 15px; font-weight: 600; color: #1a1a2e; margin-bottom: 4px; }
        .upload-subtitle { font-size: 13px; color: #a1a1aa; margin-bottom: 14px; }
        .btn-upload-select {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 22px;
            background: linear-gradient(135deg, #b8860b, #a0750a);
            color: white; border: none; border-radius: 8px;
            font-size: 13px; font-weight: 500; cursor: pointer;
            transition: all 0.25s;
        }
        .btn-upload-select:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(184,134,11,0.3);
        }
        .upload-hint { font-size: 12px; color: #a1a1aa; margin-top: 14px; }

        /* Preview */
        .upload-preview { width: 100%; animation: fadeIn 0.35s ease-out; }
        .preview-image-wrap {
            position: relative; width: 100%;
            border-radius: 12px; overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            background: #fafafa;
        }
        .preview-image {
            display: block; width: 100%; max-height: 320px;
            object-fit: contain;
        }
        .preview-remove {
            position: absolute; top: 10px; right: 10px;
            width: 32px; height: 32px;
            background: rgba(0,0,0,0.55);
            color: white; border: none; border-radius: 50%;
            cursor: pointer; font-size: 13px;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s;
            backdrop-filter: blur(4px);
        }
        .preview-remove:hover { background: rgba(220,53,69,0.85); transform: scale(1.1); }
        .preview-info {
            display: flex; align-items: center; justify-content: space-between;
            padding: 10px 14px; margin-top: 10px;
            background: #f8f8fa; border-radius: 10px;
            font-size: 13px; color: #52525b;
        }
        .preview-info-left { display: flex; align-items: center; gap: 8px; }
        .preview-info-left i { color: #b8860b; font-size: 16px; }
        .preview-size { font-weight: 500; color: #71717a; }

        .btn-camera, .btn-capture, .btn-detect {
            padding: 10px 20px; border: none; border-radius: 8px;
            cursor: pointer; font-size: 14px; display: flex; align-items: center; gap: 6px;
            transition: all 0.25s;
        }
        .btn-camera { background: #f0f0f0; color: #333; flex: 1; }
        .btn-camera:hover { background: #e0e0e0; }
        .btn-capture { background: #b8860b; color: white; flex: 1; }
        .btn-capture:hover { background: #a0750a; }
        .btn-capture:disabled { background: #d4d4d8; color: #a1a1aa; cursor: not-allowed; }
        .btn-detect {
            background: linear-gradient(135deg, #b8860b, #a0750a);
            color: white; width: 100%; justify-content: center;
            font-weight: 500;
        }
        .btn-detect:hover { background: linear-gradient(135deg, #a0750a, #8a6508); box-shadow: 0 4px 15px rgba(184,134,11,0.3); }
        .btn-detect:disabled { background: #d4d4d8; color: #a1a1aa; cursor: not-allowed; box-shadow: none; }

        /* Result */
        .deteksi-hasil { margin-top: 20px; }
        .hasil-card {
            display: flex; align-items: center; gap: 18px;
            background: white; border-radius: 14px; padding: 22px 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            animation: fadeIn 0.4s ease-out;
            border: 1px solid #f0f0f0;
        }
        .hasil-icon {
            width: 52px; height: 52px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px;
            background: #f8f8fa;
            flex-shrink: 0;
        }
        .hasil-icon.success { background: rgba(40,167,69,0.1); color: #28a745; }
        .hasil-icon.error { background: rgba(220,53,69,0.1); color: #dc3545; }
        .hasil-icon.warning { background: rgba(184,134,11,0.1); color: #b8860b; }
        .hasil-text { flex: 1; }
        .hasil-text h3 { margin: 0; font-size: 18px; color: #1a1a2e; }
        .hasil-text p { margin: 4px 0 0; color: #71717a; font-size: 14px; }
        .hasil-text .confidence-bar {
            margin-top: 10px; height: 4px;
            background: #f0f0f0; border-radius: 4px; overflow: hidden;
        }
        .hasil-text .confidence-fill {
            height: 100%; border-radius: 4px;
            transition: width 0.6s ease;
        }
        .confidence-fill.high { background: linear-gradient(90deg, #28a745, #5cdb7a); }
        .confidence-fill.medium { background: linear-gradient(90deg, #ffc107, #ffd54f); }
        .confidence-fill.low { background: linear-gradient(90deg, #dc3545, #ff6b6b); }
    </style>
</body>

</html>