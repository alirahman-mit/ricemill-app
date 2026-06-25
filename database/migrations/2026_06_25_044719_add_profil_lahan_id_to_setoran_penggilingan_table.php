<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('setoran_penggilingan', function (Blueprint $table) {
            $table->unsignedBigInteger('profil_lahan_id')->nullable()->after('ricemill_id');
            $table->foreign('profil_lahan_id')->references('id')->on('profil_lahans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('setoran_penggilingan', function (Blueprint $table) {
            $table->dropForeign(['profil_lahan_id']);
            $table->dropColumn('profil_lahan_id');
        });
    }
};
