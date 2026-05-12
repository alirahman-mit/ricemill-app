<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operasional_penggilingan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('penerimaan_gabah_id')->nullable()->constrained('penerimaan_gabah')->onDelete('set null');
            $table->string('batch_id')->unique();             // misal: BATCH-20260506-001
            $table->date('tanggal_proses');
            $table->decimal('jumlah_gabah_masuk', 10, 2);    // kg
            $table->enum('status', ['menunggu', 'diproses', 'selesai'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operasional_penggilingan');
    }
};
