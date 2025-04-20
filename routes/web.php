<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PenjualanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute Autentikasi
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    // Route untuk dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Rute untuk pengelolaan pengguna (hanya untuk admin)
    Route::middleware(['admin'])->group(function () {
        Route::resource('pengguna', PenggunaController::class);
    });

    // CRUD produk
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk.create');
    Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk/{produk}', [ProdukController::class, 'show'])->name('produk.show');
    Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');
    
    // Route tambahan untuk produk
    Route::get('/produk/filter/{filter}', [ProdukController::class, 'filterByStock'])->name('produk.filter');
    Route::get('/produk/search', [ProdukController::class, 'search'])->name('produk.search');
    Route::post('/produk/{produk}/add-stock', [ProdukController::class, 'addStock'])->name('produk.addStock');

    // CRUD pelanggan
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
    Route::get('/pelanggan/create', [PelangganController::class, 'create'])->name('pelanggan.create');
    Route::post('/pelanggan', [PelangganController::class, 'store'])->name('pelanggan.store');
    Route::get('/pelanggan/{pelanggan}', [PelangganController::class, 'show'])->name('pelanggan.show');
    Route::get('/pelanggan/{pelanggan}/edit', [PelangganController::class, 'edit'])->name('pelanggan.edit');
    Route::put('/pelanggan/{pelanggan}', [PelangganController::class, 'update'])->name('pelanggan.update');
    Route::delete('/pelanggan/{pelanggan}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');
    
    // Route tambahan untuk pelanggan
    Route::get('/pelanggan/search', [PelangganController::class, 'search'])->name('pelanggan.search');
    Route::get('/api/pelanggan/search', [PelangganController::class, 'searchJson'])->name('pelanggan.search.json');

    // CRUD penjualan
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('/penjualan/{penjualan}', [PenjualanController::class, 'show'])->name('penjualan.show');
    Route::get('/penjualan/{penjualan}/print', [PenjualanController::class, 'printInvoice'])->name('penjualan.print');
    Route::post('/penjualan/{penjualan}/cancel', [PenjualanController::class, 'cancel'])->name('penjualan.cancel');
    
    // Route untuk pencarian
    Route::get('/penjualan/search/filter', [PenjualanController::class, 'search'])->name('penjualan.search');
    // Route::get('/api/produk/search', [PenjualanController::class, 'searchProduct'])->name('penjualan.search.product');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/penjualan/harian', [LaporanController::class, 'penjualanHarian'])->name('laporan.penjualan.harian');
    Route::get('/penjualan/bulanan', [LaporanController::class, 'penjualanBulanan'])->name('laporan.penjualan.bulanan');
    Route::get('/penjualan/periode', [LaporanController::class, 'penjualanPeriode'])->name('laporan.penjualan.periode');
    Route::get('/produk/terlaris', [LaporanController::class, 'produkTerlaris'])->name('laporan.produk.terlaris');
    Route::get('/stok/produk', [LaporanController::class, 'stokProduk'])->name('laporan.stok.produk');
    Route::get('/pelanggan/terbaik', [LaporanController::class, 'pelangganTerbaik'])->name('laporan.pelanggan.terbaik');
    Route::get('/pendapatan/kasir', [LaporanController::class, 'pendapatanPerKasir'])->name('laporan.pendapatan.kasir');
    
    // Route export laporan
    Route::get('/export/pdf', [LaporanController::class, 'exportPDF'])->name('laporan.export.pdf');
    Route::get('/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');
});