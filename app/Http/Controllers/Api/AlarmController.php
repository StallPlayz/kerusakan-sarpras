<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AlarmLog;
use Illuminate\Http\Request;

class AlarmController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi data yang masuk dari ESP32
        $request->validate([
            'smoke_level' => 'required|numeric',
            'temperature' => 'required|numeric',
            'status'      => 'required|string',
        ]);

        // 2. Simpan semua data ke database
        $alarm = AlarmLog::create([
            'room_name'        => $request->room_name ?? 'F205',
            'smoke_level'      => $request->smoke_level,
            'temperature'      => $request->temperature,
            'status'           => $request->status,
            'resolved_by_rfid' => $request->resolved_by_rfid,
            'triggered_at'     => now(), // Otomatis mencatat waktu saat ini
        ]);

        // 3. Kirim balasan sukses ke ESP32
        return response()->json([
            'status'  => 'success',
            'message' => 'Sinyal darurat berhasil diterima server!',
            'data'    => $alarm
        ], 201);
    }
}
