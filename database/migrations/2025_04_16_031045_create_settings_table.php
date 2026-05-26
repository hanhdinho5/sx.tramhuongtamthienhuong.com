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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            $table->string('og_title')->nullable();
            $table->string('og_des')->nullable();
            $table->string('og_url')->nullable();
            $table->string('og_site')->nullable();
            $table->string('og_img')->nullable();

            $table->string('brand_name')->nullable();
            $table->string('home_name')->nullable();

            $table->string('qna_email')->nullable();

            $table->string('browser_title')->nullable();

            $table->string('meta_tag')->nullable();
            $table->string('meta_keyword')->nullable();

            $table->string('domain_url')->nullable();

            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('address_detail')->nullable();

            $table->string('zip')->nullable();

            $table->string('owner_name')->nullable();
            $table->string('owner_phone')->nullable();
            $table->string('owner_email')->nullable();
            $table->string('owner_social_01')->nullable();
            $table->string('owner_social_02')->nullable();
            $table->string('owner_social_03')->nullable();

            $table->string('company_name')->nullable();
            $table->string('fax')->nullable();
            $table->string('business_number')->comment('số kinh doanh')->nullable();

            $table->string('author_name')->nullable();
            $table->string('author_social')->nullable();

            $table->string('copyright')->nullable();

            $table->text('logo')->nullable();
            $table->text('favicon')->nullable();
            $table->text('thumbnail')->nullable();

            $table->text('google_site_verification')->nullable();

            $table->string('bank_name')->nullable();
            $table->string('bank_number')->nullable();
            $table->string('bank_holder')->nullable();
            $table->string('qr_code')->nullable();

            $table->string('bank_name_02')->nullable();
            $table->string('bank_number_02')->nullable();
            $table->string('bank_holder_02')->nullable();
            $table->string('qr_code_02')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
