<?php

use App\Enums\TrangThaiNguyenLieuSanXuat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nguyen_lieu_san_xuats', function (Blueprint $table) {
            $table->id();
            $table->string('ten_nguyen_lieu')->nullable();
            $table->string('code')->unique();
            $table->timestamp('ngay')->default(Carbon::now())->nullable();
            $table->unsignedBigInteger('phieu_san_xuat_id');
            $table->foreign('phieu_san_xuat_id')->references('id')->on('phieu_san_xuats')->onDelete('cascade');
            $table->float('khoi_luong')->default(0);
            $table->string('don_vi_tinh');
            $table->string('mau_sac')->nullable();
            $table->string('mui_thom');
            $table->longText('chi_tiet_khac');
            $table->string('bao_quan');
            $table->longText('ghi_chu')->nullable();
            $table->string('trang_thai')->default(TrangThaiNguyenLieuSanXuat::ACTIVE());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nguyen_lieu_san_xuats');
    }
};
