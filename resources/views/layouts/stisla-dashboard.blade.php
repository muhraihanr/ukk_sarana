<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title', 'Dashboard Siswa')</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">


    @stack('styles')
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <!-- Top Navbar -->
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                <form class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
                        <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
                    </ul>
                    <!-- <div class="search-form">
                        <input type="text" class="form-control" placeholder="Cari laporan...">
                        <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div> -->
                </form>
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
                            <div class="d-sm-none d-lg-inline-block">
                                Hi, {{ session('admin_username') ?? session('siswa_nama') }}
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger"> <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

            <<!-- Sidebar -->
                <div class="main-sidebar sidebar-style-2">
                    <aside id="sidebar-wrapper">
                        <div class="sidebar-brand">
                            <a href="{{ session('admin_id') ? route('dashboard.admin') : route('dashboard.siswa') }}">
                                Laporan Sarana
                            </a>
                        </div>
                        <div class="sidebar-brand sidebar-brand-sm">
                            <a href="{{ session('admin_id') ? route('dashboard.admin') : route('dashboard.siswa') }}">LS</a>
                        </div>

                        @if(session('user_role') === 'admin')
                        <!-- Menu Admin -->
                        <ul class="sidebar-menu">
                            <li class="menu-header">Dashboard Admin</li>
                            <li class="{{ request()->routeIs('dashboard.admin') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboard.admin') }}">
                                    <i class="fas fa-home"></i> <span>Dashboard</span>
                                </a>
                            </li>
                            <li class="menu-header">Manajemen Laporan</li>
                            <li class="{{ request()->routeIs('admin.laporan.index') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.laporan.index') }}">
                                    <i class="fas fa-list"></i> <span>Semua Laporan</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('admin.laporan.masuk') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.laporan.masuk') }}">
                                    <i class="fas fa-inbox"></i> <span>Laporan Masuk</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('admin.laporan.diproses') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.laporan.diproses') }}">
                                    <i class="fas fa-cogs"></i> <span>Laporan Diproses</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('admin.laporan.selesai') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.laporan.selesai') }}">
                                    <i class="fas fa-check-circle"></i> <span>Laporan Selesai</span>
                                </a>
                            </li>
                            <li class="menu-header">Data Siswa</li>
                            <li class="{{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.siswa.index') }}">
                                    <i class="fas fa-users"></i> <span>Data Siswa</span>
                                </a>
                            </li>
                            <li class="menu-header">Data Kategori</li>
                            <li class="{{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.kategori.index') }}">
                                    <i class="fas fa-tags"></i> <span>Data Kategori</span>
                                </a>
                            </li>
                        </ul>

                        @elseif(session('user_role') === 'siswa')
                        <!-- Menu Siswa -->
                        <ul class="sidebar-menu">
                            <li class="menu-header">Menu Utama</li>
                            <li class="{{ request()->routeIs('dashboard.siswa') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboard.siswa') }}">
                                    <i class="fas fa-home"></i> <span>Dashboard</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('pelaporan.create') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('pelaporan.create') }}">
                                    <i class="fas fa-bullhorn"></i> <span>Laporkan Sarana</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('riwayat.siswa') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('riwayat.siswa') }}">
                                    <i class="fas fa-history"></i> <span>Riwayat Laporan</span>
                                </a>
                            </li>


                        </ul>
                        @endif
                    </aside>
                </div>

                <!-- Main Content -->
                <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h1>@yield('header', 'Dashboard')</h1>
                        </div>
                        <div class="section-body">
                            @yield('content')
                        </div>
                    </section>
                </div>

                <!-- Footer -->
                <footer class="main-footer">
                    <div class="footer-left">
                        Copyright &copy; {{ date('Y') }}
                        <div class="bullet"></div> Laporan Sarana Sekolah
                    </div>
                    <div class="footer-right">
                        v1.0
                    </div>
                </footer>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="{{ asset('assets/js/stisla.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')
</body>

</html>