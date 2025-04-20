@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4">Dashboard</h1>
    
    <!-- Kartu Ringkasan -->
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Produk</h5>
                            <h2 class="display-4 fw-bold">{{ $totalProduk }}</h2>
                        </div>
                        <i class="fas fa-box fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('produk.index') }}" class="text-white text-decoration-none">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Penjualan</h5>
                            <h2 class="display-4 fw-bold">{{ $totalPenjualan }}</h2>
                        </div>
                        <i class="fas fa-shopping-cart fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="#" class="text-white text-decoration-none">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Pelanggan</h5>
                            <h2 class="display-4 fw-bold">{{ $totalPelanggan }}</h2>
                        </div>
                        <i class="fas fa-user-friends fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('pelanggan.index') }}" class="text-white text-decoration-none">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-warning text-dark h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Pengguna</h5>
                            <h2 class="display-4 fw-bold">{{ $totalPengguna }}</h2>
                        </div>
                        <i class="fas fa-users fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('pengguna.index') }}" class="text-white text-decoration-none">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik dan Tabel -->
    <div class="row">
        <!-- Grafik Penjualan -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Grafik Penjualan Tahun {{ date('Y') }}</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Stok Produk Rendah -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Stok Produk Rendah</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($produkStokRendah as $produk)
                                <tr>
                                    <td>{{ $produk->NamaProduk }}</td>
                                    <td class="text-center">
                                        @if($produk->Stok <= 0)
                                            <span class="badge bg-danger">Habis</span>
                                        @else
                                            <span class="badge bg-warning text-dark">{{ $produk->Stok }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center">Semua stok produk mencukupi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(count($produkStokRendah) > 0)
                <div class="card-footer text-center">
                    <a href="{{ route('produk.filter', 'low') }}" class="btn btn-sm btn-danger">Lihat Semua</a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Penjualan Terakhir -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-bag me-2"></i>Penjualan Terakhir</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Total</th>
                                    <th>Kasir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penjualanTerakhir as $penjualan)
                                <tr>
                                    <td>{{ $penjualan->PenjualanID }}</td>
                                    <td>
                                        @if(is_object($penjualan->TanggalPenjualan))
                                            {{ $penjualan->TanggalPenjualan->format('d/m/Y H:i') }}
                                        @else
                                            {{ $penjualan->TanggalPenjualan }}
                                        @endif
                                    </td>
                                    <td>{{ $penjualan->pelanggan->NamaPelanggan ?? 'Umum' }}</td>
                                    <td>Rp {{ number_format($penjualan->TotalHarga, 0, ',', '.') }}</td>
                                    <td>{{ $penjualan->pengguna->NamaPengguna ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data penjualan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(count($penjualanTerakhir) > 0)
                <div class="card-footer text-center">
                    <a href="#" class="btn btn-sm btn-success">Lihat Semua</a>
                </div>
                @endif
            </div>
        </div>

        <!-- Produk Terlaris -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Produk Terlaris</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProduk as $produk)
                                <tr>
                                    <td>{{ $produk->NamaProduk }}</td>
                                    <td class="text-center">{{ $produk->total_terjual }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center">Belum ada data penjualan produk</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Data dari controller
    var chartData = @json($chartData);
    
    // Siapkan data untuk chart.js
    var labels = chartData.map(item => item.bulan);
    var data = chartData.map(item => item.total);
    
    // Buat grafik
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        // Format angka dengan separator ribuan
                        callback: function(value) {
                            return new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                            }).format(value);
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                            }).format(context.raw);
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush