<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            // Menghubungkan laporan dengan user yang melapor
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('item_or_room'); // Nama barang atau ruangan yang rusak
            $table->text('description'); // Detail kerusakan
            $table->enum('status', ['Belum Dikonfirmasi', 'Diproses', 'Selesai'])->default('Belum Dikonfirmasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
