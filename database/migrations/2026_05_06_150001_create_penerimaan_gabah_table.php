<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penerimaan_gabah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // rice mill operator
            $table->string('nama_petani');
            $table->string('asal_lahan')->nullable();
            $table->date('tanggal');
            $table->decimal('jumlah_gabah', 10, 2);           // kg
            $table->enum('kualitas_gabah', ['kering', 'basah', 'grade_a', 'grade_b'])->default('kering');
            $table->enum('status', ['menunggu', 'diterima', 'diproses', 'selesai'])->default('menunggu');
            $table->string('bukti_foto')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penerimaan_gabah');
    }
};
