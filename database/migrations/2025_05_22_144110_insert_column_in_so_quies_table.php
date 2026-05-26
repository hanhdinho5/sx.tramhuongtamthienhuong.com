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
        Schema::table('so_quies', function (Blueprint $table) {
            $table->unsignedBigInteger('loai_quy_id');
            $table->foreign('loai_quy_id')->references('id')->on('loai_quies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('so_quies', function (Blueprint $table) {
            $table->dropForeign(['loai_quy_id']);
            $table->dropColumn('loai_quy_id');
        });
    }
};
