<?php

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
        Schema::create('so_quies', function (Blueprint $table) {
            $table->id();

            $table->boolean('loai')->default(true)->comment('true: phiếu thu, false: phiếu chi');
            $table->string('ma_phieu')->unique();

            $table->string('so_tien');
            $table->longText('noi_dung')->nullable();

            $table->timestamp('ngay')->default(Carbon::now())->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('so_quies');
    }
};
