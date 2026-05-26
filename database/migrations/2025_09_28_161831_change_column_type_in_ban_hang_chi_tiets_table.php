<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ban_hang_chi_tiets', function (Blueprint $table) {
            $table->string('so_luong')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ban_hang_chi_tiets', function (Blueprint $table) {
            $table->integer('so_luong')->change();
        });
    }
};
