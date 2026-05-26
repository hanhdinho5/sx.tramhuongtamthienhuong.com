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
            $table->decimal('khoi_luong_da_dung', 10, 3)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phieu_san_xuats', function (Blueprint $table) {
            //
        });
    }
};
