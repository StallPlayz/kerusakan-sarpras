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

Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = auth()->user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- JALUR KHUSUS USER ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
});

// --- JALUR KHUSUS ADMIN ---
Route::middleware(['auth', EnsureIsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::patch('/reports/{report}', [AdminController::class, 'updateReportStatus'])->name('reports.update');
    Route::patch('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.role.update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
});

require __DIR__ . '/auth.php';
