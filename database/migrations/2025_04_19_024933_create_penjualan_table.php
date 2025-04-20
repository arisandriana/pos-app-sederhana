<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id('PenjualanID');
            $table->dateTime('TanggalPenjualan')->useCurrent();
            $table->decimal('TotalHarga', 15, 2)->default(0);
            $table->decimal('Bayar', 15, 2)->default(0);
            $table->decimal('Kembali', 15, 2)->default(0);
            $table->unsignedBigInteger('PelangganID')->nullable();
            $table->unsignedBigInteger('PenggunaID');
            $table->enum('Status', ['selesai', 'pending', 'batal'])->default('selesai');
            $table->text('Catatan')->nullable();
            $table->timestamps();
            
            $table->foreign('PelangganID')->references('PelangganID')->on('pelanggan')->nullOnDelete();
            $table->foreign('PenggunaID')->references('PenggunaID')->on('pengguna');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};