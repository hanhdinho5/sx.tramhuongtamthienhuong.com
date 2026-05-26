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
        Schema::create('phieu_san_xuat_chi_tiets', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('phieu_san_xuat_id');
            $table->foreign('phieu_san_xuat_id')->references('id')->on('phieu_san_xuats')->onDelete('cascade');

            $table->string('type')->nullable();

            $table->unsignedBigInteger('nguyen_lieu_id')->nullable();

            $table->string('ten_nguyen_lieu');
            $table->float('khoi_luong')->default(0);

            $table->decimal('so_tien', 15, 0)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phieu_san_xuat_chi_tiets');
    }
};
