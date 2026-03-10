<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alarm_logs', function (Blueprint $table) {
            $table->id();
            $table->string('room_name')->default('F205'); // Mengunci nilai default ke ruangan
            $table->string('smoke_level')->nullable(); // Menyimpan kadar asap saat alarm berbunyi
            $table->string('resolved_by_rfid')->nullable();
            $table->timestamp('triggered_at')->useCurrent(); // Waktu alarm mulai berbunyi
            $table->timestamp('resolved_at')->nullable(); // Waktu alarm dimatikan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alarm_logs');
    }
};
