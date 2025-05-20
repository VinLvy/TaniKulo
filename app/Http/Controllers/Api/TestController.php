<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json([
            'pesan' => 'API dari Laravel berhasil diakses!'
        ]);
    }
}
