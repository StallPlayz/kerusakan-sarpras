<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AlarmController;

// Jalur ini akan menjadi: http://localhost:8000/api/alarm-log
Route::post('/alarm-log', [AlarmController::class, 'store']);
