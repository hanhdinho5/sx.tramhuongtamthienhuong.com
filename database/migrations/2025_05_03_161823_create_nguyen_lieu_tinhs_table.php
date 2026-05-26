<?php

use App\Enums\TrangThaiNguyenLieuTinh;
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
        Schema::create('nguyen_lieu_tinhs', function (Blueprint $table) {
            $table->id();

            $table->timestamp('ngay')->default(Carbon::now())->nullable();

            $table->string('code')->unique();

            $table->string('trang_thai')->default(TrangThaiNguyenLieuTinh::ACTIVE());

            $table->float('tong_khoi_luong')->nullable()->default(0);
            $table->decimal('gia_tien', 15, 0)->nullable()->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nguyen_lieu_tinhs');
    }
};
