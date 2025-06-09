<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LuxReading;

class LuxController extends Controller
{
    // SIMULASI / TESTING ONLY
    private static $luxData = [
        'value' => 300.0, // nilai default lux (misalnya 0 - 100,000 lux)
        'recorded_at' => null,
    ];

    public function index()
    {
        return response()->json([
            'lux' => self::$luxData['value'],
            'recorded_at' => self::$luxData['recorded_at']
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'lux' => 'required|numeric|min:0|max:100000',
            'device_id' => 'required|exists:devices,id',
        ]);

        // Simpan ke database
        $reading = new LuxReading();
        $reading->device_id = $request->device_id;
        $reading->value = $request->lux;
        $reading->recorded_at = now();
        $reading->save();

        // Simpan ke static variable untuk simulasi
        self::$luxData['value'] = $request->lux;
        self::$luxData['recorded_at'] = $reading->recorded_at;

        return response()->json([
            'message' => 'Lux value updated successfully',
            'lux' => self::$luxData['value'],
            'recorded_at' => self::$luxData['recorded_at']
        ]);
    }
}
