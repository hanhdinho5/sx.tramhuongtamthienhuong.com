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
        Schema::table('phieu_san_xuats', function (Blueprint $table) {
            $table->string('don_gia')->nullable();
            $table->string('tong_tien')->nullable();
            $table->string('gia_tri_ton_kho')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phieu_san_xuats', function (Blueprint $table) {
            $table->dropColumn('don_gia');
            $table->dropColumn('tong_tien');
            $table->dropColumn('gia_tri_ton_kho');
        });
    }
};
