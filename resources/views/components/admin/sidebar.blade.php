        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center"  href="{{ route('admin/dashboard') }}">
                {{-- <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div> --}}
                <div class="sidebar-brand-text mx-3">Dashboard Kurban</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('admin/dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Kegiatan Kurban
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Jadwal Pelaksanaan</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="{{ route('admin.pelaksanaan.index') }}">Lihat Jadwal
                            Pelaksanaan</a>
                        <a class="collapse-item" href="{{ route('admin.pelaksanaan.create') }}">Tambah Jadwal</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Ketersediaan Hewan</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="{{ route('admin.ketersediaan-hewan.index') }}">Lihat Ketersediaan
                            Hewan</a>
                        <a class="collapse-item" href="{{ route('admin.ketersediaan-hewan.create') }}">Tambah
                            Ketersediaan</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Peserta
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-dollar-sign"></i>
                    <span>Verifikasi Pembayaran</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Semua Data:</h6>
                        <a class="collapse-item" href="{{ route('admin.order.index') }}">Semua Data</a>
                        <div class="collapse-divider"></div>
                        <h6 class="collapse-header">Kategori Pembayaran:</h6>
                        <a class="collapse-item" href="{{ route('admin.orders.verifikasi') }}">Menunggu Persetujuan</a>
                        <a class="collapse-item" href="{{ route('admin.orders.approved') }}">Disetujui</a>
                        <a class="collapse-item" href="{{ route('admin.orders.rejected') }}">Ditolak</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse"
                    data-target="#collapsePagesPenyembelihan" aria-expanded="true"
                    aria-controls="collapsePagesPenyembelihan">
                    <i class="fas fa-fw fa-cat"></i>
                    <span>Penyembelihan</span>
                </a>
                <div id="collapsePagesPenyembelihan" class="collapse" aria-labelledby="headingPages"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Semua Data:</h6>
                        <a class="collapse-item" href="{{ route('admin.penyembelihan.index') }}">Semua Data</a>
                        <div class="collapse-divider"></div>
                        <h6 class="collapse-header">Kategori Pembayaran:</h6>
                        <a class="collapse-item"
                            href="{{ route('admin.penyembelihans.menunggu-penyembelihan') }}">Menunggu
                            Penyembelihan</a>
                        <a class="collapse-item" href="{{ route('admin.penyembelihans.tersembelih') }}">Tersembelih</a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse"
                    data-target="#collapsePagesDistribusi" aria-expanded="true"
                    aria-controls="collapsePagesDistribusi">
                    <i class="fas fa-fw fa-cat"></i>
                    <span>Pendistribusian</span>
                </a>
                <div id="collapsePagesDistribusi" class="collapse" aria-labelledby="headingPages"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Semua Data:</h6>
                        <a class="collapse-item" href="{{ route('admin.distribusi.index') }}">Semua Data</a>
                        <div class="collapse-divider"></div>
                        <h6 class="collapse-header">Kategori Pembayaran:</h6>
                        <a class="collapse-item"
                            href="{{ route('admin.distributions.perkiraan-penerima') }}">Perhitungan Pendistribusian</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Lain-lain
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse"
                    data-target="#collapseTwoKeuangan" aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-money-bill-wave"></i>
                    <span>Keuangan</span>
                </a>
                <div id="collapseTwoKeuangan" class="collapse" aria-labelledby="headingTwo"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="{{ route('admin.dana-dkm.index') }}">Dana DKM</a>
                        <a class="collapse-item" href="{{ route('admin.dana-operasional.index') }}">Dana Operasional</a>
                        <a class="collapse-item" href="{{ route('admin.bank-penerima.index') }}">Bank Penerima</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Pengguna</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
