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
            $table->string('giam_gia')->default(0)->nullable();
            $table->string('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ban_hangs', function (Blueprint $table) {
            $table->dropColumn('giam_gia');
            $table->dropColumn('note');
        });
    }
};
