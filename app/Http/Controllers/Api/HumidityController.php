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

    //Data Soil Moisture
    public function index()
    {
        //get data from database or model
        $HumidityData = HumidityReading::latest('recorded_at')->first();

        if (!$HumidityData) {
            return response()->json([
                'success' => false,
                'message' => 'Data moisture tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'moisture' => $HumidityData->value,
            'recorded_at' => $HumidityData->recorded_at
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
            'temperature' => 'required|numeric|min:0|max:100',
            'status' => 'required|string',
        ]);

        $reading = HumidityReading::create([
            'humidity' => $request->humidity,
            'temperature' => $request->temperature,
            'status' => $request->status,
            'recorded_at' => now(),
        ]);

        if ($reading) {
            return response()->json([
                'message' => 'Data kelembapan udara & suhu berhasil disimpan.',
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
            'warnLowerTemperature' => 'required|numeric|min:0|max:100',
            'warnUpperTemperature' => 'required|numeric|min:0|max:100',
            'warnLowerHumidity' => 'required|numeric|min:0|max:100',
            'warnUpperHumidity' => 'required|numeric|min:0|max:100',
            'set_by' => 'required|string',
        ]);

        if ($request->device_id) {
            HumiditySetting::where('device_id', $request->device_id)
                ->where('status', 'online')
                ->update(['status' => 'offline']);
        }

        $setting = HumiditySetting::create([
            'warnLowerTemperature' => $request->warnLowerTemperature,
            'warnUpperTemperature' => $request->warnUpperTemperature,
            'warnLowerHumidity' => $request->warnLowerHumidity,
            'warnUpperHumidity' => $request->warnUpperHumidity,
            'status' => 'online',
            'set_by' => $request->set_by,
            'recorded_at' => now(),
        ]);

        if ($setting) {
            try {
                $iotUrl = "http://your-iot-device.local/api/set-humidity?" . http_build_query([
                    'warnLowerTemperature' => $request->warnLowerTemperature,
                    'warnUpperTemperature' => $request->warnUpperTemperature,
                    'warnLowerHumidity' => $request->warnLowerHumidity,
                    'warnUpperHumidity' => $request->warnUpperHumidity,
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
            'warnLowerTemperature' => 'required|numeric|min:0|max:100',
            'warnUpperTemperature' => 'required|numeric|min:0|max:100',
            'warnLowerHumidity' => 'required|numeric|min:0|max:100',
            'warnUpperHumidity' => 'required|numeric|min:0|max:100',
            'set_by' => 'required|string',
        ]);

        $setting = HumiditySetting::find($id);

        if (!$setting) {
            return response()->json([
                'message' => 'Data pengaturan tidak ditemukan.',
            ], 404);
        }

        $setting->update([
            'warnLowerTemperature' => $request->warnLowerTemperature,
            'warnUpperTemperature' => $request->warnUpperTemperature,
            'warnLowerHumidity' => $request->warnLowerHumidity,
            'warnUpperHumidity' => $request->warnUpperHumidity,
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
