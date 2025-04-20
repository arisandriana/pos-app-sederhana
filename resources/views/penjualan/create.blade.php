@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <form id="form-penjualan" action="{{ route('penjualan.store') }}" method="POST">
        @csrf
        <div class="row">
            <!-- Kolom Kiri - Pilih Produk dan Keranjang -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Transaksi Baru</h5>
                    </div>
                    <div class="card-body">
                        <!-- Panel Pencarian Produk (Tanpa Modal) -->
                        <div class="mb-4 p-3 border rounded">
                            <div class="mb-3">
                                <label for="search-produk" class="form-label">Cari Produk</label>
                                <input type="text" class="form-control" id="search-produk" placeholder="Ketik nama produk atau barcode...">
                            </div>
                            
                            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-hover" id="product-table">
                                    <thead class="table-dark sticky-top">
                                        <tr>
                                            <th>Nama Produk</th>
                                            <th>Harga</th>
                                            <th>Stok</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product-list">
                                        @foreach($produk as $item)
                                        <tr class="product-row">
                                            <td>{{ $item->NamaProduk }}</td>
                                            <td>Rp {{ number_format($item->Harga, 0, ',', '.') }}</td>
                                            <td>{{ $item->Stok }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-success add-product" 
                                                    onclick="addProduct({{ $item->ProdukID }}, '{{ $item->NamaProduk }}', {{ $item->Harga }}, {{ $item->Stok }})">
                                                    <i class="fas fa-plus"></i> Tambah
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Keranjang Belanja -->
                        <h5 class="mb-3">Keranjang Belanja</h5>
                        <div class="table-responsive">
                            <table class="table table-striped" id="cart-table">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="40%">Produk</th>
                                        <th width="15%">Harga</th>
                                        <th width="15%">Jumlah</th>
                                        <th width="20%">Subtotal</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="empty-cart">
                                        <td colspan="5" class="text-center py-3">Belum ada produk di keranjang</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan - Pembayaran -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <!-- Form Pelanggan & Pembayaran -->
                        <div class="mb-3">
                            <label for="pelanggan_id" class="form-label">Pelanggan</label>
                            <select class="form-select" id="pelanggan_id" name="pelanggan_id">
                                <option value="">-- Umum --</option>
                                @foreach($pelanggan as $p)
                                    <option value="{{ $p->PelangganID }}">{{ $p->NamaPelanggan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="2"></textarea>
                        </div>

                        <div class="card mb-3 bg-light">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Total Item:</span>
                                    <span id="total-items">0</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Total Belanja:</span>
                                    <span id="total-belanja">Rp 0</span>
                                    <input type="hidden" name="total" id="total" value="0">
                                </div>
                                <div class="mb-2">
                                    <label for="bayar" class="form-label">Jumlah Bayar</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="bayar" name="bayar" value="0">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Kembalian:</span>
                                    <span id="kembalian">Rp 0</span>
                                    <input type="hidden" name="kembali" id="kembali" value="0">
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success" id="btn-bayar">
                                <i class="fas fa-check-circle me-1"></i> Proses Pembayaran
                            </button>
                            <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times-circle me-1"></i> Batal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Gunakan definisi fungsi langsung, bukan dalam event listener
function addProduct(id, name, price, stock) {
    console.log("Add product called:", id, name, price, stock);
    
    // Cek apakah produk sudah ada di keranjang
    var existingItem = document.querySelector('tr[data-product-id="' + id + '"]');
    var cartTable = document.getElementById('cart-table');
    
    if (existingItem) {
        // Update jumlah jika produk sudah ada
        var qtyInput = existingItem.querySelector('.qty-input');
        var currentQty = parseInt(qtyInput.value);
        var newQty = currentQty + 1;
        
        // Cek stok
        if (newQty > stock) {
            alert('Stok tidak mencukupi! Stok tersedia: ' + stock);
            return;
        }
        
        qtyInput.value = newQty;
        updateSubtotal(existingItem);
    } else {
        // Hapus empty cart message jika ada
        var emptyCart = document.getElementById('empty-cart');
        if (emptyCart) {
            emptyCart.remove();
        }
        
        // Tambahkan produk baru ke keranjang
        var tableBody = cartTable.querySelector('tbody');
        
        var newRow = document.createElement('tr');
        newRow.setAttribute('data-product-id', id);
        
        newRow.innerHTML = `
            <td>
                <strong>${name}</strong>
                <input type="hidden" name="produk_id[]" value="${id}">
            </td>
            <td>
                Rp ${formatNumber(price)}
                <input type="hidden" name="harga[]" value="${price}">
            </td>
            <td>
                <input type="number" class="form-control form-control-sm qty-input" name="jumlah[]" value="1" min="1" max="${stock}" onchange="updateSubtotal(this.parentNode.parentNode)">
            </td>
            <td>
                <span class="subtotal">Rp ${formatNumber(price)}</span>
                <input type="hidden" name="subtotal[]" value="${price}">
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(this.parentNode.parentNode)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        
        tableBody.appendChild(newRow);
    }
    
    updateTotals();
}

function updateSubtotal(row) {
    var price = parseFloat(row.querySelector('input[name="harga[]"]').value);
    var qty = parseInt(row.querySelector('.qty-input').value);
    var subtotal = price * qty;
    
    row.querySelector('.subtotal').textContent = 'Rp ' + formatNumber(subtotal);
    row.querySelector('input[name="subtotal[]"]').value = subtotal;
    
    updateTotals();
}

function removeItem(row) {
    row.remove();
    
    var cartTable = document.getElementById('cart-table');
    if (cartTable.querySelectorAll('tbody tr').length === 0) {
        var emptyRow = document.createElement('tr');
        emptyRow.id = 'empty-cart';
        emptyRow.innerHTML = '<td colspan="5" class="text-center py-3">Belum ada produk di keranjang</td>';
        cartTable.querySelector('tbody').appendChild(emptyRow);
    }
    
    updateTotals();
}

function updateTotals() {
    var items = document.querySelectorAll('#cart-table tbody tr:not(#empty-cart)');
    var totalQty = 0;
    var totalAmount = 0;
    
    items.forEach(function(item) {
        totalQty += parseInt(item.querySelector('.qty-input').value);
        totalAmount += parseFloat(item.querySelector('input[name="subtotal[]"]').value);
    });
    
    document.getElementById('total-items').textContent = totalQty;
    document.getElementById('total-belanja').textContent = 'Rp ' + formatNumber(totalAmount);
    document.getElementById('total').value = totalAmount;
    
    updateKembalian();
}

function updateKembalian() {
    var total = parseFloat(document.getElementById('total').value);
    var bayar = parseFloat(document.getElementById('bayar').value) || 0;
    var kembali = bayar - total;
    
    document.getElementById('kembalian').textContent = 'Rp ' + formatNumber(kembali);
    document.getElementById('kembali').value = kembali;
    
    document.getElementById('btn-bayar').disabled = total <= 0 || bayar < total;
}

function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

// Add event listener after DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Filter produk pada input pencarian
    document.getElementById('search-produk').addEventListener('keyup', function() {
        var searchText = this.value.toLowerCase();
        var rows = document.querySelectorAll('#product-list tr');
        
        rows.forEach(function(row) {
            var name = row.cells[0].textContent.toLowerCase();
            if (name.indexOf(searchText) > -1) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    
    // Event listener untuk input pembayaran
    document.getElementById('bayar').addEventListener('input', updateKembalian);
    
    // Event listener untuk tombol bayar
    document.getElementById('btn-bayar').addEventListener('click', function() {
        var items = document.querySelectorAll('#cart-table tbody tr:not(#empty-cart)');
        
        if (items.length === 0) {
            alert('Keranjang belanja masih kosong!');
            return;
        }
        
        var total = parseFloat(document.getElementById('total').value);
        var bayar = parseFloat(document.getElementById('bayar').value) || 0;
        
        if (bayar < total) {
            alert('Jumlah pembayaran kurang dari total belanja!');
            document.getElementById('bayar').focus();
            return;
        }
        
        if (confirm('Yakin ingin memproses transaksi ini?')) {
            document.getElementById('form-penjualan').submit();
        }
    });
});
</script>
@endsection