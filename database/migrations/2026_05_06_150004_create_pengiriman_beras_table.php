<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengiriman_beras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // rice mill
            $table->string('nama_packager');
            $table->enum('jenis_beras', ['premium', 'medium', 'biasa'])->default('medium');
            $table->integer('jumlah_karung');
            $table->decimal('berat_per_karung', 8, 2)->nullable();             // kg
            $table->date('tanggal_kirim');
            $table->decimal('biaya_logistik', 15, 2)->nullable();
            $table->enum('status', ['menunggu', 'diterima', 'ditolak', 'diproses'])->default('menunggu');
            $table->string('bukti_kirim')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengiriman_beras');
    }
};
