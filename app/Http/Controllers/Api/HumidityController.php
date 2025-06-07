<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HumidityReading;
use Illuminate\Support\Carbon;

class HumidityController extends Controller
{
    // SIMULASI / TESTING ONLY
    private static $humidityData = [
        'value' => 0.45, // default humidity value (0 - 1)
        'recorded_at' => null,
    ];

    public function index()
    {
        return response()->json([
            'humidity' => self::$humidityData['value'],
            'recorded_at' => self::$humidityData['recorded_at']
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'humidity' => 'required|numeric|min:0|max:1',
            'device_id' => 'required|exists:devices,id', // pastikan ID device valid
        ]);

        // Simpan ke database
        $reading = new HumidityReading();
        $reading->device_id = $request->device_id;
        $reading->value = $request->humidity;
        $reading->recorded_at = now();
        $reading->save();

        // Simpan ke variabel static (simulasi/testing)
        self::$humidityData['value'] = $request->humidity;
        self::$humidityData['recorded_at'] = $reading->recorded_at;

        return response()->json([
            'message' => 'Humidity value updated successfully',
            'humidity' => self::$humidityData['value'],
            'recorded_at' => self::$humidityData['recorded_at']
        ]);
    }
}
