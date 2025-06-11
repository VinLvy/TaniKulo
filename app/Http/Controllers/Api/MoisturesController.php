<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MoistureReading;
use Illuminate\Http\Request;

class MoisturesController extends Controller
{
    // TESTING PURPOSES ONLY
    private static $moistureData = [
        'value' => 0.55, // default moisture value (0 - 1)
        'recorded_at' => null,
    ];

    public function index()
    {
        return response()->json([
            'moisture' => self::$moistureData['value'],
            'recorded_at' => self::$moistureData['recorded_at']
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'moisture' => 'required|numeric|min:0|max:1',
        ]);

        self::$moistureData['value'] = $request->moisture;
        self::$moistureData['recorded_at'] = now();

        return response()->json([
            'message' => 'Moisture value updated successfully',
            'moisture' => self::$moistureData['value'],
            'recorded_at' => self::$moistureData['recorded_at']
        ]);
    }

    public function store(Request $request)
    {
        // Validasi request
        $request->validate([
            'moisture' => 'required|numeric|min:0|max:100',
            'status' => 'required|string',
        ]);

        // Simpan data ke database
        $reading = MoistureReading::create([
            'moisture' => $request->moisture,
            'status' => $request->status,
            'recorded_at' => now(),
        ]);

        // Beri respon sukses
        return response()->json([
            'message' => 'Data kelembapan berhasil disimpan.',
            'data' => $reading
        ], 201);
    }
}
