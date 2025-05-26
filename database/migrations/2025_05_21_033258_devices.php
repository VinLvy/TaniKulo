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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('serial_number')->unique();

            // Tambahan kolom dari wifi_settings
            $table->string('ssid')->nullable();
            $table->string('password')->nullable();
            $table->boolean('is_active')->default(true); // untuk status wifi

            $table->timestamps();
        });

        Schema::create('moisture_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
            $table->float('value');
            $table->timestamp('recorded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('humidity_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
            $table->float('value');
            $table->timestamp('recorded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('lux_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
            $table->float('value');
            $table->timestamp('recorded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('ph_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
            $table->float('value');
            $table->timestamp('recorded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('water_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
            $table->enum('status', ['on', 'off'])->default('off');
            $table->float('amount')->nullable(); // dalam liter/ml
            $table->timestamp('recorded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('fertilizer_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
            $table->enum('status', ['on', 'off'])->default('off');
            $table->float('amount')->nullable(); // dalam liter/ml
            $table->timestamp('recorded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fertilizer_logs');
        Schema::dropIfExists('water_logs');
        Schema::dropIfExists('ph_readings');
        Schema::dropIfExists('lux_readings');
        Schema::dropIfExists('humidity_readings');
        Schema::dropIfExists('moisture_readings');
        Schema::dropIfExists('devices');
    }
};
