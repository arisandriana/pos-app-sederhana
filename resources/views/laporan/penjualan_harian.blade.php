@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Laporan Penjualan Harian</h1>
        <div>
            <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Filter Tanggal -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('laporan.penjualan.harian') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="tanggal" class="form-label">Pilih Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ $tanggal }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
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
                            <h6 class="text-uppercase">Rata-rata per Transaksi</h6>
                            <h2 class="mb-0">Rp {{ $jumlahTransaksi > 0 ? number_format($total / $jumlahTransaksi, 0, ',', '.') : 0 }}</h2>
                        </div>
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Penjualan -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Daftar Transaksi ({{ date('d F Y', strtotime($tanggal)) }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover" id="penjualan-table">
                    <thead class="table-dark">
                        <tr>
                            <th>No. Invoice</th>
                            <th>Waktu</th>
                            <th>Pelanggan</th>
                            <th>Kasir</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penjualan as $item)
                            <tr>
                                <td>{{ 'INV-' . str_pad($item->PenjualanID, 6, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ date('H:i:s', strtotime($item->TanggalPenjualan)) }}</td>
                                <td>{{ $item->pelanggan->NamaPelanggan ?? 'Umum' }}</td>
                                <td>{{ $item->pengguna->NamaPengguna ?? '-' }}</td>
                                <td>Rp {{ number_format($item->TotalHarga, 0, ',', '.') }}</td>
                                <td>
                                    @if($item->Status === 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($item->Status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($item->Status === 'batal')
                                        <span class="badge bg-danger">Batal</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('penjualan.show', $item->PenjualanID) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada transaksi pada tanggal ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-secondary">
                        <tr>
                            <th colspan="4" class="text-end">Total Penjualan:</th>
                            <th>Rp {{ number_format($total, 0, ',', '.') }}</th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function exportToPDF() {
        window.location.href = "{{ route('laporan.export.pdf') }}?jenis=harian&tanggal={{ $tanggal }}";
    }
    
    function exportToExcel() {
        window.location.href = "{{ route('laporan.export.excel') }}?jenis=harian&tanggal={{ $tanggal }}";
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