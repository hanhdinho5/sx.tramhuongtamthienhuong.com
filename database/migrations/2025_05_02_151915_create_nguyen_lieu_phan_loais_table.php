<?php

use App\Enums\TrangThaiNguyenLieuPhanLoai;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nguyen_lieu_phan_loais', function (Blueprint $table) {
            $table->id();

            $table->timestamp('ngay')->default(Carbon::now())->nullable();

            $table->unsignedBigInteger('nguyen_lieu_tho_id');
            $table->foreign('nguyen_lieu_tho_id')->references('id')->on('nguyen_lieu_thos')->onDelete('cascade');

            $table->float('nu_cao_cap')->default(0);
            $table->float('nu_vip')->default(0);
            $table->float('nhang')->default(0);
            $table->float('vong')->default(0);
            $table->float('tam_tre')->default(0);
            $table->float('keo')->default(0);
            $table->float('nau_dau')->default(0);
            $table->float('tong_khoi_luong')->default(0);
            $table->float('khoi_luong_ban_dau')->default(0);
            $table->decimal('chi_phi_mua', 15, 0)->default(0);
            $table->float('khoi_luong_hao_hut')->default(0);
            $table->decimal('gia_sau_phan_loai', 15, 0)->default(0);
            $table->longText('ghi_chu')->nullable();

            $table->string('trang_thai')->default(TrangThaiNguyenLieuPhanLoai::ACTIVE());

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nguyen_lieu_phan_loais');
    }
};
