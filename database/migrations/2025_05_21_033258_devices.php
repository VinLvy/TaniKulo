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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // <- tambah id_user
            $table->string('name');
            $table->string('serial_number')->unique();
            $table->timestamps();
        });

        Schema::create('moisture_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
            $table->float('value'); // %
            $table->timestamp('recorded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('humidity_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
            $table->float('value'); // %
            $table->timestamp('recorded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('lux_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
            $table->float('value'); // lux
            $table->timestamp('recorded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('water_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
            $table->boolean('status'); // 0 = off, 1 = menyiram
            $table->float('amount')->nullable(); // liter atau ml
            $table->timestamp('recorded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('fertilizer_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
            $table->boolean('status'); // 0 = off, 1 = aktif
            $table->float('amount')->nullable(); // gram/ml
            $table->timestamp('recorded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('wifi_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
            $table->string('ssid');
            $table->string('password');
            $table->boolean('is_active')->default(true); // bisa disable jika device pindah lokasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wifi_settings');
        Schema::dropIfExists('fertilizer_logs');
        Schema::dropIfExists('water_logs');
        Schema::dropIfExists('lux_readings');
        Schema::dropIfExists('humidity_readings');
        Schema::dropIfExists('moisture_readings');
        Schema::dropIfExists('devices');
    }
};
