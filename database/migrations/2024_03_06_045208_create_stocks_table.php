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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_main_display')->default(true);

            $table->text('heading')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();
            $table->text('storeName')->nullable();

            $table->text('mainBannerDesktop')->nullable();
            $table->text('mainBannerMobile')->nullable();
            $table->text('mainBannerAlt')->nullable();

            $table->text('additionalBannerDesktop')->nullable();
            $table->text('additionalBannerMobile')->nullable();
            $table->text('additionalBannerAlt')->nullable();

            $table->text('ovalBanner')->nullable();
            $table->text('ovalBannerAlt')->nullable();
            $table->text('ovalBannerText')->nullable();

            $table->text('otherAdditionalBanners')->nullable();

            $table->text('linksToLanding')->nullable();

            $table->text('gallery')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
