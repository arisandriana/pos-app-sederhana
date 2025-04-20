@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Laporan Produk Terlaris</h1>
        <div>
            <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('laporan.produk.terlaris') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ $tanggalMulai }}">
                </div>
                <div class="col-md-3">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                    <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="{{ $tanggalSelesai }}">
                </div>
                <div class="col-md-2">
                    <label for="limit" class="form-label">Jumlah Data</label>
                    <select class="form-select" id="limit" name="limit">
                        <option value="5" {{ $limit == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ $limit == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $limit == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $limit == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter me-1"></i> Tampilkan
                    </button>
                    
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
            </form>
        </div>
    </div>

    <!-- Grafik -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Grafik Produk Terlaris</h5>
        </div>
        <div class="card-body">
            <div class="chart-container" style="position: relative; height:400px;">
                <canvas id="productChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Tabel Produk Terlaris -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Data Produk Terlaris ({{ date('d M Y', strtotime($tanggalMulai)) }} - {{ date('d M Y', strtotime($tanggalSelesai)) }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover" id="produk-table">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Jumlah Terjual</th>
                            <th>Total Pendapatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($produkTerlaris as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->NamaProduk }}</td>
                                <td>Rp {{ number_format($item->Harga, 0, ',', '.') }}</td>
                                <td>{{ $item->total_terjual }}</td>
                                <td>Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('produk.show', $item->ProdukID) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data produk terjual pada periode ini</td>
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
        // Data untuk chart
        const labels = {!! json_encode($produkTerlaris->pluck('NamaProduk')) !!};
        const dataSales = {!! json_encode($produkTerlaris->pluck('total_terjual')) !!};
        const dataRevenue = {!! json_encode($produkTerlaris->pluck('total_pendapatan')) !!};
        
        // Buat chart
        const ctx = document.getElementById('productChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Jumlah Terjual',
                        data: dataSales,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgb(54, 162, 235)',
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Pendapatan (Rp)',
                        data: dataRevenue,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgb(255, 99, 132)',
                        borderWidth: 1,
                        type: 'line',
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Jumlah Terjual'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                        title: {
                            display: true,
                            text: 'Pendapatan (Rp)'
                        },
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    minimumFractionDigits: 0
                                }).format(value);
                            }
                        }
                    },
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                let value = context.raw;
                                
                                if (label.includes('Pendapatan')) {
                                    return label + ': ' + new Intl.NumberFormat('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR',
                                        minimumFractionDigits: 0
                                    }).format(value);
                                } else {
                                    return label + ': ' + value;
                                }
                            }
                        }
                    }
                }
            }
        });
    });
    
    function exportToPDF() {
        window.location.href = "{{ route('laporan.export.pdf') }}?jenis=produk_terlaris&tanggal_mulai={{ $tanggalMulai }}&tanggal_selesai={{ $tanggalSelesai }}&limit={{ $limit }}";
    }
    
    function exportToExcel() {
        window.location.href = "{{ route('laporan.export.excel') }}?jenis=produk_terlaris&tanggal_mulai={{ $tanggalMulai }}&tanggal_selesai={{ $tanggalSelesai }}&limit={{ $limit }}";
    }
</script>
@endpush

@push('styles')
<style>
    @media print {
        .navbar, .sidebar, form, .btn, footer {
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