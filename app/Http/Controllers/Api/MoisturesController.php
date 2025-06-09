<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
}
