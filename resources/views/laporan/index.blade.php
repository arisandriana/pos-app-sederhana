@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4">Laporan</h1>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Laporan Penjualan</h5>
                </div>
                <div class="card-body">
                    <p>Laporan penjualan berdasarkan periode waktu.</p>
                    <div class="list-group">
                        <a href="{{ route('laporan.penjualan.harian') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-calendar-day me-2"></i> Penjualan Harian
                        </a>
                        <a href="{{ route('laporan.penjualan.bulanan') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-calendar-alt me-2"></i> Penjualan Bulanan
                        </a>
                        <a href="{{ route('laporan.penjualan.periode') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-calendar-week me-2"></i> Penjualan Per Periode
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Laporan Produk</h5>
                </div>
                <div class="card-body">
                    <p>Laporan terkait produk dan inventori.</p>
                    <div class="list-group">
                        <a href="{{ route('laporan.produk.terlaris') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-line me-2"></i> Produk Terlaris
                        </a>
                        <a href="{{ route('laporan.stok.produk') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-boxes me-2"></i> Stok Produk
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Laporan Lainnya</h5>
                </div>
                <div class="card-body">
                    <p>Laporan tambahan dan analisis.</p>
                    <div class="list-group">
                        <a href="{{ route('laporan.pelanggan.terbaik') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-users me-2"></i> Pelanggan Terbaik
                        </a>
                        <a href="{{ route('laporan.pendapatan.kasir') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-user-tie me-2"></i> Pendapatan Per Kasir
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection