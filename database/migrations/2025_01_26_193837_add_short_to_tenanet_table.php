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
        Schema::table('theater_elements', function (Blueprint $table) {
            $table->integer('sort')->default(500);
            $table->DateTime('active_from')->nullable();
            $table->DateTime('active_to')->nullable();
            $table->boolean('active')->defalut(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('theater_elements', function (Blueprint $table) {
            $table->dropColumn('sort');
            $table->dropColumn('active_from');
            $table->dropColumn('active_to');
            $table->dropColumn('active');
        });
    }
};
