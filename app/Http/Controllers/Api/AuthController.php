<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email','password'))) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        $user = Auth::user();

        // Solo admin usa app móvil
        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $token = $user->createToken('mobile-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }
}
