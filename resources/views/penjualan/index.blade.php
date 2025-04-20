@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Daftar Penjualan</h1>
        <a href="{{ route('penjualan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Transaksi Baru
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-white">
            <form action="{{ route('penjualan.search') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Dari Tanggal</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $start_date ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $end_date ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua</option>
                        <option value="selesai" {{ isset($status) && $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="pending" {{ isset($status) && $status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="batal" {{ isset($status) && $status == 'batal' ? 'selected' : '' }}>Batal</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="invoice" class="form-label">No. Invoice</label>
                    <input type="text" class="form-control" id="invoice" name="invoice" placeholder="Cari nomor invoice..." value="{{ $invoice ?? '' }}">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>No. Invoice</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Kasir</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penjualan as $item)
                            <tr>
                                <td>{{ 'INV-' . str_pad($item->PenjualanID, 6, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    @if(is_object($item->TanggalPenjualan))
                                        {{ $item->TanggalPenjualan->format('d/m/Y H:i') }}
                                    @else
                                        {{ $item->TanggalPenjualan }}
                                    @endif
                                </td>
                                <td>{{ $item->pelanggan->NamaPelanggan ?? 'Umum' }}</td>
                                <td>Rp {{ number_format($item->TotalHarga, 0, ',', '.') }}</td>
                                <td>{{ $item->pengguna->NamaPengguna ?? '-' }}</td>
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
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('penjualan.show', $item->PenjualanID) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('penjualan.print', $item->PenjualanID) }}" class="btn btn-sm btn-secondary" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data penjualan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($penjualan->count() > 0)
            <div class="card-footer">
                {{ $penjualan->links() }}
            </div>
        @endif
    </div>
</div>
@endsection