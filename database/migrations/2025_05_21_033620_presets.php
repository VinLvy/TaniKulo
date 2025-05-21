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
        Schema::create('presets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
            $table->float('moisture_min')->nullable();
            $table->float('moisture_max')->nullable();
            $table->float('humidity_min')->nullable();
            $table->float('humidity_max')->nullable();
            $table->float('lux_min')->nullable();
            $table->float('lux_max')->nullable();
            $table->float('water_amount')->nullable(); // preset takaran air default
            $table->float('fertilizer_amount')->nullable(); // preset takaran pupuk default
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presets');
    }
};
