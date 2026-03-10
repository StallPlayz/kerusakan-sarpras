<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Models\AlarmLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    // Menampilkan halaman Dashboard Admin dengan semua data
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'reports' => Report::with('user:id,name')->latest()->get(),
            'users' => User::select('id', 'name', 'email', 'role', 'rfid_uid')->get(),
            'alarm_logs' => AlarmLog::latest()->get(),
        ]);
    }

    // Mengupdate status tiket laporan
    public function updateReportStatus(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:Belum Dikonfirmasi,Diproses,Selesai'
        ]);

        $report->update(['status' => $validated['status']]);

        return redirect()->back()->with('message', 'Status tiket berhasil diperbarui!');
    }

    // Mengubah role user (Admin/User)
    public function updateUserRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,user'
        ]);

        $user->update(['role' => $validated['role']]);

        return redirect()->back()->with('message', 'Role user berhasil diubah!');
    }
}
