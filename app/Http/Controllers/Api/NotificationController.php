<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        // Jika ingin mengambil semua notifikasi tanpa filter device_id

        // return response()->json([
        //     'status' => 'success',
        //     'notifications' => Notification::latest()->get()
        // ]);

        $query = Notification::latest();

        if ($request->has('device_id')) {
            $query->where('device_id', $request->device_id);
        }

        return response()->json([
            'status' => 'success',
            'notifications' => $query->get(),
        ]);
    }

    public function send(Request $request)
    {
        // Jika ingin mengirim notifikasi tanpa relasi device

        // $request->validate([
        //     'title' => 'required|string',
        //     'message' => 'required|string',
        // ]);

        // // Simpan jika perlu
        // $notification = Notification::create([
        //     'title' => $request->title,
        //     'message' => $request->message,
        // ]);

        // // Kirim notifikasi JSON ke Flutter (langsung respons)
        // return response()->json([
        //     'status' => 'sent',
        //     'notification' => $notification,
        // ]);

        $request->validate([
            'device_id' => 'required|exists:devices,id', // Validasi relasi ke devices
            'title' => 'required|string',
            'message' => 'required|string',
        ]);

        $notification = Notification::create([
            'device_id' => $request->device_id,
            'title' => $request->title,
            'message' => $request->message,
        ]);

        return response()->json([
            'status' => 'sent',
            'notification' => $notification,
        ]);
    }
}
