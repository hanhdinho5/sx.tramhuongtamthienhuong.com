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
        Schema::create('ban_hang_chi_tiets', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('san_pham_id')->nullable();
            $table->integer('so_luong');
            $table->string('gia_ban');
            $table->string('tong_tien');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ban_hang_chi_tiets');
    }
};
