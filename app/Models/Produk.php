<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan model.
     *
     * @var string
     */
    protected $table = 'produk';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'ProdukID';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'NamaProduk',
        'Harga',
        'Stok',
        'Deskripsi',
        'Barcode',
        'Gambar',
    ];

    /**
     * Get detail penjualan yang terkait dengan produk ini.
     */
    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'ProdukID', 'ProdukID');
    }

    /**
     * Kurangi stok produk.
     *
     * @param int $jumlah
     * @return bool
     */
    public function kurangiStok($jumlah)
    {
        if ($this->Stok >= $jumlah) {
            $this->Stok -= $jumlah;
            return $this->save();
        }
        
        return false;
    }

    /**
     * Tambah stok produk.
     *
     * @param int $jumlah
     * @return bool
     */
    public function tambahStok($jumlah)
    {
        $this->Stok += $jumlah;
        return $this->save();
    }

    /**
     * Cek apakah stok mencukupi.
     *
     * @param int $jumlah
     * @return bool
     */
    public function cekStok($jumlah)
    {
        return $this->Stok >= $jumlah;
    }
}