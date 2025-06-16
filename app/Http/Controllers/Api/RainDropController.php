<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RainDropReading;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class RainDropController extends Controller
{
    public function index()
    {
        $RainDropData = RainDropReading::latest('recorded_at')->first();

        if (!$RainDropData) {
            return response()->json([
                'success' => false,
                'message' => 'Data moisture tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'rainDrop' => $RainDropData->value,
            'status' => $RainDropData->status,
            'recorded_at' => $RainDropData->recorded_at
        ]);
    }

    //store data from IoT to Database
    public function store(Request $request)
    {
        // Validasi request
        $request->validate([
            'rain_value' => 'required|numeric|min:0|max:100',
            'status' => 'required|string',
        ]);

        // Simpan data ke database
        $reading = RainDropReading::create([
            'rain_value' => $request->rain_value,
            'status' => $request->status,
            'recorded_at' => now(),
        ]);

        // Beri respon sukses
        if ($reading) {
            return response()->json([
                'message' => 'Data Hujan berhasil disimpan.',
                'data' => $reading
            ], 201);
        } else {
            return response()->json([
                'message' => 'Gagal menyimpan data.',
            ], 500);
        }
    }

    //upload settings ( STILL DON'T KNOW IF THIS NEED OR NO !  )
    public function settingRainDropStore(Request $request)
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
            RainDropReading::where('device_id', $request->device_id)
                ->where('status', 'online')
                ->update(['status' => 'offline']);
        }

        $createSetting = RainDropReading::create([
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
