<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\Auth\GoogleAuthController;
use App\Http\Controllers\Api\TankLevelsController;
use App\Http\Controllers\Api\DeviceDataController;

// Tes routes
Route::match(['get', 'post'], '/tes', [TestController::class, '__invoke']);
Route::get('/cek', fn () => response()->json(['cek' => 'berhasil']));
Route::post('/kirim-data', fn (Request $request) => response()->json([
    'status' => 'received',
    'data' => $request->all(),
]));

// Authenticated user route
Route::middleware('auth:sanctum')->get('/user', fn (Request $request) => $request->user());

// Group: Auth Google
Route::prefix('auth/google')->controller(GoogleAuthController::class)->group(function () {
    Route::get('/', 'redirect');         // GET /auth/google
    Route::get('/callback', 'callback'); // GET /auth/google/callback
});

// Group: Tank Levels
Route::prefix('tank-levels')->controller(TankLevelsController::class)->group(function () {
    Route::get('/', 'index');     // GET /tank-levels
    Route::post('/', 'update');   // POST /tank-levels
});

// Group: Device Data
Route::prefix('devices')->group(function () {
    Route::get('/{serial}/status', [DeviceDataController::class, 'status']);
    Route::post('/{serial}/data', [DeviceDataController::class, 'sendData']);
});
