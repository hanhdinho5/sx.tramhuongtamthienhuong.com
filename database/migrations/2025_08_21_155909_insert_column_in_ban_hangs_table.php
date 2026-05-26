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
        Schema::table('ban_hangs', function (Blueprint $table) {
            $table->string('ma_don_hang')->nullable();
            $table->string('loai_nguon_hang')->nullable();
            $table->string('nguon_hang')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ban_hangs', function (Blueprint $table) {
            $table->dropColumn('ma_don_hang');
            $table->dropColumn('loai_nguon_hang');
            $table->dropColumn('nguon_hang');
        });
    }
};
