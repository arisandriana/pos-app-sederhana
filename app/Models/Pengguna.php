<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Nama tabel yang digunakan model.
     *
     * @var string
     */
    protected $table = 'pengguna';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'PenggunaID';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'NamaPengguna',
        'Email',
        'KataSandi',
        'Peran',
    ];

    /**
     * Atribut yang harus disembunyikan dalam array.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'KataSandi',
        'remember_token',
    ];

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->KataSandi;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * The penjualan that belong to the pengguna.
     */
    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'PenggunaID', 'PenggunaID');
    }
}