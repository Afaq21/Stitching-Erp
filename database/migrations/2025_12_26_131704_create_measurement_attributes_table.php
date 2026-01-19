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
    Schema::create('measurement_attributes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('service_id')->constrained('services')->onDelete('cascade'); // link to services
        $table->string('name'); // e.g., Bazu, Tera, Height
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurement_attributes');
    }
};
