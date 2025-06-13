<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\MoistureReading;
use App\Models\MoisturesSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

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

    /**
     * Store data kelembapan dari IoT ke database
     */
    public function store(Request $request)
    {
        // Validasi request
        $request->validate([
            'moisture' => 'required|numeric|min:0|max:100',
            'status' => 'required|string',
            'device_id' => 'nullable|exists:devices,id'
        ]);

        // Simpan data ke database
        $reading = MoistureReading::create([
            'device_id' => $request->device_id,
            'moisture' => $request->moisture,
            'status' => $request->status,
            'recorded_at' => now(),
        ]);

        // Beri respon sukses
        if ($reading) {
            return response()->json([
                'message' => 'Data kelembapan berhasil disimpan.',
                'data' => $reading
            ], 201);
        } else {
            return response()->json([
                'message' => 'Gagal menyimpan data.',
            ], 500);
        }
    }

    /**
     * Simpan pengaturan kelembapan dari aplikasi + kirim ke IoT
     */
    public function settingMoistureStore(Request $request)
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
            MoisturesSetting::where('device_id', $request->device_id)
                ->where('status', 'online')
                ->update(['status' => 'offline']);
        }

        $createSetting = MoisturesSetting::create([
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

    public function settingMoistureUpdate(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'warnLower' => 'required|numeric|min:0|max:100',
            'warnUpper' => 'required|numeric|min:0|max:100',
            'status' => 'required|string',
            'set_by' => 'required|string',
        ]);

        // Cari data berdasarkan ID
        $setting = MoisturesSetting::find($id);

        if (!$setting) {
            return response()->json([
                'message' => 'Data tidak ditemukan.',
            ], 404);
        }

        // Update data
        $setting->update([
            'warnLower' => $request->warnLower,
            'warnUpper' => $request->warnUpper,
            'status' => $request->status,
            'set_by' => $request->set_by,
            'recorded_at' => now(),
        ]);

        return response()->json([
            'message' => 'Pengaturan sensor berhasil diperbarui.',
            'data' => $setting
        ], 200);
    }
}
