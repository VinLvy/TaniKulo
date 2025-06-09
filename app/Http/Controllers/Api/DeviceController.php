<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DeviceController extends Controller
{
    // IP & endpoint device (ESP) milikmu
    protected $deviceUrl = 'http://192.168.181.229/set';

    // IP & endpoint device teman
    protected $friendUrl = 'http://192.168.181.5/set';

    /**
     * Terima data sensor dari device (ESP).
     */
    public function receiveData(Request $request)
    {
        $data = $request->validate([
            'moisture' => 'required|numeric',
        ]);

        // contoh log atau simpan ke database
        \Log::info("Received from device: " . $data['moisture']);

        return response()->json([
            'status'   => 'ok',
            'received' => $data['moisture'],
        ]);
    }

    /**
     * Kirim kalibrasi ke device milikmu.
     * Expects JSON { adcDryUser, adcWetUser } in request body.
     */
    public function sendCalibration(Request $request)
    {
        $payload = $request->validate([
            'adcDryUser' => 'required|integer',
            'adcWetUser' => 'required|integer',
        ]);

        $response = Http::post($this->deviceUrl, $payload);

        if ($response->successful()) {
            return response()->json([
                'status'   => 'sent',
                'device'   => $this->deviceUrl,
                'response' => $response->json(),
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Failed to reach device',
            'code'    => $response->status(),
        ], 500);
    }

    /**
     * Terima data dari device teman.
     */
    public function receiveFriendData(Request $request)
    {
        $data = $request->validate([
            'moisture' => 'required|numeric',
        ]);

        \Log::info("Received from friend device: " . $data['moisture']);

        return response()->json([
            'status'   => 'ok',
            'received' => $data['moisture'],
        ]);
    }

    /**
     * Kirim kalibrasi ke device temanmu.
     */
    public function sendCalibrationToFriend(Request $request)
    {
        $payload = $request->validate([
            'adcDryUser' => 'required|integer',
            'adcWetUser' => 'required|integer',
        ]);

        // Tambahkan calibrationMode ke payload
        $payload['calibrationMode'] = 'user';

        $response = Http::post($this->friendUrl, $payload);

        if ($response->successful()) {
            return response()->json([
                'status'   => 'sent',
                'friend'   => $this->friendUrl,
                'response' => $response->json(),
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Failed to reach friend device',
            'code'    => $response->status(),
        ], 500);
    }
}
