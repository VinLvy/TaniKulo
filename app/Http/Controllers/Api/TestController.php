<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TestController extends Controller
{
    private static $humidity = 10; //ganti humidity disini

    public function __invoke(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'humidity' => 'required|numeric|between:0,100'
            ]);

            self::$humidity = $validated['humidity'];

            return Response::json([
                'message' => 'Humidity updated successfully',
                'humidity' => self::$humidity
            ]);
        }

        return Response::json([
            'humidity' => self::$humidity
        ]);
    }

    // testing get data from device 
    public function testingDevice(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'humidity' => 'required|numeric|between:0,100'
            ]);

            $humidity = $validated['humidity'];

            return response()->json([
                'message' => 'Testing Humidity Successful',
                'humidity' => $humidity
            ], 200);
        }

        return response()->json([
            'message' => 'Gagal bos'
        ], 400);
    }
}
