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
        Schema::create('lich_su_ton_khos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('san_pham_id');
            $table->foreign('san_pham_id')->references('id')->on('san_phams')->onDelete('cascade');

            $table->boolean('type')->default(true);

            $table->integer('so_luong_cu');
            $table->integer('so_luong_moi');
            $table->timestamp('ngay')->default(Carbon::now())->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_su_ton_khos');
    }
};
