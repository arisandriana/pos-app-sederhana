@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">
            Daftar Pelanggan
            @if(isset($searchActive) && $searchActive)
                <span class="badge bg-info">Pencarian: "{{ $searchQuery }}"</span>
            @endif
        </h1>
        <a href="{{ route('pelanggan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Pelanggan
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-white">
            <form action="{{ route('pelanggan.search') }}" method="GET" class="d-flex">
                <input type="text" name="query" class="form-control" placeholder="Cari nama, telepon, atau email..." 
                    value="{{ $searchQuery ?? '' }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="fas fa-search"></i>
                </button>
                @if(isset($searchActive) && $searchActive)
                    <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama Pelanggan</th>
                            <th>No. Telepon</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pelanggan as $item)
                            <tr>
                                <td>{{ $item->PelangganID }}</td>
                                <td>{{ $item->NamaPelanggan }}</td>
                                <td>{{ $item->NomorTelepon ?? '-' }}</td>
                                <td>{{ $item->Email ?? '-' }}</td>
                                <td>{{ Str::limit($item->Alamat, 30) ?? '-' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('pelanggan.show', $item->PelangganID) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('pelanggan.edit', $item->PelangganID) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->PelangganID }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Modal Konfirmasi Hapus -->
                                    <div class="modal fade" id="deleteModal{{ $item->PelangganID }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin menghapus pelanggan <strong>{{ $item->NamaPelanggan }}</strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form action="{{ route('pelanggan.destroy', $item->PelangganID) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data pelanggan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection