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
        // Schema::create('humidity_readings', function (Blueprint $table) {
        //     $table->id();
        //     $table->decimal('humidity_value', 5, 2); // Stores humidity with 2 decimal places
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('humidity_readings');
    }
};
