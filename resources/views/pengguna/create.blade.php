@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Tambah Pengguna Baru</h1>
        <a href="{{ route('pengguna.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('pengguna.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="NamaPengguna" class="form-label">Nama Pengguna</label>
                        <input type="text" class="form-control @error('NamaPengguna') is-invalid @enderror" 
                            id="NamaPengguna" name="NamaPengguna" value="{{ old('NamaPengguna') }}" required>
                        @error('NamaPengguna')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="Email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('Email') is-invalid @enderror" 
                            id="Email" name="Email" value="{{ old('Email') }}" required>
                        @error('Email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="KataSandi" class="form-label">Kata Sandi</label>
                        <input type="password" class="form-control @error('KataSandi') is-invalid @enderror" 
                            id="KataSandi" name="KataSandi" required>
                        @error('KataSandi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="KonfirmasiKataSandi" class="form-label">Konfirmasi Kata Sandi</label>
                        <input type="password" class="form-control @error('KonfirmasiKataSandi') is-invalid @enderror" 
                            id="KonfirmasiKataSandi" name="KonfirmasiKataSandi" required>
                        @error('KonfirmasiKataSandi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="Peran" class="form-label">Peran</label>
                    <select class="form-select @error('Peran') is-invalid @enderror" id="Peran" name="Peran" required>
                        <option value="" disabled selected>Pilih Peran</option>
                        <option value="Admin" {{ old('Peran') == 'Admin' ? 'selected' : '' }}>Admin</option>
                        <option value="Kasir" {{ old('Peran') == 'Kasir' ? 'selected' : '' }}>Kasir</option>
                        <option value="Manager" {{ old('Peran') == 'Manager' ? 'selected' : '' }}>Manager</option>
                    </select>
                    @error('Peran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end">
                    <button type="reset" class="btn btn-secondary me-2">Reset</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection