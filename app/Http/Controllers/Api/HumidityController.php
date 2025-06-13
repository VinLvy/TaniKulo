<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\HumidityReading;
use App\Models\HumiditySetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

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
        ]);

        self::$humidityData['value'] = $request->humidity;
        self::$humidityData['recorded_at'] = now();

        return response()->json([
            'message' => 'Humidity value updated successfully',
            'humidity' => self::$humidityData['value'],
            'recorded_at' => self::$humidityData['recorded_at']
        ]);
    }

    /**
     * Simpan data kelembapan udara dari IoT
     */
    public function store(Request $request)
    {
        $request->validate([
            'humidity' => 'required|numeric|min:0|max:100',
            'status' => 'required|string',
            'device_id' => 'nullable|exists:devices,id',
        ]);

        $reading = HumidityReading::create([
            'device_id' => $request->device_id,
            'value' => $request->humidity,
            'status' => $request->status,
            'recorded_at' => now(),
        ]);

        if ($reading) {
            return response()->json([
                'message' => 'Data kelembapan udara berhasil disimpan.',
                'data' => $reading
            ], 201);
        } else {
            return response()->json([
                'message' => 'Gagal menyimpan data.',
            ], 500);
        }
    }

    /**
     * Simpan pengaturan kelembapan terbaru dan kirim ke IoT
     */
    public function settingHumidityStore(Request $request)
    {
        $request->validate([
            'warnLower' => 'required|numeric|min:0|max:100',
            'warnUpper' => 'required|numeric|min:0|max:100',
            'set_by' => 'required|string',
            'device_id' => 'nullable|exists:devices,id',
        ]);

        if ($request->device_id) {
            HumiditySetting::where('device_id', $request->device_id)
                ->where('status', 'online')
                ->update(['status' => 'offline']);
        }

        $setting = HumiditySetting::create([
            'device_id' => $request->device_id,
            'warnLower' => $request->warnLower,
            'warnUpper' => $request->warnUpper,
            'status' => 'online',
            'set_by' => $request->set_by,
            'recorded_at' => now(),
        ]);

        if ($setting) {
            try {
                $iotUrl = "http://your-iot-device.local/api/set-humidity?" . http_build_query([
                    'warnLower' => $request->warnLower,
                    'warnUpper' => $request->warnUpper,
                    'device_id' => $request->device_id
                ]);

                $response = Http::get($iotUrl);

                return response()->json([
                    'message' => 'Pengaturan sensor kelembapan berhasil disimpan dan dikirim ke perangkat IoT.',
                    'data' => $setting,
                    'iot_response' => $response->body()
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Pengaturan disimpan, tetapi gagal mengirim ke perangkat IoT.',
                    'data' => $setting,
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'message' => 'Gagal menyimpan pengaturan.',
        ], 500);
    }

    public function settingHumidityUpdate(Request $request, $id)
    {
        $request->validate([
            'warnLower' => 'required|numeric|min:0|max:100',
            'warnUpper' => 'required|numeric|min:0|max:100',
            'status' => 'required|string',
            'set_by' => 'required|string',
        ]);

        $setting = HumiditySetting::find($id);

        if (!$setting) {
            return response()->json([
                'message' => 'Data pengaturan tidak ditemukan.',
            ], 404);
        }

        $setting->update([
            'warnLower' => $request->warnLower,
            'warnUpper' => $request->warnUpper,
            'status' => $request->status,
            'set_by' => $request->set_by,
            'recorded_at' => now(),
        ]);

        return response()->json([
            'message' => 'Pengaturan sensor kelembapan berhasil diperbarui.',
            'data' => $setting
        ], 200);
    }
}
