<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->foreignId('device_id')->nullable()->constrained('devices')->onDelete('cascade');
            $table->float('moisture');
            $table->string('status');
            $table->timestamp('recorded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('moisture_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->nullable()->constrained('devices')->onDelete('cascade');
            $table->float('warnLower');
            $table->float('warnUpper');
            $table->string('status');
            $table->string('set_by');
            $table->timestamp('recorded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('humidity_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->nullable()->constrained('devices')->onDelete('cascade');
            $table->float('humidity');
            $table->string('status');
            $table->timestamp('recorded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('humidity_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->nullable()->constrained('devices')->onDelete('cascade');
            $table->float('warnLower');
            $table->float('warnUpper');
            $table->string('status');
            $table->string('set_by');
            $table->timestamp('recorded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('lux_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->nullable()->constrained('devices')->onDelete('cascade');
            $table->float('lux');
            $table->string('status');
            $table->timestamp('recorded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('lux_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->nullable()->constrained('devices')->onDelete('cascade');
            $table->float('warnLower');
            $table->float('warnUpper');
            $table->string('status');
            $table->string('set_by');
            $table->timestamp('recorded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('ph_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->nullable()->constrained('devices')->onDelete('cascade');
            $table->float('ph');
            $table->string('status');
            $table->timestamp('recorded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('ph_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->nullable()->constrained('devices')->onDelete('cascade');
            $table->float('warnLower');
            $table->float('warnUpper');
            $table->string('status');
            $table->string('set_by');
            $table->timestamp('recorded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        schema::create('rainDrops_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->nullable()->constrained('devices')->onDelete('cascade');
            $table->float('rainDrops');
            $table->string('status');
            $table->timestamp('recorded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('rainDrops_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->nullable()->constrained('devices')->onDelete('cascade');
            $table->float('warnLower')->nullable();
            $table->float('warnUpper')->nullable();
            $table->string('status');
            $table->string('set_by');
            $table->timestamp('recorded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('waterDepth_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->nullable()->constrained('devices')->onDelete('cascade');
            $table->enum('status', ['on', 'off'])->default('off');
            $table->float('amount')->nullable(); // dalam liter/ml
            $table->timestamp('recorded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('fertilizer_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->nullable()->constrained('devices')->onDelete('cascade');
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
