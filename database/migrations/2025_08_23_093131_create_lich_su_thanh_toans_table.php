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
        Schema::create('lich_su_thanh_toans', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('so_quy_id')->nullable();

            $table->unsignedBigInteger('ban_hang_id')->nullable();

            $table->string('so_tien_thanh_toan')->nullable();

            $table->unsignedBigInteger('nguoi_thanh_toan')->nullable();

            $table->string('ghi_chu')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_su_thanh_toans');
    }
};
