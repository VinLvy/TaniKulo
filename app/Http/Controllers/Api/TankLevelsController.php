<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TankLevelsController extends Controller
{
    //ganti data level air dan fertilize disini
    private static $waterLevel = 0.6;
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
}
