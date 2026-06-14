<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver !== 'mysql' && $driver !== 'sqlite') {
            // Drop Postgres check constraints caused by Laravel ENUMs
            $constraints = [
                'pengiriman_beras' => ['pengiriman_beras_jenis_beras_check', 'pengiriman_beras_status_check'],
                'penerimaan_beras' => ['penerimaan_beras_jenis_beras_check', 'penerimaan_beras_status_check'],
                'hasil_pengemasan' => ['hasil_pengemasan_jenis_beras_check', 'hasil_pengemasan_jenis_kemasan_check', 'hasil_pengemasan_kualitas_check'],
                'pesanan'          => ['pesanan_jenis_produk_check', 'pesanan_status_check'],
            ];

            foreach ($constraints as $table => $tableConstraints) {
                foreach ($tableConstraints as $constraint) {
                    DB::statement("ALTER TABLE {$table} DROP CONSTRAINT IF EXISTS {$constraint}");
                }
            }
        }
    }

    public function down(): void
    {
        // No-op for safety
    }
};
