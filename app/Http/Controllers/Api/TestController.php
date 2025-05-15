<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class TestController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json(['pesan' => 'Test REST API Controller Laravel 12']);
    }
}
