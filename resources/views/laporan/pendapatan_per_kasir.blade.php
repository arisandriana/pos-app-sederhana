@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Laporan Pendapatan Per Kasir</h1>
        <div>
            <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('laporan.pendapatan.kasir') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ $tanggalMulai }}">
                </div>
                <div class="col-md-4">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                    <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="{{ $tanggalSelesai }}">
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
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Grafik Jumlah Transaksi</h5>
                </div>
                <div class="card-body">
                    <canvas id="transactionChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Grafik Pendapatan</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Pendapatan Per Kasir -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Pendapatan Per Kasir ({{ date('d M Y', strtotime($tanggalMulai)) }} - {{ date('d M Y', strtotime($tanggalSelesai)) }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover" id="pendapatan-table">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>Nama Kasir</th>
                            <th>Jumlah Transaksi</th>
                            <th>Total Pendapatan</th>
                            <th>Rata-rata per Transaksi</th>
                            <th>Kontribusi (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalAllTransactions = $pendapatanPerKasir->sum('total_transaksi');
                            $totalAllRevenue = $pendapatanPerKasir->sum('total_pendapatan');
                        @endphp
                        
                        @forelse ($pendapatanPerKasir as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->NamaPengguna }}</td>
                                <td>{{ $item->total_transaksi }}</td>
                                <td>Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->total_pendapatan / $item->total_transaksi, 0, ',', '.') }}</td>
                                <td>
                                    {{ number_format(($item->total_pendapatan / $totalAllRevenue) * 100, 2) }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data transaksi pada periode ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-secondary">
                        <tr>
                            <th colspan="2" class="text-end">Total:</th>
                            <th>{{ $totalAllTransactions }}</th>
                            <th>Rp {{ number_format($totalAllRevenue, 0, ',', '.') }}</th>
                            <th>Rp {{ $totalAllTransactions > 0 ? number_format($totalAllRevenue / $totalAllTransactions, 0, ',', '.') : 0 }}</th>
                            <th>100%</th>
                        </tr>
                    </tfoot>
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
        // Data untuk charts
        const labels = {!! json_encode($pendapatanPerKasir->pluck('NamaPengguna')) !!};
        const dataTransactions = {!! json_encode($pendapatanPerKasir->pluck('total_transaksi')) !!};
        const dataRevenue = {!! json_encode($pendapatanPerKasir->pluck('total_pendapatan')) !!};
        
        // Random colors
        const backgroundColors = generateColors(labels.length);
        
        // Chart Jumlah Transaksi
        const ctxTransactions = document.getElementById('transactionChart').getContext('2d');
        new Chart(ctxTransactions, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: dataTransactions,
                    backgroundColor: backgroundColors,
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
                        text: 'Distribusi Jumlah Transaksi per Kasir'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} transaksi (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
        
        // Chart Pendapatan
        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRevenue, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: dataRevenue,
                    backgroundColor: backgroundColors,
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
                        text: 'Distribusi Pendapatan per Kasir'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${formatCurrency(value)} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
        
        // Fungsi untuk membuat warna random
        function generateColors(count) {
            const colors = [];
            const baseColors = [
                'rgba(255, 99, 132, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(153, 102, 255, 0.7)',
                'rgba(255, 159, 64, 0.7)',
                'rgba(199, 199, 199, 0.7)',
                'rgba(83, 102, 255, 0.7)',
                'rgba(40, 159, 64, 0.7)',
                'rgba(210, 199, 199, 0.7)',
            ];
            
            for (let i = 0; i < count; i++) {
                if (i < baseColors.length) {
                    colors.push(baseColors[i]);
                } else {
                    const r = Math.floor(Math.random() * 255);
                    const g = Math.floor(Math.random() * 255);
                    const b = Math.floor(Math.random() * 255);
                    colors.push(`rgba(${r}, ${g}, ${b}, 0.7)`);
                }
            }
            
            return colors;
        }
        
        // Format currency
        function formatCurrency(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(value);
        }
    });
    
    function exportToPDF() {
        window.location.href = "{{ route('laporan.export.pdf') }}?jenis=pendapatan_kasir&tanggal_mulai={{ $tanggalMulai }}&tanggal_selesai={{ $tanggalSelesai }}";
    }
    
    function exportToExcel() {
        window.location.href = "{{ route('laporan.export.excel') }}?jenis=pendapatan_kasir&tanggal_mulai={{ $tanggalMulai }}&tanggal_selesai={{ $tanggalSelesai }}";
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