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
        Schema::table('khach_hangs', function (Blueprint $table) {
            $table->unsignedBigInteger('nhom_khach_hang_id');
            $table->foreign('nhom_khach_hang_id')->references('id')->on('nhom_khach_hangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('khach_hangs', function (Blueprint $table) {
            $table->dropForeign(['nhom_khach_hang_id']);
            $table->dropColumn('nhom_khach_hang_id');
        });
    }
};
