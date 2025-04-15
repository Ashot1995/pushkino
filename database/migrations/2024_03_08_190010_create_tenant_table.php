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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();

            $table->text('slug')->nullable();
            $table->integer('sort')->default(500);
            $table->text('type')->nullable();
            $table->text('logo')->nullable();
            $table->text('mainImageDesktop')->nullable();
            $table->text('mainImageMobile')->nullable();
            $table->text('mainImageAlt')->nullable();
            $table->text('liter')->nullable();
            $table->text('storeName')->nullable();
            $table->integer('floor')->nullable();
            $table->text('idSpace')->nullable();
            $table->text('description')->nullable();
            $table->text('workingTime')->nullable();
            $table->text('links')->nullable();
            $table->text('phoneNumber')->nullable();
            $table->text('gallery')->nullable();
            $table->boolean('new')->default(false);
            $table->text('has_stocks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};