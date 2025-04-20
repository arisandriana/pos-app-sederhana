<!-- Ringkasan Penjualan -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase">Total Penjualan</h6>
                        <h2 class="mb-0">Rp {{ number_format($total, 0, ',', '.') }}</h2>
                    </div>
                    <i class="fas fa-money-bill-wave fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase">Jumlah Transaksi</h6>
                        <h2 class="mb-0">{{ $jumlahTransaksi }}</h2>
                    </div>
                    <i class="fas fa-receipt fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase">Rata-rata per Hari</h6>
                        <h2 class="mb-0">Rp {{ count($dataPerTanggal) > 0 ? number_format($total / count($dataPerTanggal), 0, ',', '.') : 0 }}</h2>
                    </div>
                    <i class="fas fa-chart-line fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Penjualan -->
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Grafik Penjualan {{ date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)) }}</h5>
    </div>
    <div class="card-body">
        <canvas id="salesChart" height="300"></canvas>
    </div>
</div>

<!-- Tabel Penjualan Per Tanggal -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Data Penjualan Harian</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover" id="penjualan-table">
                <thead class="table-dark">
                    <tr>
                        <th>Tanggal</th>
                        <th>Jumlah Transaksi</th>
                        <th>Total Penjualan</th>
                        <th>Rata-rata per Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataPerTanggal as $data)
                        <tr>
                            <td>{{ date('d F Y', strtotime($data['tanggal'])) }}</td>
                            <td>{{ $data['jumlah_transaksi'] }}</td>
                            <td>Rp {{ number_format($data['total'], 0, ',', '.') }}</td>
                            <td>Rp {{ $data['jumlah_transaksi'] > 0 ? number_format($data['total'] / $data['jumlah_transaksi'], 0, ',', '.') : 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada transaksi pada bulan ini</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-secondary">
                    <tr>
                        <th>Total</th>
                        <th>{{ $jumlahTransaksi }}</th>
                        <th>Rp {{ number_format($total, 0, ',', '.') }}</th>
                        <th></th>
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
    // Data untuk chart
    const labels = {!! json_encode(array_column($dataPerTanggal, 'tanggal')) !!};
    const data = {!! json_encode(array_column($dataPerTanggal, 'total')) !!};
    
    // Formatting tanggal
    const formattedLabels = labels.map(date => {
        const d = new Date(date);
        return d.getDate(); // Hanya tampilkan tanggal (1-31)
    });
    
    // Buat chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: formattedLabels,
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
                        callback: function(value) {
                            return new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                            }).format(value);
                        }
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal'
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

function exportToPDF() {
    window.location.href = "{{ route('laporan.export.pdf') }}?jenis=bulanan&bulan={{ $bulan }}&tahun={{ $tahun }}";
}

function exportToExcel() {
    window.location.href = "{{ route('laporan.export.excel') }}?jenis=bulanan&bulan={{ $bulan }}&tahun={{ $tahun }}";
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
@endpush@extends('layouts.app')

@section('content')
<div class="container-fluid">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Laporan Penjualan Bulanan</h1>
    <div>
        <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<!-- Filter Bulan -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('laporan.penjualan.bulanan') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="bulan" class="form-label">Pilih Bulan</label>
                <select class="form-select" id="bulan" name="bulan">
                    @foreach(range(1, 12) as $b)
                        <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $b, 1)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="tahun" class="form-label">Pilih Tahun</label>
                <select class="form-select" id="tahun" name="tahun">
                    @foreach(range(date('Y'), date('Y')-5, -1) as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-1"></i> Tampilkan
                </button>
            </div>
            <div class="col-md-4 d-flex align-items-end justify-content-end">
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