<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AlarmController;

// Jalur ini akan menjadi: http://localhost:8000/api/alarm
Route::post('/alarm', [AlarmController::class, 'store']);
