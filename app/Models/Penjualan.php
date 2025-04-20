<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan model.
     *
     * @var string
     */
    protected $table = 'penjualan';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'PenjualanID';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'TanggalPenjualan',
        'TotalHarga',
        'Bayar',
        'Kembali',
        'PelangganID',
        'PenggunaID',
        'Status',
        'Catatan',
    ];

    /**
     * Konversi atribut ke tipe native.
     *
     * @var array
     */
    protected $casts = [
        'TanggalPenjualan' => 'datetime',
        'TotalHarga' => 'decimal:2',
        'Bayar' => 'decimal:2',
        'Kembali' => 'decimal:2',
    ];

    /**
     * Get the pengguna that owns the penjualan.
     */
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'PenggunaID', 'PenggunaID');
    }

    /**
     * Get the pelanggan that owns the penjualan.
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'PelangganID', 'PelangganID');
    }

    /**
     * Get the detail penjualan for the penjualan.
     */
    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'PenjualanID', 'PenjualanID');
    }

    /**
     * Hitung total harga dari semua detail penjualan.
     */
    public function hitungTotal()
    {
        $total = $this->detailPenjualan->sum('Subtotal');
        $this->TotalHarga = $total;
        return $total;
    }

    /**
     * Hitung kembalian dari pembayaran.
     */
    public function hitungKembalian()
    {
        $this->Kembali = $this->Bayar - $this->TotalHarga;
        return $this->Kembali;
    }

    /**
     * Get nomor invoice berdasarkan ID penjualan.
     */
    public function getNomorInvoice()
    {
        return 'INV-' . str_pad($this->PenjualanID, 6, '0', STR_PAD_LEFT);
    }
}