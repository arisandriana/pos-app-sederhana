@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Daftar Pengguna</h1>
        <a href="{{ route('pengguna.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Pengguna
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Peran</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penggunas as $pengguna)
                            <tr>
                                <td>{{ $pengguna->PenggunaID }}</td>
                                <td>{{ $pengguna->NamaPengguna }}</td>
                                <td>{{ $pengguna->Email }}</td>
                                <td>
                                    @if ($pengguna->Peran === 'Admin')
                                        <span class="badge bg-danger">{{ $pengguna->Peran }}</span>
                                    @elseif ($pengguna->Peran === 'Kasir')
                                        <span class="badge bg-success">{{ $pengguna->Peran }}</span>
                                    @else
                                        <span class="badge bg-info">{{ $pengguna->Peran }}</span>
                                    @endif
                                </td>
                                <td>{{ $pengguna->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('pengguna.show', $pengguna->PenggunaID) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('pengguna.edit', $pengguna->PenggunaID) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $pengguna->PenggunaID }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Modal Konfirmasi Hapus -->
                                    <div class="modal fade" id="deleteModal{{ $pengguna->PenggunaID }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin menghapus pengguna <strong>{{ $pengguna->NamaPengguna }}</strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form action="{{ route('pengguna.destroy', $pengguna->PenggunaID) }}" method="POST">
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
                                <td colspan="6" class="text-center">Tidak ada data pengguna</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection