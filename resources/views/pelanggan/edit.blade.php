@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Edit Pelanggan</h1>
        <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('pelanggan.update', $pelanggan->PelangganID) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="NamaPelanggan" class="form-label">Nama Pelanggan</label>
                        <input type="text" class="form-control @error('NamaPelanggan') is-invalid @enderror" 
                            id="NamaPelanggan" name="NamaPelanggan" value="{{ old('NamaPelanggan', $pelanggan->NamaPelanggan) }}" required>
                        @error('NamaPelanggan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="NomorTelepon" class="form-label">Nomor Telepon <small class="text-muted">(Opsional)</small></label>
                        <input type="text" class="form-control @error('NomorTelepon') is-invalid @enderror" 
                            id="NomorTelepon" name="NomorTelepon" value="{{ old('NomorTelepon', $pelanggan->NomorTelepon) }}">
                        @error('NomorTelepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="Email" class="form-label">Email <small class="text-muted">(Opsional)</small></label>
                    <input type="email" class="form-control @error('Email') is-invalid @enderror" 
                        id="Email" name="Email" value="{{ old('Email', $pelanggan->Email) }}">
                    @error('Email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="Alamat" class="form-label">Alamat <small class="text-muted">(Opsional)</small></label>
                    <textarea class="form-control @error('Alamat') is-invalid @enderror" 
                        id="Alamat" name="Alamat" rows="3">{{ old('Alamat', $pelanggan->Alamat) }}</textarea>
                    @error('Alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="Catatan" class="form-label">Catatan <small class="text-muted">(Opsional)</small></label>
                    <textarea class="form-control @error('Catatan') is-invalid @enderror" 
                        id="Catatan" name="Catatan" rows="3">{{ old('Catatan', $pelanggan->Catatan) }}</textarea>
                    @error('Catatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end">
                    <button type="reset" class="btn btn-secondary me-2">Reset</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection