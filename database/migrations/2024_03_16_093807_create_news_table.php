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
        Schema::create('news', function (Blueprint $table) {
            $table->id();

            $table->boolean('is_main_display')->default(true);

            $table->text('heading')->nullable();
            $table->date('published_date')->nullable();
            $table->text('description')->nullable();

            $table->text('mainImageDesktop')->nullable();
            $table->text('mainImageMobile')->nullable();
            $table->text('mainImageAlt')->nullable();

            $table->text('otherAdditionalBanners')->nullable();

            $table->text('gallery')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
