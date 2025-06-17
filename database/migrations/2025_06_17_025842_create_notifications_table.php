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
        // Jika ingin membuat tabel notifications tanpa relasi device

        // Schema::create('notifications', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('title');
        //     $table->text('message');
        //     $table->timestamps();
        // });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->nullable->constrained('devices')->onDelete('cascade'); // relasi device
            $table->string('title');
            $table->text('message');
            $table->boolean('is_read')->default(false); // opsional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
