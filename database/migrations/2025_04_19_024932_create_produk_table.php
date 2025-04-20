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
        Schema::create('produk', function (Blueprint $table) {
            $table->id('ProdukID');
            $table->string('NamaProduk');
            $table->decimal('Harga', 15, 2);
            $table->integer('Stok')->default(0);
            $table->text('Deskripsi')->nullable();
            $table->string('Barcode')->nullable()->unique();
            $table->string('Gambar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};