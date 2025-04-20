@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Laporan Pelanggan Terbaik</h1>
        <div>
            <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('laporan.pelanggan.terbaik') }}" method="GET" class="row g-3">
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
            <h5 class="mb-0">Grafik Pelanggan Terbaik</h5>
        </div>
        <div class="card-body">
            <div class="chart-container" style="position: relative; height:400px;">
                <canvas id="customerChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Tabel Pelanggan Terbaik -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Data Pelanggan Terbaik ({{ date('d M Y', strtotime($tanggalMulai)) }} - {{ date('d M Y', strtotime($tanggalSelesai)) }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover" id="pelanggan-table">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>Nama Pelanggan</th>
                            <th>Nomor Telepon</th>
                            <th>Jumlah Transaksi</th>
                            <th>Total Belanja</th>
                            <th>Rata-rata per Transaksi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pelangganTerbaik as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->NamaPelanggan }}</td>
                                <td>{{ $item->NomorTelepon ?? '-' }}</td>
                                <td>{{ $item->total_transaksi }}</td>
                                <td>Rp {{ number_format($item->total_belanja, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->total_belanja / $item->total_transaksi, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('pelanggan.show', $item->PelangganID) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data pelanggan pada periode ini</td>
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
        const labels = {!! json_encode($pelangganTerbaik->pluck('NamaPelanggan')) !!};
        const dataTransactions = {!! json_encode($pelangganTerbaik->pluck('total_transaksi')) !!};
        const dataSpending = {!! json_encode($pelangganTerbaik->pluck('total_belanja')) !!};
        
        // Buat chart
        const ctx = document.getElementById('customerChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Jumlah Transaksi',
                        data: dataTransactions,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgb(75, 192, 192)',
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Total Belanja (Rp)',
                        data: dataSpending,
                        backgroundColor: 'rgba(153, 102, 255, 0.5)',
                        borderColor: 'rgb(153, 102, 255)',
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
                            text: 'Jumlah Transaksi'
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
                            text: 'Total Belanja (Rp)'
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
                                
                                if (label.includes('Belanja')) {
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
        window.location.href = "{{ route('laporan.export.pdf') }}?jenis=pelanggan_terbaik&tanggal_mulai={{ $tanggalMulai }}&tanggal_selesai={{ $tanggalSelesai }}&limit={{ $limit }}";
    }
    
    function exportToExcel() {
        window.location.href = "{{ route('laporan.export.excel') }}?jenis=pelanggan_terbaik&tanggal_mulai={{ $tanggalMulai }}&tanggal_selesai={{ $tanggalSelesai }}&limit={{ $limit }}";
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