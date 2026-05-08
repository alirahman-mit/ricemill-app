<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // packager
            $table->string('nama_pelanggan');
            $table->date('tanggal');
            $table->enum('jenis_produk', ['beras_premium_5kg', 'beras_premium_10kg', 'beras_premium_25kg',
                                          'beras_medium_5kg',  'beras_medium_10kg',  'beras_medium_25kg',
                                          'beras_biasa_5kg',   'beras_biasa_10kg',   'beras_biasa_25kg']);
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->decimal('total_harga', 15, 2)->nullable();
            $table->enum('status', ['pending', 'diproses', 'selesai', 'dibatalkan'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
