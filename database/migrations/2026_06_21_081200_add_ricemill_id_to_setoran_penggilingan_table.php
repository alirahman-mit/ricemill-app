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
            $table->unsignedBigInteger('ricemill_id')->nullable()->after('user_id');
            $table->foreign('ricemill_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('setoran_penggilingan', function (Blueprint $table) {
            $table->dropForeign(['ricemill_id']);
            $table->dropColumn('ricemill_id');
        });
    }
};
