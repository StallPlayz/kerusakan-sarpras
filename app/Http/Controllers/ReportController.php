<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Reports/Index', [
            'reports' => $request->user()->reports()->latest()->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Reports/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'room' => 'required|string|max:255',
            'item' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            if (!file_exists(storage_path('app/public/reports'))) {
                mkdir(storage_path('app/public/reports'), 0755, true);
            }

            $manager = ImageManager::usingDriver(Driver::class);
            $image = $manager->decode($request->file('image')->getRealPath());
            $imageName = 'reports/' . uniqid() . '.webp';
            $fullPath = storage_path('app/public/' . $imageName);
            $image->save($fullPath, quality: 80);
            $imagePath = $imageName;
        }

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
