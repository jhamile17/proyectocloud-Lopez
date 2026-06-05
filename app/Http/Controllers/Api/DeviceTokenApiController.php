<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeviceToken;

class DeviceTokenApiController extends Controller
{
    /**
     * Registrar o actualizar token (CON o SIN login)
     */
    public function register(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'device_token' => 'required|string',
        ]);

        // Si no viene user_id, toma el ID del usuario autenticado, si hay
        $userId = $request->user_id ?? auth()->id();

        $device = DeviceToken::updateOrCreate(
            ['device_token' => $request->device_token],
            ['user_id' => $userId]
        );

        return response()->json([
            'success' => true,
            'message' => 'Token registrado correctamente',
            'data' => $device
        ]);
    }

    /**
     * Obtener tokens de un usuario
     */
    public function getTokensByUser($userId)
    {
        $tokens = DeviceToken::where('user_id', $userId)
            ->pluck('device_token');

        return response()->json([
            'success' => true,
            'tokens' => $tokens
        ]);
    }

    /**
     * Eliminar token
     */
    public function deleteToken(Request $request)
    {
        $request->validate([
            'device_token' => 'required|string',
        ]);

        $deleted = DeviceToken::where('device_token', $request->device_token)
            ->delete();

        return response()->json([
            'success' => $deleted > 0,
            'message' => $deleted > 0
                ? 'Token eliminado'
                : 'Token no encontrado'
        ]);
    }
}