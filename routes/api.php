<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\Auth\GoogleAuthController;
use App\Http\Controllers\Api\TankLevelsController;

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

Route::post('/testingDevice', [TestController::class, 'testingDevice']);

Route::get('/tank-levels', [TankLevelsController::class, 'index']);
Route::post('/tank-levels', [TankLevelsController::class, 'update']);
Route::get('/auth/google', [GoogleAuthController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
