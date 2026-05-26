<?php

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
        Schema::table('nguyen_lieu_phan_loais', function (Blueprint $table) {
            $table->string('ten_nguyen_lieu')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nguyen_lieu_phan_loais', function (Blueprint $table) {
            $table->dropColumn('ten_nguyen_lieu');
        });
    }
};
