<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\WaterLogReading;
use App\Models\TankLevelsReading;
use App\Models\WaterLevelReading;
use App\Models\WaterLevelSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\FertilizerLevelReading;
use App\Models\FertilizerLevelSetting;

class TankLevelsController extends Controller
{
    //ganti data level air dan fertilize disini
    private static $waterLevel = 0.7;
    private static $fertilizerLevel = 0.4;

    public function index()
    {
        $waterLevel = self::$waterLevel > 1 ? 1 : self::$waterLevel;
        return response()->json([
            'water_level' => $waterLevel,
            'fertilizer_level' => self::$fertilizerLevel
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'water_level' => 'nullable|numeric|min:0|max:1',
            'fertilizer_level' => 'nullable|numeric|min:0|max:1',
        ]);

        if ($request->has('water_level')) {
            self::$waterLevel = $request->water_level;
        }

        if ($request->has('fertilizer_level')) {
            self::$fertilizerLevel = $request->fertilizer_level;
        }

        return response()->json([
            'water_level' => self::$waterLevel,
            'fertilizer_level' => self::$fertilizerLevel
        ]);
    }

    //store data from water tank
    public function waterLogsStore(Request $request)
    {
        $request->validate([
            'value' => 'required|numeric|min:0|max:100',
            'status' => 'required|string',
        ]);

        $reading = WaterLevelReading::create([
            'value' => $request->value,
            'status' => $request->status,
            'recorded_at' => now(),
        ]);

        if ($reading) {
            return response()->json([
                'message' => 'Data kapasitas tanki air berhasil disimpan.',
                'data' => $reading
            ], 201);
        } else {
            return response()->json([
                'message' => 'Gagal menyimpan data.',
            ], 500);
        }
    }

    //store data from fertilizer tank
    public function fertilizerLogsStore(Request $request)
    {
        $request->validate([
            'value' => 'required|numeric|min:0|max:100',
            'status' => 'required|string',
        ]);

        $reading = FertilizerLevelReading::create([
            'value' => $request->value,
            'status' => $request->status,
            'recorded_at' => now(),
        ]);

        if ($reading) {
            return response()->json([
                'message' => 'Data kapasitas tanki pupuk berhasil disimpan.',
                'data' => $reading
            ], 201);
        } else {
            return response()->json([
                'message' => 'Gagal menyimpan data.',
            ], 500);
        }
    }

    // SETTING FOR WATER & FERTILIZER
    public function settingWaterStore(Request $request)
    {
        // Validasi input
        $request->validate([
            'warnLower' => 'required|numeric|min:0|max:100',
            'warnUpper' => 'required|numeric|min:0|max:100',
            'set_by' => 'required|string',
            'device_id' => 'nullable|exists:devices,id'
        ]);

        // Tandai setting sebelumnya sebagai offline untuk device yang sama (jika ada)
        if ($request->device_id) {
            WaterLevelSetting::where('device_id', $request->device_id)
                ->where('status', 'online')
                ->update(['status' => 'offline']);
        }

        $createSetting = WaterLevelSetting::create([
            'device_id' => $request->device_id,
            'warnLower' => $request->warnLower,
            'warnUpper' => $request->warnUpper,
            'status' => "online",
            'set_by' => $request->set_by,
            'recorded_at' => now(),
        ]);

        // Push ke IoT jika berhasil
        if ($createSetting) {
            try {
                // Ganti URL dengan endpoint IoT sebenarnya
                $iotUrl = "http://your-iot-device.local/api/set-moisture?"
                    . http_build_query([
                        'warnLower' => $request->warnLower,
                        'warnUpper' => $request->warnUpper,
                        'device_id' => $request->device_id
                    ]);

                $response = Http::get($iotUrl);

                return response()->json([
                    'message' => 'Pengaturan sensor berhasil disimpan dan dikirim ke perangkat IoT.',
                    'data' => $createSetting,
                    'iot_response' => $response->body()
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Pengaturan disimpan, tapi gagal mengirim ke perangkat IoT.',
                    'data' => $createSetting,
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'message' => 'Gagal menyimpan data.',
        ], 500);
    }

    // FERTILIZER
    public function settingFertilizerStore(Request $request)
    {
        // Validasi input
        $request->validate([
            'warnLower' => 'required|numeric|min:0|max:100',
            'warnUpper' => 'required|numeric|min:0|max:100',
            'set_by' => 'required|string',
            'device_id' => 'nullable|exists:devices,id'
        ]);

        // Tandai setting sebelumnya sebagai offline untuk device yang sama (jika ada)
        if ($request->device_id) {
            FertilizerLevelSetting::where('device_id', $request->device_id)
                ->where('status', 'online')
                ->update(['status' => 'offline']);
        }

        $createSetting = FertilizerLevelSetting::create([
            'device_id' => $request->device_id,
            'warnLower' => $request->warnLower,
            'warnUpper' => $request->warnUpper,
            'status' => "online",
            'set_by' => $request->set_by,
            'recorded_at' => now(),
        ]);

        // Push ke IoT jika berhasil
        if ($createSetting) {
            try {
                // Ganti URL dengan endpoint IoT sebenarnya
                $iotUrl = "http://your-iot-device.local/api/set-moisture?"
                    . http_build_query([
                        'warnLower' => $request->warnLower,
                        'warnUpper' => $request->warnUpper,
                        'device_id' => $request->device_id
                    ]);

                $response = Http::get($iotUrl);

                return response()->json([
                    'message' => 'Pengaturan sensor berhasil disimpan dan dikirim ke perangkat IoT.',
                    'data' => $createSetting,
                    'iot_response' => $response->body()
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Pengaturan disimpan, tapi gagal mengirim ke perangkat IoT.',
                    'data' => $createSetting,
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'message' => 'Gagal menyimpan data.',
        ], 500);
    }
}
