<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TestController;

Route::match(['get', 'post'], '/tes', [TestController::class, '__invoke']);

Route::get('/cek', function () {
    return response()->json(['cek' => 'berhasil']);
});

Route::post('/kirim-data', function (Illuminate\Http\Request $request) {
    return response()->json([
        'status' => 'received',
        'data' => $request->all()
    ]);
});
