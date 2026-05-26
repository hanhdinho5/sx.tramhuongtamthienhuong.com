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
        Schema::table('nguyen_lieu_thos', function (Blueprint $table) {
            $table->string('khoi_luong_da_phan_loai')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nguyen_lieu_thos', function (Blueprint $table) {
            $table->dropColumn('khoi_luong_da_phan_loai');
        });
    }
};
