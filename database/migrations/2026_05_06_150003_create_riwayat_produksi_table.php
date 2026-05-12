<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_produksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('operasional_id')->nullable()->constrained('operasional_penggilingan')->onDelete('set null');
            $table->string('batch_id');
            $table->date('tanggal_proses');
            $table->decimal('jumlah_gabah', 10, 2);           // kg gabah masuk
            $table->decimal('jumlah_beras', 10, 2);           // kg beras hasil
            // rendemen dihitung otomatis: (jumlah_beras / jumlah_gabah) * 100
            $table->boolean('notifikasi_rendemen_rendah')->default(false); // task 6.4
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_produksi');
    }
};
