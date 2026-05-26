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
        Schema::table('ban_hang_chi_tiets', function (Blueprint $table) {
            $table->unsignedBigInteger('ban_hang_id');
            $table->foreign('ban_hang_id')->references('id')->on('ban_hangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ban_hang_chi_tiets', function (Blueprint $table) {
            $table->dropForeign(['ban_hang_id']);
            $table->dropColumn('ban_hang_id');
        });
    }
};
