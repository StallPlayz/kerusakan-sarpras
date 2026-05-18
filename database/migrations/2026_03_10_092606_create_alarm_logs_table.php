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
            $table->string('room_name')->default('F205');
            $table->integer('smoke_level');
            $table->float('temperature');
            $table->string('status');
            $table->string('resolved_by_rfid')->nullable();
            $table->timestamp('triggered_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alarm_logs');
    }
};
