<?php

use App\Enums\TrangThaiBanHang;
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
        Schema::create('ban_hangs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('khach_hang_id')->nullable();
            $table->foreign('khach_hang_id')->references('id')->on('khach_hangs')->onDelete('cascade');

            $table->string('loai_san_pham');
            $table->boolean('ban_le')->default(false)->nullable();
            $table->string('khach_le')->nullable();

            $table->string('tong_tien');
            $table->string('da_thanht_toan');
            $table->string('cong_no');

            $table->string('phuong_thuc_thanh_toan');
            $table->string('trang_thai')->default(TrangThaiBanHang::ACTIVE());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ban_hangs');
    }
};
