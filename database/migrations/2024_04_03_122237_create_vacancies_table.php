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
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();

            $table->boolean('is_active')->default(true);
            $table->text('type');
            $table->text('logo')->nullable();
            $table->text('alt')->nullable();
            $table->text('position')->nullable();
            $table->text('employerName')->nullable();
            $table->text('date')->nullable();
            $table->text('conditions')->nullable();
            $table->text('requirements')->nullable();
            $table->text('duties')->nullable();
            $table->text('email')->nullable();
            $table->text('phoneNumber')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};
