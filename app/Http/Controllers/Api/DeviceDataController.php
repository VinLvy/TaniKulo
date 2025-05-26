<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\MoistureReading;
use App\Models\HumidityReading;
use App\Models\LuxReading;
use App\Models\PhReading;
use App\Models\WaterLog;
use App\Models\FertilizerLog;

class DeviceDataController extends Controller
{
    public function sendData(Request $request, $serial)
    {
        $device = Device::where('serial_number', $serial)->first();

        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        $validated = $request->validate([
            'moisture' => 'required|numeric',
            'humidity' => 'required|numeric',
            'lux' => 'required|numeric',
            'ph' => 'nullable|numeric',
            'water_status' => 'nullable|boolean',
            'fertilizer_status' => 'nullable|boolean',
            'amount_water' => 'nullable|numeric',
            'amount_fertilizer' => 'nullable|numeric',
        ]);

        // Simpan data sensor
        MoistureReading::create([
            'device_id' => $device->id,
            'value' => $validated['moisture'],
        ]);

        HumidityReading::create([
            'device_id' => $device->id,
            'value' => $validated['humidity'],
        ]);

        LuxReading::create([
            'device_id' => $device->id,
            'value' => $validated['lux'],
        ]);

        if ($request->has('ph')) {
            PhReading::create([
                'device_id' => $device->id,
                'value' => $validated['ph'],
            ]);
        }

        // Simpan log penyiraman
        if ($request->has('water_status')) {
            WaterLog::create([
                'device_id' => $device->id,
                'status' => $validated['water_status'],
                'amount' => $validated['amount_water'] ?? null,
                'recorded_at' => now()
            ]);
        }

        // Simpan log pemupukan
        if ($request->has('fertilizer_status')) {
            FertilizerLog::create([
                'device_id' => $device->id,
                'status' => $validated['fertilizer_status'],
                'amount' => $validated['amount_fertilizer'] ?? null,
                'recorded_at' => now()
            ]);
        }

        return response()->json(['message' => 'Data stored successfully']);
    }

    public function status($serial)
    {
        $device = Device::where('serial_number', $serial)->first();

        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        return response()->json([
            'wifi' => [
                'ssid' => $device->ssid,
                'password' => $device->password,
                'is_active' => $device->is_active,
            ],
        ]);
    }
}
