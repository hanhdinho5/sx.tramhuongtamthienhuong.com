<?php

use Carbon\Carbon;
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
            $table->timestamp('thoi_gian_hoan_thanh_san_xuat')->default(Carbon::now())->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phieu_san_xuats', function (Blueprint $table) {
            $table->dropColumn('thoi_gian_hoan_thanh_san_xuat');
        });
    }
};
