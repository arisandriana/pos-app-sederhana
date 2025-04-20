@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Tambah Produk Baru</h1>
        <a href="{{ route('produk.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="NamaProduk" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control @error('NamaProduk') is-invalid @enderror" 
                            id="NamaProduk" name="NamaProduk" value="{{ old('NamaProduk') }}" required>
                        @error('NamaProduk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="Barcode" class="form-label">Barcode <small class="text-muted">(Opsional)</small></label>
                        <input type="text" class="form-control @error('Barcode') is-invalid @enderror" 
                            id="Barcode" name="Barcode" value="{{ old('Barcode') }}">
                        @error('Barcode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="Harga" class="form-label">Harga (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('Harga') is-invalid @enderror" 
                                id="Harga" name="Harga" value="{{ old('Harga', 0) }}" step="0.01" min="0" required>
                        </div>
                        @error('Harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="Stok" class="form-label">Stok Awal</label>
                        <input type="number" class="form-control @error('Stok') is-invalid @enderror" 
                            id="Stok" name="Stok" value="{{ old('Stok', 0) }}" min="0" required>
                        @error('Stok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="Deskripsi" class="form-label">Deskripsi <small class="text-muted">(Opsional)</small></label>
                    <textarea class="form-control @error('Deskripsi') is-invalid @enderror" 
                        id="Deskripsi" name="Deskripsi" rows="3">{{ old('Deskripsi') }}</textarea>
                    @error('Deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="Gambar" class="form-label">Gambar Produk <small class="text-muted">(Opsional)</small></label>
                    <input type="file" class="form-control @error('Gambar') is-invalid @enderror" 
                        id="Gambar" name="Gambar" accept="image/*">
                    <small class="text-muted">Format: JPG, PNG, GIF. Maks: 2MB</small>
                    @error('Gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div id="imagePreview" class="mt-2 d-none">
                        <p>Preview:</p>
                        <img id="preview" src="#" alt="Preview Gambar" style="max-width: 200px; max-height: 200px;" class="img-thumbnail">
                    </div>
                </div>

                <div class="text-end">
                    <button type="reset" class="btn btn-secondary me-2">Reset</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Preview gambar yang diupload
    document.getElementById('Gambar').addEventListener('change', function(e) {
        const preview = document.getElementById('preview');
        const imagePreview = document.getElementById('imagePreview');
        
        if (e.target.files.length > 0) {
            preview.src = URL.createObjectURL(e.target.files[0]);
            imagePreview.classList.remove('d-none');
        } else {
            imagePreview.classList.add('d-none');
        }
    });
</script>
@endsection