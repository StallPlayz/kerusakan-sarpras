<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    // Menyimpan laporan baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_or_room' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Menyimpan data dengan mengaitkannya ke user yang sedang login
        $request->user()->reports()->create([
            'item_or_room' => $validated['item_or_room'],
            'description' => $validated['description'],
            'status' => 'Belum Dikonfirmasi',
        ]);

        return redirect()->back()->with('message', 'Laporan berhasil dikirim ke Admin!');
    }
}
