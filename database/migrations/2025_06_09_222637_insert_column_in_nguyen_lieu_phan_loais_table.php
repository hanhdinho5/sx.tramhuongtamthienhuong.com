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
            $table->string('tam_dai')->nullable();
            $table->string('tam_ngan')->nullable();
            $table->string('nuoc_cat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nguyen_lieu_phan_loais', function (Blueprint $table) {
            $table->dropColumn('tam_dai');
            $table->dropColumn('tam_ngan');
            $table->dropColumn('nuoc_cat');
        });
    }
};
