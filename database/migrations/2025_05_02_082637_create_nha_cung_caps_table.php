<?php

use App\Enums\TrangThaiNhaCungCap;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nha_cung_caps', function (Blueprint $table) {
            $table->id();

            $table->string('ten');
            $table->string('tinh_thanh');
            $table->string('dia_chi');
            $table->string('so_dien_thoai');
            $table->string('trang_thai')->default(TrangThaiNhaCungCap::ACTIVE());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nha_cung_caps');
    }
};
