<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\MoistureReading;
use App\Models\MoisturesSetting;
use App\Http\Controllers\Controller;

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

    public function store(Request $request)
    {
        // Validasi request
        $request->validate([
            'moisture' => 'required|numeric|min:0|max:100',
            'status' => 'required|string',
        ]);

        // Simpan data ke database
        $reading = MoistureReading::create([
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

    //    ---- CONTROLLER FOR SETTING SENSOR MOISTURE ----
    public function settingMoistureStore(Request $request)
    {
        // Validasi input
        $request->validate([
            'warnLower' => 'required|numeric|min:0|max:100',
            'warnUpper' => 'required|numeric|min:0|max:100',
            'set_by' => 'required|string',
        ]);

        MoisturesSetting::where('status', 'online')->update(['status' => 'offline']);


        $createSetting = MoisturesSetting::create([
            'warnLower' => $request->warnLower,
            'warnUpper' => $request->warnUpper,
            'status' => "online",
            'set_by' => $request->set_by,
        ]);

        // Respon
        if ($createSetting) {
            return response()->json([
                'message' => 'Pengaturan sensor berhasil disimpan.',
                'data' => $createSetting
            ], 201);
        } else {
            return response()->json([
                'message' => 'Gagal menyimpan data.',
            ], 500);
        }
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
            'recorded_at' => now(), // jika kamu ingin update waktu pencatatan juga
        ]);

        return response()->json([
            'message' => 'Pengaturan sensor berhasil diperbarui.',
            'data' => $setting
        ], 200);
    }
}
