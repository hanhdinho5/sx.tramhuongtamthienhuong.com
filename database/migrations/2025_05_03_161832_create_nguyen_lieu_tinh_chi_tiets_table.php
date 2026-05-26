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
        Schema::create('nguyen_lieu_tinh_chi_tiets', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('nguyen_lieu_tinh_id');
            $table->foreign('nguyen_lieu_tinh_id')->references('id')->on('nguyen_lieu_tinhs')->onDelete('cascade');

            $table->unsignedBigInteger('nguyen_lieu_phan_loai_id')->nullable();
            $table->foreign('nguyen_lieu_phan_loai_id')->references('id')->on('nguyen_lieu_phan_loais')->onDelete('cascade');

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
        Schema::dropIfExists('nguyen_lieu_tinh_chi_tiets');
    }
};
