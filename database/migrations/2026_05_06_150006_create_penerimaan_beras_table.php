<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penerimaan_beras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // packager
            $table->foreignId('pengiriman_beras_id')->nullable()->constrained('pengiriman_beras')->onDelete('set null');
            $table->string('asal_penggilingan');
            $table->enum('jenis_beras', ['premium', 'medium', 'biasa'])->default('medium');
            $table->decimal('jumlah_beras', 10, 2);            // kg
            $table->date('tanggal');
            $table->enum('status', ['menunggu', 'diterima', 'ditolak', 'sebagian'])->default('menunggu');
            $table->string('bukti_foto')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penerimaan_beras');
    }
};
