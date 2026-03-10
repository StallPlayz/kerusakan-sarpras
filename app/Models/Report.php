<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    // Kolom-kolom yang boleh diisi datanya melalui form
    protected $fillable = [
        'user_id',
        'item_or_room',
        'description',
        'status',
    ];

    // --- RELASI DATABASE: Laporan ini milik satu User ---
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
