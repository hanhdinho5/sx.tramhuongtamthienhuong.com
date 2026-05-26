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
        Schema::table('nguyen_lieu_tinhs', function (Blueprint $table) {
            $table->string('so_luong_da_dung')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nguyen_lieu_tinhs', function (Blueprint $table) {
            $table->dropColumn('so_luong_da_dung');
        });
    }
};
