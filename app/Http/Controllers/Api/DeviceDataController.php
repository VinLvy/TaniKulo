<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\MoistureReading;
use App\Models\HumidityReading;
use App\Models\LuxReading;
use App\Models\PhReading;

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
        ]);

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

        PhReading::create([
            'device_id' => $device->id,
            'value' => $request->input('ph', null), // Optional, if ph is not always sent
        ]);

        return response()->json(['message' => 'Sensor data stored successfully']);
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
