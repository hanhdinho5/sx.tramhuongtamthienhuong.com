<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ban_hangs', function (Blueprint $table) {
            $table->string('type_discount')->default(0)->nullable()->comment('0: not discount, 1: discount percent, 2: discount amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ban_hangs', function (Blueprint $table) {
            $table->dropColumn('type_discount');
        });
    }
};
