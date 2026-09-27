<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * POST /api/register
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:kasir,operator,admin',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
        ]);

        return response()->json([
            'message' => 'Registrasi berhasil',
            'user'    => $user,
        ], 201);
    }

    /**
     * POST /api/login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        // Buat token sederhana dan simpan ke kolom api_token
        $token = Str::random(60);
        $user->forceFill(['api_token' => hash('sha256', $token)])->save();

        return response()->json([
            'message'    => 'Login berhasil',
            'user'       => $user,
            'token'      => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * POST /api/logout
     * Membutuhkan header: Authorization: Bearer {token}
     */
    public function logout(Request $request)
    {
        $request->user()->forceFill(['api_token' => null])->save();

        return response()->json([
            'message' => 'Logout berhasil',
        ]);
    }
}
