<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\Auth\GoogleAuthController;
use App\Http\Controllers\Api\TankLevelsController;
use App\Http\Controllers\Api\DeviceDataController;
use App\Http\Controllers\Api\MoisturesController;

// Tes routes
Route::match(['get', 'post'], '/tes', [TestController::class, '__invoke'])->name('tes.invoke');
Route::get('/cek', fn () => response()->json(['cek' => 'berhasil']))->name('cek');
Route::post('/kirim-data', fn (Request $request) => response()->json([
    'status' => 'received',
    'data' => $request->all(),
]))->name('kirim-data');

// Authenticated user route
Route::middleware('auth:sanctum')->get('/user', fn (Request $request) => $request->user())->name('user');

// Group: Auth Google
Route::prefix('auth/google')->as('auth.google.')->controller(GoogleAuthController::class)->group(function () {
    Route::get('/', 'redirect')->name('redirect');         // auth.google.redirect
    Route::get('/callback', 'callback')->name('callback'); // auth.google.callback
});

// Group: Tank Levels
Route::prefix('tank-levels')->as('tank-levels.')->controller(TankLevelsController::class)->group(function () {
    Route::get('/', 'index')->name('index');     // tank-levels.index
    Route::post('/', 'update')->name('update');  // tank-levels.update
});

// Group: Moistures
Route::prefix('moistures')->as('moistures.')->controller(MoisturesController::class)->group(function () {
    Route::get('/', 'index')->name('index');     // moistures.index
    Route::post('/', 'update')->name('update');  // moistures.update
});

// Group: Device Data
Route::prefix('devices')->as('devices.')->group(function () {
    Route::get('/{serial}/status', [DeviceDataController::class, 'status'])->name('status');     // devices.status
    Route::post('/{serial}/data', [DeviceDataController::class, 'sendData'])->name('send-data'); // devices.send-data
});
