<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    // Menampilkan halaman riwayat laporan user
    public function index(Request $request)
    {
        return Inertia::render('Reports/Index', [
            'reports' => $request->user()->reports()->latest()->get()
        ]);
    }

    // Menampilkan form laporan baru
    public function create()
    {
        return Inertia::render('Reports/Create');
    }

    // Menyimpan laporan baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_or_room' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $request->user()->reports()->create([
            'item_or_room' => $validated['item_or_room'],
            'description' => $validated['description'],
            'status' => 'Belum Dikonfirmasi',
        ]);

        // Mengarahkan user kembali ke halaman riwayat setelah berhasil melapor
        return redirect()->route('reports.index');
    }
}
