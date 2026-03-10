<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlarmLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_name',
        'smoke_level',
        'resolved_by_rfid',
        'triggered_at',
        'resolved_at',
    ];

    // Mengubah tipe data kolom waktu agar otomatis menjadi objek Carbon (Datetime)
    protected $casts = [
        'triggered_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];
}
