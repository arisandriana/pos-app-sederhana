@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">
            Daftar Produk
            @if(isset($filterActive) && $filterActive)
                <span class="badge bg-warning">Filter: {{ $filterText }}</span>
            @endif
            @if(isset($searchActive) && $searchActive)
                <span class="badge bg-info">Pencarian: "{{ $searchQuery }}"</span>
            @endif
        </h1>
        <a href="{{ route('produk.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Produk
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-white">
            <div class="row g-3">
                <div class="col-md-6">
                    <form action="{{ route('produk.search') }}" method="GET" class="d-flex">
                        <input type="text" name="query" class="form-control" placeholder="Cari nama produk atau barcode..." 
                            value="{{ $searchQuery ?? '' }}">
                        <button type="submit" class="btn btn-primary ms-2">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(isset($searchActive) && $searchActive)
                            <a href="{{ route('produk.index') }}" class="btn btn-secondary ms-2">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="btn-group float-end" role="group">
                        <a href="{{ route('produk.index') }}" class="btn {{ !isset($filterActive) ? 'btn-primary' : 'btn-outline-primary' }}">
                            Semua
                        </a>
                        <a href="{{ route('produk.filter', 'low') }}" class="btn {{ (isset($filterActive) && isset($filterText) && $filterText == 'Stok Rendah (< 10)') ? 'btn-warning' : 'btn-outline-warning' }}">
                            Stok Rendah
                        </a>
                        <a href="{{ route('produk.filter', 'out') }}" class="btn {{ (isset($filterActive) && isset($filterText) && $filterText == 'Stok Habis') ? 'btn-danger' : 'btn-outline-danger' }}">
                            Stok Habis
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Barcode</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($produk as $item)
                            <tr>
                                <td>{{ $item->ProdukID }}</td>
                                <td>
                                    @if($item->Gambar && file_exists(public_path('storage/produk/'.$item->Gambar)))
                                        <img src="{{ asset('storage/produk/'.$item->Gambar) }}" alt="{{ $item->NamaProduk }}" 
                                            width="50" height="50" class="img-thumbnail">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                            style="width: 50px; height: 50px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $item->NamaProduk }}</td>
                                <td>Rp {{ number_format($item->Harga, 0, ',', '.') }}</td>
                                <td>
                                    @if($item->Stok <= 0)
                                        <span class="badge bg-danger">Habis</span>
                                    @elseif($item->Stok < 10)
                                        <span class="badge bg-warning text-dark">{{ $item->Stok }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $item->Stok }}</span>
                                    @endif
                                </td>
                                <td>{{ $item->Barcode ?? '-' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('produk.show', $item->ProdukID) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('produk.edit', $item->ProdukID) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->ProdukID }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Modal Konfirmasi Hapus -->
                                    <div class="modal fade" id="deleteModal{{ $item->ProdukID }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin menghapus produk <strong>{{ $item->NamaProduk }}</strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form action="{{ route('produk.destroy', $item->ProdukID) }}" method="POST">
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
                                <td colspan="7" class="text-center">Tidak ada data produk</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection