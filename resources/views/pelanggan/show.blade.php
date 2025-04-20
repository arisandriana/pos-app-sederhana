@extends('layouts.app')

@php
use Illuminate\Support\Facades\Schema;
@endphp

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Detail Pelanggan</h1>
        <div>
            <a href="{{ route('pelanggan.edit', $pelanggan->PelangganID) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Pelanggan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 35%">ID Pelanggan</th>
                            <td>{{ $pelanggan->PelangganID }}</td>
                        </tr>
                        <tr>
                            <th>Nama Pelanggan</th>
                            <td>{{ $pelanggan->NamaPelanggan }}</td>
                        </tr>
                        <tr>
                            <th>Nomor Telepon</th>
                            <td>{{ $pelanggan->NomorTelepon ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $pelanggan->Email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $pelanggan->Alamat ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Catatan</th>
                            <td>{{ $pelanggan->Catatan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Registrasi</th>
                            <td>{{ $pelanggan->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Terakhir Diperbarui</th>
                            <td>{{ $pelanggan->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Riwayat Transaksi</h5>
                </div>
                <div class="card-body">
                    @php
                        $hasPenjualan = false;
                        $penjualanExists = false;
                        
                        try {
                            if (Schema::hasTable('penjualan')) {
                                $penjualanExists = true;
                                $hasPenjualan = $pelanggan->penjualan()->exists();
                            }
                        } catch (\Exception $e) {
                            $penjualanExists = false;
                        }
                    @endphp
                    
                    @if (!$penjualanExists)
                        <div class="alert alert-warning">
                            Modul penjualan belum tersedia.
                        </div>
                    @elseif ($hasPenjualan)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID Penjualan</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Kasir</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pelanggan->penjualan()->latest()->take(5)->get() as $penjualan)
                                        <tr>
                                            <td>{{ $penjualan->PenjualanID }}</td>
                                            <td>
                                                @if(is_object($penjualan->TanggalPenjualan))
                                                    {{ $penjualan->TanggalPenjualan->format('d/m/Y H:i') }}
                                                @else
                                                    {{ $penjualan->TanggalPenjualan }}
                                                @endif
                                            </td>
                                            <td>Rp {{ number_format($penjualan->TotalHarga, 0, ',', '.') }}</td>
                                            <td>{{ $penjualan->pengguna->NamaPengguna ?? '-' }}</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-sm btn-primary">Lihat Semua Transaksi</a>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Pelanggan ini belum memiliki transaksi.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Tindakan Cepat</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('pelanggan.edit', $pelanggan->PelangganID) }}" class="btn btn-warning w-100 mb-3">
                        <i class="fas fa-edit me-1"></i> Edit Data Pelanggan
                    </a>
                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-danger w-100 mb-3" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash me-1"></i> Hapus Pelanggan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus pelanggan <strong>{{ $pelanggan->NamaPelanggan }}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('pelanggan.destroy', $pelanggan->PelangganID) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection