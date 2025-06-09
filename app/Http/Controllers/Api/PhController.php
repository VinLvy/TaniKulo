<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhReading;

class PhController extends Controller
{
    // SIMULASI / TESTING ONLY
    private static $phData = [
        'value' => 6.5, // nilai default pH (umumnya antara 0 - 14)
        'recorded_at' => null,
    ];

    public function index()
    {
        return response()->json([
            'ph' => self::$phData['value'],
            'recorded_at' => self::$phData['recorded_at']
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'ph' => 'required|numeric|min:0|max:14',
            'device_id' => 'required|exists:devices,id',
        ]);

        // Simpan ke database
        $reading = new PhReading();
        $reading->device_id = $request->device_id;
        $reading->value = $request->ph;
        $reading->recorded_at = now();
        $reading->save();

        // Simpan ke static variable untuk simulasi
        self::$phData['value'] = $request->ph;
        self::$phData['recorded_at'] = $reading->recorded_at;

        return response()->json([
            'message' => 'pH value updated successfully',
            'ph' => self::$phData['value'],
            'recorded_at' => self::$phData['recorded_at']
        ]);
    }
}
