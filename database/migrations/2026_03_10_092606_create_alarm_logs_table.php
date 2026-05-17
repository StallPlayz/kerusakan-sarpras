<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alarm_logs', function (Blueprint $table) {
            $table->id();
            $table->string('room_name')->default('F205'); // Nama Ruangan
            $table->integer('smoke_level');               // Kadar Asap
            $table->float('temperature');                 // Suhu (Float karena ada koma)
            $table->string('status');                     // Status bahaya/aman
            $table->string('resolved_by_rfid')->nullable(); // UID Kartu (Bisa kosong)
            $table->timestamp('triggered_at')->nullable();  // Waktu kejadian
            $table->timestamp('resolved_at')->nullable();   // Waktu dimatikan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alarm_logs');
    }
};
