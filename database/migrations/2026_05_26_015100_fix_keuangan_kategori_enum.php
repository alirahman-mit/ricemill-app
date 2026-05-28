<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah kolom 'kategori' dari ENUM ketat → VARCHAR(100) agar fleksibel
        // ENUM lama: ['operasional','tenaga_kerja','setoran','penggilingan','pengiriman','lainnya']
        // Form mengisi: 'Penjualan Beras', 'Jasa Penggilingan', dll — tidak cocok dengan ENUM di atas
        DB::statement("ALTER TABLE keuangan_ricemill MODIFY COLUMN kategori VARCHAR(100) NOT NULL DEFAULT 'lainnya'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE keuangan_ricemill MODIFY COLUMN kategori ENUM('operasional','tenaga_kerja','setoran','penggilingan','pengiriman','lainnya') NOT NULL DEFAULT 'lainnya'");
    }
};
