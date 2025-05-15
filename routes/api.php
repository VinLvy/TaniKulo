<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TestController;

Route::get('/tes', TestController::class);

Route::get('/cek', function () {
    return response()->json(['cek' => 'berhasil']);
});
