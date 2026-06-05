<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\FirebaseService;

class NotificacionController extends Controller
{
    public function enviar(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        $firebase = new FirebaseService();
        $accessToken = $firebase->getAccessToken();

        $url = "https://fcm.googleapis.com/v1/projects/my-project-de-entrega/messages:send";

        $mensaje = "🟡 Stock bajo detectado";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->post($url, [
            "message" => [
                "token" => $request->token,

                /// 🔔 VISUAL
                "notification" => [
                    "title" => "Alerta de Inventario",
                    "body" => $mensaje
                ],

                /// 🔥 CONTROL EN APP
                "data" => [
                    "tipo" => "stock",
                    "nivel" => "bajo",
                    "title" => "Alerta de Inventario",
                    "body" => $mensaje
                ],

                /// 🚀 CLAVE PARA BACKGROUND
                "android" => [
                    "priority" => "HIGH",
                     "notification" => [
                        "sound" => "default",
                        "channel_id" => "high_importance_channel"
                    ]
                ],
            ]
        ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Error enviando notificación',
                'error' => $response->body()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'firebase_response' => $response->json()
        ]);
    }
}