<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlarmLog extends Model
{
    use HasFactory;

    // Tambahkan 'temperature' dan 'status' ke dalam list ini
    protected $fillable = [
        'room_name',
        'smoke_level',
        'temperature',
        'status',
        'resolved_by_rfid',
        'triggered_at',
        'resolved_at',
    ];

    protected $casts = [
        'triggered_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];
}
