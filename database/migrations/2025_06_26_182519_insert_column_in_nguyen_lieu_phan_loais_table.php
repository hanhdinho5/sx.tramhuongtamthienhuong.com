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
        Schema::table('nguyen_lieu_phan_loais', function (Blueprint $table) {
            $table->decimal('nu_cao_cap', 10, 3)->default(0)->change();
            $table->decimal('nu_vip', 10, 3)->default(0)->change();
            $table->decimal('nhang', 10, 3)->default(0)->change();
            $table->decimal('vong', 10, 3)->default(0)->change();
            $table->decimal('tam_tre', 10, 3)->default(0)->change();
            $table->decimal('keo', 10, 3)->default(0)->change();
            $table->decimal('nau_dau', 10, 3)->default(0)->change();
            $table->decimal('tong_khoi_luong', 10, 3)->default(0)->change();
            $table->decimal('khoi_luong_ban_dau', 10, 3)->default(0)->change();
            $table->decimal('khoi_luong_hao_hut', 10, 3)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nguyen_lieu_phan_loais', function (Blueprint $table) {
            //
        });
    }
};
