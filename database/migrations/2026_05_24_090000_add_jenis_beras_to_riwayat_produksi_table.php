<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('riwayat_produksi', function (Blueprint $table) {
            $table->string('jenis_beras')->nullable()->after('jumlah_beras')
                  ->comment('Jenis beras hasil produksi: premium, medium, setra_ramos, pandan_wangi, dll');
        });
    }

    public function down(): void
    {
        Schema::table('riwayat_produksi', function (Blueprint $table) {
            $table->dropColumn('jenis_beras');
        });
    }
};
