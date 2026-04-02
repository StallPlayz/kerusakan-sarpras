<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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
        // 1. Validasi Input
        $request->validate([
            'room' => 'required|string|max:255',
            'item' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $imagePath = null;

        // 2. Logika Pengolahan Gambar
        if ($request->hasFile('image')) {
            if (!file_exists(storage_path('app/public/reports'))) {
                mkdir(storage_path('app/public/reports'), 0755, true);
            }

            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('image')->getRealPath());
            $imageName = 'reports/' . uniqid() . '.webp';
            $fullPath = storage_path('app/public/' . $imageName);
            $image->toWebp(80)->save($fullPath);

            $imagePath = $imageName;
        }

        // 3. Simpan ke Database
        Report::create([
            'user_id' => auth()->id(),
            'room' => $request->room,
            'item' => $request->item,
            'description' => $request->description,
            'image_path' => $imagePath,
            'status' => 'Belum Dikonfirmasi',
        ]);

        return redirect()->route('reports.index')->with('success', 'Laporan berhasil dibuat!');
    }
}
