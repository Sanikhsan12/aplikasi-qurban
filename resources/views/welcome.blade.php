<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kurban - Sistem Pengelolaan Ibadah Kurban</title>
    <meta name="description"
        content="Sistem manajemen kurban untuk pengelolaan hewan kurban, pendaftaran peserta, dan distribusi daging kurban secara transparan dan mudah.">
    <meta name="keywords" content="kurban, qurban, idul adha, manajemen kurban, hewan kurban, daging kurban">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
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
</body>

</html>