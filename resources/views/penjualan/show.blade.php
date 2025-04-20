@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Detail Penjualan</h1>
        <div>
            <a href="{{ route('penjualan.print', $penjualan->PenjualanID) }}" class="btn btn-success me-2" target="_blank">
                <i class="fas fa-print me-1"></i> Cetak Nota
            </a>
            <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informasi Penjualan -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Transaksi</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="35%">No. Invoice</th>
                            <td>: {{ 'INV-' . str_pad($penjualan->PenjualanID, 6, '0', STR_PAD_LEFT) }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>: 
                                @if(is_object($penjualan->TanggalPenjualan))
                                    {{ $penjualan->TanggalPenjualan->format('d/m/Y H:i') }}
                                @else
                                    {{ $penjualan->TanggalPenjualan }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Kasir</th>
                            <td>: {{ $penjualan->pengguna->NamaPengguna ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Pelanggan</th>
                            <td>: {{ $penjualan->pelanggan->NamaPelanggan ?? 'Umum' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>: 
                                @if($penjualan->Status === 'selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif($penjualan->Status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($penjualan->Status === 'batal')
                                    <span class="badge bg-danger">Batal</span>
                                @endif
                            </td>
                        </tr>
                        @if($penjualan->Catatan)
                        <tr>
                            <th>Catatan</th>
                            <td>: {{ $penjualan->Catatan }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <!-- Ringkasan Pembayaran -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Informasi Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded">
                                <p class="mb-1">Total Belanja</p>
                                <h3 class="mb-0">Rp {{ number_format($penjualan->TotalHarga, 0, ',', '.') }}</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded">
                                <p class="mb-1">Jumlah Item</p>
                                <h3 class="mb-0">{{ $penjualan->detailPenjualan->sum('JumlahProduk') }} Item</h3>
                            </div>
                        </div>
                    </div>
                    <table class="table table-borderless">
                        <tr>
                            <th width="35%">Bayar</th>
                            <td class="text-end">Rp {{ number_format($penjualan->Bayar, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Kembalian</th>
                            <td class="text-end">Rp {{ number_format($penjualan->Kembali, 0, ',', '.') }}</td>
                        </tr>
                    </table>

                    @if($penjualan->Status !== 'batal')
                    <div class="d-grid gap-2 mt-3">
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            <i class="fas fa-times-circle me-1"></i> Batalkan Transaksi
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Produk -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-shopping-basket me-2"></i>Detail Produk</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th width="40%">Produk</th>
                            <th width="15%">Harga</th>
                            <th width="10%">Jumlah</th>
                            <th width="15%">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penjualan->detailPenjualan as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $detail->produk->NamaProduk ?? 'Produk tidak ditemukan' }}</td>
                            <td>Rp {{ number_format($detail->HargaSatuan, 0, ',', '.') }}</td>
                            <td>{{ $detail->JumlahProduk }}</td>
                            <td>Rp {{ number_format($detail->Subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-secondary">
                        <tr>
                            <th colspan="3" class="text-end">Total</th>
                            <th>{{ $penjualan->detailPenjualan->sum('JumlahProduk') }}</th>
                            <th>Rp {{ number_format($penjualan->TotalHarga, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Pembatalan -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Konfirmasi Pembatalan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin membatalkan transaksi ini?</p>
                <p class="text-danger"><strong>Perhatian:</strong> Pembatalan akan mengembalikan stok produk yang sudah terjual.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <form action="{{ route('penjualan.cancel', $penjualan->PenjualanID) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Ya, Batalkan Transaksi</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection