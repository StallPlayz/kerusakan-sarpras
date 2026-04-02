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

// Jalur bawaan Breeze (Homepage (WIP))
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- JALUR KHUSUS USER ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 1. Jalur untuk melihat riwayat laporan
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    // 2. Jalur untuk membuka halaman form laporan baru
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    // 3. Jalur untuk memproses form
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
});

// --- JALUR KHUSUS ADMIN ---
// Memasang 2 gembok: harus 'auth' (login) dan harus lewat 'EnsureIsAdmin'
Route::middleware(['auth', EnsureIsAdmin::class])->prefix('admin')->name('admin.')->group(function () {

    // 1. Halaman Dashboard Admin (URL: /admin/dashboard)
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // 2. Fungsi Update Status Tiket Laporan
    Route::patch('/reports/{report}', [AdminController::class, 'updateReportStatus'])->name('reports.update');

    // 3. Fungsi Mengubah Role User
    Route::patch('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.role.update');

    // 4. Jalur untuk menghapus user
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
});

require __DIR__ . '/auth.php';
