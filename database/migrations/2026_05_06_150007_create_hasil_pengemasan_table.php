<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_pengemasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('penerimaan_beras_id')->nullable()->constrained('penerimaan_beras')->onDelete('set null');
            $table->date('tanggal');
            $table->enum('jenis_beras', ['premium', 'medium', 'biasa'])->default('medium');
            $table->enum('jenis_kemasan', ['5kg', '10kg', '25kg'])->default('5kg');
            $table->integer('jumlah_kemasan');
            $table->enum('kualitas', ['layak jual', 'reject'])->default('layak jual');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_pengemasan');
    }
};
