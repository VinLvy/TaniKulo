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
                'message' => 'Humidity updated successfully' ,
                'humidity' => self::$humidity
            ]);
        }

        return Response::json([
            'humidity' => self::$humidity
        ]);
    }
}
