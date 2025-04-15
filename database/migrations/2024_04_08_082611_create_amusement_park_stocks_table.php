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
        Schema::create('amusement_park_stocks', function (Blueprint $table) {
            $table->id();

            $table->text('image')->nullable();
            $table->text('alt')->nullable();
            $table->text('heading')->nullable();
            $table->text('subheading')->nullable();
            $table->text('link')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amusement_park_stocks');
    }
};
