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
            $constraints = [
                'keuangan_ricemill' => ['keuangan_ricemill_kategori_check', 'keuangan_ricemill_tipe_check'],
                'operasional_penggilingan' => ['operasional_penggilingan_status_check'],
                'penerimaan_gabah' => ['penerimaan_gabah_kualitas_gabah_check', 'penerimaan_gabah_status_check'],
                'profil_lahans' => ['profil_lahans_jenis_tanah_check'],
                'setoran_penggilingan' => ['setoran_penggilingan_status_check'],
                'users' => ['users_role_check'],
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
