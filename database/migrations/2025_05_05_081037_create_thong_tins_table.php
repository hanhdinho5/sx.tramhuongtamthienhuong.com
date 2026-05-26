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
        Schema::create('thong_tins', function (Blueprint $table) {
            $table->id();
            $table->string('display_name');
            $table->string('file_name');
            $table->string('file_path');
            $table->softDeletes('is_deleted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thong_tins');
    }
};
