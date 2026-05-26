<?php

use App\Enums\TrangThaiNguyenLieuTho;
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
        Schema::create('nguyen_lieu_thos', function (Blueprint $table) {
            $table->id();

            $table->timestamp('ngay')->default(Carbon::now())->nullable();

            $table->unsignedBigInteger('nha_cung_cap_id');
            $table->foreign('nha_cung_cap_id')->references('id')->on('nha_cung_caps')->onDelete('cascade');

            $table->string('ten_nguyen_lieu');
            $table->string('loai');
            $table->string('nguon_goc');
            $table->string('khoi_luong');
            $table->string('kich_thuoc');
            $table->string('do_kho');
            $table->string('dieu_kien_luu_tru');
            $table->string('chi_phi_mua');
            $table->string('phuong_thuc_thanh_toan');
            $table->string('cong_no');
            $table->string('nhan_su_xu_li')->nullable();
            $table->timestamp('thoi_gian_phan_loai')->default(Carbon::now());
            $table->longText('ghi_chu')->nullable();
            $table->string('trang_thai')->default(TrangThaiNguyenLieuTho::ACTIVE());

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nguyen_lieu_thos');
    }
};
