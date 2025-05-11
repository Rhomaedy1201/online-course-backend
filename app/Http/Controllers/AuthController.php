<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->with('role')->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        $tokenResult = $user->createToken('auth_token', ['*']);
        $tokenResult->accessToken->expires_at = now()->addMinutes(30);
        $tokenResult->accessToken->save();

        $token = $tokenResult->plainTextToken;

        Log::info("User Login", ["user" => $user->email]);
        Log::info('Now: ' . now());

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();

        Log::info("User Logout", ['user' => $request->user()?->email]);

        return response()->json([
            'message' => 'Logout berhasil',
        ]);
    }


}
