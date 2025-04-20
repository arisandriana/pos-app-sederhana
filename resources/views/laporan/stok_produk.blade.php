@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Laporan Stok Produk</h1>
        <div>
            <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Tombol Export -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-success" onclick="exportToExcel()">
                        <i class="fas fa-file-excel me-1"></i> Excel
                    </button>
                    <button type="button" class="btn btn-outline-danger" onclick="exportToPDF()">
                        <i class="fas fa-file-pdf me-1"></i> PDF
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan Stok -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Stok Habis</h6>
                            <h2 class="mb-0">{{ $stokHabis }}</h2>
                        </div>
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Stok Rendah</h6>
                            <h2 class="mb-0">{{ $stokRendah }}</h2>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Stok Aman</h6>
                            <h2 class="mb-0">{{ $stokAman }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Stok -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Grafik Status Stok</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <canvas id="stockStatusChart" height="300"></canvas>
                </div>
                <div class="col-md-6">
                    <canvas id="topLowStockChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Stok Produk -->
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Stok Produk</h5>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-danger filter-btn" data-filter="habis">Stok Habis</button>
                <button type="button" class="btn btn-sm btn-outline-warning filter-btn" data-filter="rendah">Stok Rendah</button>
                <button type="button" class="btn btn-sm btn-outline-success filter-btn" data-filter="aman">Stok Aman</button>
                <button type="button" class="btn btn-sm btn-outline-primary filter-btn" data-filter="semua">Semua</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover" id="produk-table">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Produk</th>
                            <th>Barcode</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($produk as $item)
                            <tr class="product-row {{ $item->Stok <= 0 ? 'stok-habis' : ($item->Stok <= 10 ? 'stok-rendah' : 'stok-aman') }}">
                                <td>{{ $item->ProdukID }}</td>
                                <td>{{ $item->NamaProduk }}</td>
                                <td>{{ $item->Barcode ?? '-' }}</td>
                                <td>Rp {{ number_format($item->Harga, 0, ',', '.') }}</td>
                                <td>{{ $item->Stok }}</td>
                                <td>
                                    @if($item->Stok <= 0)
                                        <span class="badge bg-danger">Habis</span>
                                    @elseif($item->Stok <= 10)
                                        <span class="badge bg-warning text-dark">Rendah</span>
                                    @else
                                        <span class="badge bg-success">Aman</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('produk.show', $item->ProdukID) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data produk</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data untuk chart status stok
        const stockStatusLabels = ['Stok Habis', 'Stok Rendah', 'Stok Aman'];
        const stockStatusData = [{{ $stokHabis }}, {{ $stokRendah }}, {{ $stokAman }}];
        const stockStatusColors = ['#dc3545', '#ffc107', '#28a745'];
        
        // Buat chart status stok
        const ctxStatus = document.getElementById('stockStatusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'pie',
            data: {
                labels: stockStatusLabels,
                datasets: [{
                    data: stockStatusData,
                    backgroundColor: stockStatusColors,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                        text: 'Distribusi Status Stok'
                    }
                }
            }
        });
        
        // Data untuk chart produk dengan stok rendah
        const lowStockProducts = {!! json_encode($produk->where('Stok', '>', 0)->where('Stok', '<=', 10)->sortBy('Stok')->take(10)->pluck('NamaProduk')) !!};
        const lowStockCounts = {!! json_encode($produk->where('Stok', '>', 0)->where('Stok', '<=', 10)->sortBy('Stok')->take(10)->pluck('Stok')) !!};
        
        // Buat chart produk dengan stok rendah
        const ctxLowStock = document.getElementById('topLowStockChart').getContext('2d');
        new Chart(ctxLowStock, {
            type: 'bar',
            data: {
                labels: lowStockProducts,
                datasets: [{
                    label: 'Jumlah Stok',
                    data: lowStockCounts,
                    backgroundColor: 'rgba(255, 193, 7, 0.5)',
                    borderColor: '#ffc107',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Produk dengan Stok Rendah'
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Stok'
                        }
                    }
                }
            }
        });
        
        // Filter tabel
        const filterButtons = document.querySelectorAll('.filter-btn');
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                const rows = document.querySelectorAll('.product-row');
                
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                rows.forEach(row => {
                    if (filter === 'semua') {
                        row.style.display = '';
                    } else if (filter === 'habis' && row.classList.contains('stok-habis')) {
                        row.style.display = '';
                    } else if (filter === 'rendah' && row.classList.contains('stok-rendah')) {
                        row.style.display = '';
                    } else if (filter === 'aman' && row.classList.contains('stok-aman')) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    });
    
    function exportToPDF() {
        window.location.href = "{{ route('laporan.export.pdf') }}?jenis=stok_produk";
    }
    
    function exportToExcel() {
        window.location.href = "{{ route('laporan.export.excel') }}?jenis=stok_produk";
    }
</script>
@endpush

@push('styles')
<style>
    .filter-btn.active {
        font-weight: bold;
    }
    
    @media print {
        .navbar, .sidebar, form, .btn, footer, .filter-btn {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .container-fluid {
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
    }
</style>
@endpush