@extends('layouts.app')

@php
use Illuminate\Support\Facades\Schema;
@endphp

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Detail Produk</h1>
        <div>
            <a href="{{ route('produk.edit', $produk->ProdukID) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="{{ route('produk.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Gambar Produk</h5>
                </div>
                <div class="card-body text-center">
                    @if($produk->Gambar && file_exists(public_path('storage/produk/'.$produk->Gambar)))
                        <img src="{{ asset('storage/produk/'.$produk->Gambar) }}" 
                            alt="{{ $produk->NamaProduk }}" class="img-fluid" style="max-height: 300px;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-image fa-5x text-muted"></i>
                        </div>
                        <p class="text-muted mt-2">Tidak ada gambar</p>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Manajemen Stok</h5>
                </div>
                <div class="card-body">
                    <h2 class="text-center mb-3">
                        @if($produk->Stok <= 0)
                            <span class="badge bg-danger">Stok Habis</span>
                        @elseif($produk->Stok < 10)
                            <span class="badge bg-warning text-dark">Stok Rendah: {{ $produk->Stok }}</span>
                        @else
                            <span class="badge bg-success">Stok Tersedia: {{ $produk->Stok }}</span>
                        @endif
                    </h2>

                    <form action="{{ route('produk.addStock', $produk->ProdukID) }}" method="POST">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="number" name="jumlah" class="form-control" placeholder="Jumlah" min="1" required>
                            <button class="btn btn-success" type="submit">Tambah Stok</button>
                        </div>
                        @error('jumlah')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Informasi Produk</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%">ID Produk</th>
                            <td>{{ $produk->ProdukID }}</td>
                        </tr>
                        <tr>
                            <th>Nama Produk</th>
                            <td>{{ $produk->NamaProduk }}</td>
                        </tr>
                        <tr>
                            <th>Harga</th>
                            <td>Rp {{ number_format($produk->Harga, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Barcode</th>
                            <td>{{ $produk->Barcode ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>{{ $produk->Deskripsi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Dibuat</th>
                            <td>{{ $produk->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Terakhir Diperbarui</th>
                            <td>{{ $produk->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Riwayat Penjualan</h5>
                </div>
                <div class="card-body">
                    @php
                        $hasDetailPenjualan = false;
                        $detailPenjualanExists = false;
                        
                        try {
                            if (Schema::hasTable('detail_penjualan')) {
                                $detailPenjualanExists = true;
                                $hasDetailPenjualan = $produk->detailPenjualan()->exists();
                            }
                        } catch (\Exception $e) {
                            $detailPenjualanExists = false;
                        }
                    @endphp
                    
                    @if (!$detailPenjualanExists)
                        <div class="alert alert-warning">
                            Modul penjualan belum tersedia.
                        </div>
                    @elseif ($hasDetailPenjualan)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID Penjualan</th>
                                        <th>Tanggal</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                        <th>Kasir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($produk->detailPenjualan()->with('penjualan')->latest()->take(5)->get() as $detail)
                                        <tr>
                                            <td>{{ $detail->PenjualanID }}</td>
                                            <td>{{ $detail->penjualan->TanggalPenjualan }}</td>
                                            <td>{{ $detail->JumlahProduk }}</td>
                                            <td>Rp {{ number_format($detail->Subtotal, 0, ',', '.') }}</td>
                                            <td>{{ $detail->penjualan->pengguna->NamaPengguna ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-sm btn-primary">Lihat Semua Riwayat</a>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Produk ini belum memiliki riwayat penjualan.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection