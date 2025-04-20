<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #212529;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
        }
        .sidebar .nav-link:hover {
            color: #fff;
        }
        .sidebar .nav-link.active {
            color: #fff;
            background-color: #0d6efd;
        }
        .content-wrapper {
            min-height: calc(100vh - 56px);
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    @auth
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">POS System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->NamaPengguna }} ({{ Auth::user()->Peran }})
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 px-0">
                <div class="sidebar p-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                                href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>
                        @if(Auth::user()->Peran === 'Admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('pengguna.*') ? 'active' : '' }}" 
                                href="{{ route('pengguna.index') }}">
                                <i class="fas fa-users me-2"></i> Pengguna
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('produk.*') ? 'active' : '' }}" href="{{ route('produk.index') }}">
                                <i class="fas fa-box me-2"></i> Produk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}" href="{{ route('pelanggan.index') }}">
                                <i class="fas fa-user-friends me-2"></i> Pelanggan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('penjualan.*') ? 'active' : '' }}" href="{{ route('penjualan.index') }}">
                                <i class="fas fa-shopping-cart me-2"></i> Penjualan
                            </a>
                        </li>
                        @if(Auth::check() && in_array(Auth::user()->Peran, ['Admin', 'Manager']))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
                                    <i class="fas fa-chart-bar me-2"></i> Laporan
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="col-md-10">
                <div class="content-wrapper p-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    @else
        @yield('content')
    @endauth

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>