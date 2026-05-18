<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'category',
        'room_name',
        'item_name',
        'description',
        'image_path',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
