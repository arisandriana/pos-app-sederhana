@extends('layouts.app')

@php
use Illuminate\Support\Facades\Schema;
@endphp

@section('content')
<div class="card mb-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Riwayat Aktivitas</h5>
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
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Penjualan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Pelanggan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengguna->penjualan()->latest()->take(10)->get() as $penjualan)
                            <tr>
                                <td>{{ $penjualan->PenjualanID }}</td>
                                <td>{{ $penjualan->TanggalPenjualan }}</td>
                                <td>Rp {{ number_format($penjualan->TotalHarga, 0, ',', '.') }}</td>
                                <td>{{ $penjualan->pelanggan->NamaPelanggan ?? 'Umum' }}</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">
                Belum ada riwayat aktivitas penjualan.
            </div>
        @endif
    </div>
</div>