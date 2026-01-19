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
    Schema::create('measurement_values', function (Blueprint $table) {
        $table->id();
        $table->foreignId('measurement_id')->constrained('measurements')->onDelete('cascade');
        $table->foreignId('measurement_attribute_id')->constrained('measurement_attributes')->onDelete('cascade');
        $table->string('value'); // actual measurement value
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurement_values');
    }
};
