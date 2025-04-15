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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();

            $table->boolean('is_active')->default(true);

            $table->string('template');

            $table->text('firstImageDesktop')->nullable();
            $table->text('firstImageMobile')->nullable();
            $table->text('firstImageAlt')->nullable();

            $table->text('secondImageDesktop')->nullable();
            $table->text('secondImageMobile')->nullable();
            $table->text('secondImageAlt')->nullable();

            $table->text('heading')->nullable();
            $table->text('description')->nullable();
            $table->text('buttonText')->nullable();
            $table->text('link')->nullable();
            $table->text('backgroundColor')->nullable();
            $table->text('colorText')->nullable();
            $table->text('backgroundColorButton')->nullable();
            $table->text('colorButtonText')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
