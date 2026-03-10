<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController; // Tambahan
use App\Http\Controllers\AdminController;  // Tambahan
use App\Http\Middleware\EnsureIsAdmin;     // Tambahan
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Jalur bawaan Breeze (Bisa kamu jadikan Homepage User nanti)
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- JALUR KHUSUS USER ---
Route::middleware('auth')->group(function () {
    // Profil bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Jalur untuk mengirim laporan kerusakan sarpras
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
});

// --- JALUR KHUSUS ADMIN ---
// Memasang 2 gembok: harus 'auth' (login) dan harus lewat 'EnsureIsAdmin'
Route::middleware(['auth', EnsureIsAdmin::class])->prefix('admin')->name('admin.')->group(function () {

    // Halaman Dashboard Admin (URL: /admin/dashboard)
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Fungsi Update Status Tiket Laporan
    Route::patch('/reports/{report}', [AdminController::class, 'updateReportStatus'])->name('reports.update');

    // Fungsi Mengubah Role User
    Route::patch('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.role.update');
});

require __DIR__.'/auth.php';
