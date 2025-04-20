<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan model.
     *
     * @var string
     */
    protected $table = 'pelanggan';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'PelangganID';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'NamaPelanggan',
        'Alamat',
        'NomorTelepon',
        'Email',
        'Catatan',
    ];

    /**
     * Get the penjualan for the pelanggan.
     */
    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'PelangganID', 'PelangganID');
    }

    /**
     * Cari pelanggan berdasarkan nama atau nomor telepon.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('NamaPelanggan', 'like', "%{$search}%")
                     ->orWhere('NomorTelepon', 'like', "%{$search}%")
                     ->orWhere('Email', 'like', "%{$search}%");
    }
}