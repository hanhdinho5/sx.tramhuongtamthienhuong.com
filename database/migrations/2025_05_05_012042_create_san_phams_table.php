<?php

use App\Enums\TrangThaiSanPham;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('san_phams', function (Blueprint $table) {
            $table->id();

            $table->string('ma_san_pham')->unique();
            $table->string('ma_vach')->nullable();

            $table->string('ten_san_pham');
            $table->string('don_vi_tinh');
            $table->string('khoi_luong_rieng');
            $table->string('gia_xuat_kho')->comment('Giá nhập');
            $table->string('gia_ban')->comment('giá bán ra cho KHách hàng');
            $table->string('ton_kho');
            $table->string('mo_ta')->nullable();

            $table->string('trang_thai')->default(TrangThaiSanPham::ACTIVE());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('san_phams');
    }
};
