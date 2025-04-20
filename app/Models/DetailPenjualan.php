<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan model.
     *
     * @var string
     */
    protected $table = 'detail_penjualan';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'DetailID';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'PenjualanID',
        'ProdukID',
        'JumlahProduk',
        'HargaSatuan',
        'Subtotal',
    ];

    /**
     * Konversi atribut ke tipe native.
     *
     * @var array
     */
    protected $casts = [
        'HargaSatuan' => 'decimal:2',
        'Subtotal' => 'decimal:2',
    ];

    /**
     * Get the penjualan that owns the detail penjualan.
     */
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'PenjualanID', 'PenjualanID');
    }

    /**
     * Get the produk that owns the detail penjualan.
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'ProdukID', 'ProdukID');
    }

    /**
     * Hitung subtotal detail penjualan.
     */
    public function hitungSubtotal()
    {
        $this->Subtotal = $this->JumlahProduk * $this->HargaSatuan;
        return $this->Subtotal;
    }
}