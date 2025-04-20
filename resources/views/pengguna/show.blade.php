@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Detail Pengguna</h1>
        <div>
            <a href="{{ route('pengguna.edit', $pengguna->PenggunaID) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="{{ route('pengguna.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Pengguna</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 35%">ID Pengguna</th>
                            <td>{{ $pengguna->PenggunaID }}</td>
                        </tr>
                        <tr>
                            <th>Nama Pengguna</th>
                            <td>{{ $pengguna->NamaPengguna }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $pengguna->Email }}</td>
                        </tr>
                        <tr>
                            <th>Peran</th>
                            <td>
                                @if ($pengguna->Peran === 'Admin')
                                    <span class="badge bg-danger">{{ $pengguna->Peran }}</span>
                                @elseif ($pengguna->Peran === 'Kasir')
                                    <span class="badge bg-success">{{ $pengguna->Peran }}</span>
                                @else
                                    <span class="badge bg-info">{{ $pengguna->Peran }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Dibuat</th>
                            <td>{{ $pengguna->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Terakhir Diperbarui</th>
                            <td>{{ $pengguna->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Aktivitas Penjualan</h5>
                </div>
                <div class="card-body">
                    @php
                        $hasPenjualan = false;
                        $penjualanExists = false;
                        
                        try {
                            if (Schema::hasTable('penjualan')) {
                                $penjualanExists = true;
                                $hasPenjualan = $pengguna->penjualan()->exists();
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
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pengguna->penjualan()->latest()->take(5)->get() as $penjualan)
                                        <tr>
                                            <td>{{ $penjualan->PenjualanID }}</td>
                                            <td>{{ $penjualan->TanggalPenjualan }}</td>
                                            <td>Rp {{ number_format($penjualan->TotalHarga, 0, ',', '.') }}</td>
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
                            Pengguna ini belum memiliki transaksi penjualan.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection