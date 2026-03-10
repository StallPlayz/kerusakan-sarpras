<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AlarmLog;
use Illuminate\Http\Request;

class AlarmController extends Controller
{
    // Menerima sinyal bahaya dari ESP32
    public function store(Request $request)
    {
        // Validasi data yang masuk dari ESP32
        $request->validate([
            'smoke_level' => 'required|numeric',
        ]);

        // Simpan ke database (otomatis diset ke ruang F205 dan waktu saat ini)
        $alarm = AlarmLog::create([
            'room_name' => 'F205',
            'smoke_level' => $request->smoke_level,
            // resolved_at dan resolved_by_rfid dibiarkan kosong karena api belum padam
        ]);

        // Kembalikan balasan (response) ke ESP32 bahwa data berhasil diterima
        return response()->json([
            'status' => 'success',
            'message' => 'Sinyal darurat berhasil diterima server!',
            'data' => $alarm
        ], 201);
    }
}
