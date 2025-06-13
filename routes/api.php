<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DeviceController;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\Auth\GoogleAuthController;
use App\Http\Controllers\Api\TankLevelsController;
use App\Http\Controllers\Api\DeviceDataController;
use App\Http\Controllers\Api\MoisturesController;
use App\Http\Controllers\Api\HumidityController;
use App\Http\Controllers\Api\LuxController;
use App\Http\Controllers\Api\PhController;
use App\Http\Controllers\Api\ExportController;

// Tes routes
Route::match(['get', 'post'], '/tes', [TestController::class, '__invoke'])->name('tes.invoke');
Route::get('/cek', fn() => response()->json(['cek' => 'berhasil']))->name('cek');
Route::post('/kirim-data', fn(Request $request) => response()->json([
    'status' => 'received',
    'data' => $request->all(),
]))->name('kirim-data');

// Authenticated user route
Route::middleware('auth:sanctum')->get('/user', fn(Request $request) => $request->user())->name('user');

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
    Route::post('/store', 'store')->name('store');  // moistures.store

    //setting moisture 
    Route::post('/settingMoisture', 'settingMoistureStore')->name('settingMoistureStore');
    // setting moisture update
    Route::post('/updateSettingMoisture', 'settingMoistureUpdate')->name('settingMoistureUpdate');
});

// Group: Humidity
Route::prefix('humidity')->as('humidity.')->controller(HumidityController::class)->group(function () {
    Route::get('/', 'index')->name('index');     // humidity.index
    Route::post('/', 'update')->name('update');  // humidity.update
});

// Group: Lux
Route::prefix('lux')->as('lux.')->controller(LuxController::class)->group(function () {
    Route::get('/', 'index')->name('index');     // lux.index
    Route::post('/', 'update')->name('update');  // lux.update
});

// Group: pH
Route::prefix('ph')->as('ph.')->controller(PhController::class)->group(function () {
    Route::get('/', 'index')->name('index');     // ph.index
    Route::post('/', 'update')->name('update');  // ph.update
});

// Group: Device Data
Route::prefix('devices')->as('devices.')->group(function () {
    Route::get('/{serial}/status', [DeviceDataController::class, 'status'])->name('status');     // devices.status
    Route::post('/{serial}/data', [DeviceDataController::class, 'sendData'])->name('send-data'); // devices.send-data
    Route::post('/register', [DeviceDataController::class, 'registerDevice'])->name('register'); // devices.register
    Route::post('/{serial}/wifi', [DeviceDataController::class, 'saveWifi'])->name('save-wifi');
});

// Group: Export
Route::prefix('export')->as('export.')->controller(ExportController::class)->group(function () {
    Route::get('/devices', 'devices')->name('devices');           // /api/export/devices
    Route::get('/moistures', 'moistures')->name('moistures');     // /api/export/moistures
    Route::get('/humidity', 'humidity')->name('humidity');        // /api/export/humidity
    Route::get('/ph', 'ph')->name('ph');                          // /api/export/ph
    Route::get('/lux', 'lux')->name('lux');                       // /api/export/lux
    Route::get('/fertilizer', 'fertilizer')->name('fertilizer'); // /api/export/fertilizer
    Route::get('/water', 'water')->name('water');                 // /api/export/water
});

Route::post('/device/calibrate',      [DeviceController::class, 'sendCalibration']);
Route::get('/device/data',           [DeviceController::class, 'receiveData']);

Route::post('/friend/calibrate',      [DeviceController::class, 'sendCalibrationToFriend']);
Route::post('/friend/data',           [DeviceController::class, 'receiveFriendData']);
