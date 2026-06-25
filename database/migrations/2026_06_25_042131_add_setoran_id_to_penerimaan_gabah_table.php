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
        Schema::table('penerimaan_gabah', function (Blueprint $table) {
            $table->unsignedBigInteger('setoran_id')->nullable()->after('user_id');
            $table->foreign('setoran_id')->references('id')->on('setoran_penggilingan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penerimaan_gabah', function (Blueprint $table) {
            $table->dropForeign(['setoran_id']);
            $table->dropColumn('setoran_id');
        });
    }
};
