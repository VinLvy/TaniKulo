<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        // Arahkan ke Google login
        return Socialite::driver('google')
            ->stateless()
            ->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(uniqid()),
                ]
            );

            $token = $user->createToken('flutter_app')->plainTextToken;

            // return redirect()->away("yourflutterapp://auth-success?token={$token}"); // Flutter

            return response()->json([
                'access_token' => $token,
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            // return response()->json(['error' => 'Unauthorized'], 401); // Flutter
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
