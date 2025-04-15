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
        Schema::create('event_lasts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->text('heading')->nullable();
            $table->date('start_date')->nullable();
            $table->text('description')->nullable();

            $table->text('mainImageDesktop')->nullable();
            $table->text('mainImageMobile')->nullable();
            $table->text('mainImageAlt')->nullable();

            $table->text('otherAdditionalBanners')->nullable();

            $table->text('schedule')->nullable();

            $table->text('gallery')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('EventLast');
    }
};
