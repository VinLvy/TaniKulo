<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\WaterLogReading;
use App\Http\Controllers\Controller;
use App\Models\FertilizerLevelReading;
use App\Models\TankLevelsReading;
use App\Models\WaterLevelReading;

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
}
