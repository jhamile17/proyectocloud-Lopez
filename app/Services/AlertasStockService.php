<?php

namespace App\Services;

use App\Models\AlertasStock;
use App\Models\DeviceToken;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class AlertasStockService
{
    /**
     * Revisar stock de un producto y generar alerta + notificación
     */
    public function revisarStock($product)
    {
        if ($product->stock <= $product->stock_minimo) {

            $tipo = $product->stock == 0 ? 'agotado' : 'bajo';

            $mensaje = $tipo === 'agotado'
                ? "Producto AGOTADO: {$product->name}"
                : "Stock bajo ({$product->stock}): {$product->name}";

            // 🔁 Evitar spam: 30 minutos
            $ultimaAlerta = AlertasStock::where('product_id', $product->id)
                ->latest('fecha_alerta')
                ->first();

            $crearAlerta = false;

            if (!$ultimaAlerta) {
                $crearAlerta = true;
            } else {
                $diffMin = Carbon::parse($ultimaAlerta->fecha_alerta)
                    ->diffInMinutes(now());

                if ($diffMin >= 30) {
                    $crearAlerta = true;
                }
            }

            if ($crearAlerta) {

                /// 📌 Guardar alerta en DB
                AlertasStock::create([
                    'product_id' => $product->id,
                    'stock_detectado' => $product->stock,
                    'mensaje' => $mensaje,
                    'fecha_alerta' => now(),
                ]);

                \Log::info("🆕 Alerta registrada: {$mensaje}");

                /// 📲 Enviar a todos los dispositivos
                $tokens = DeviceToken::orderBy('created_at', 'desc')
                    ->pluck('device_token');

                foreach ($tokens as $token) {
                    try {
                        $this->enviarNotificacion(
                            $token,
                            $mensaje,
                            $tipo,
                            $product->id,
                            $product->name,
                            $product->stock,
                            $product->image
                        );
                    } catch (\Exception $e) {
                        \Log::error("❌ Error enviando notificación: " . $e->getMessage());
                    }
                }
            }
        }
    }

    /**
     * 🔥 ENVÍO FCM CON NOTIFICACIÓN VISUAL
     */
   private function enviarNotificacion($token, $mensaje, $tipo, $productoId, $productoName, $stock, $imagen = null)
{
    $firebase = new \App\Services\FirebaseService();
    $accessToken = $firebase->getAccessToken();

    $url = "https://fcm.googleapis.com/v1/projects/my-project-de-entrega/messages:send";

    $payload = [
        "message" => [
            "token" => $token,

            // 🔥 DATA (para Flutter)
            "data" => [
                "tipo" => "producto",
                "nivel" => $tipo,
                "producto_id" => (string)$productoId,
                "producto" => $productoName,
                "stock" => (string)$stock,
                "title" => "🚨 Alerta de Inventario",
                "body" => $mensaje,
                "image" => $imagen ? asset('storage/' . $imagen) : ""
            ],

            // 🔔 NOTIFICACIÓN (clave para background y app cerrada)
            "notification" => [
                "title" => "🚨 Alerta de Inventario",
                "body" => $mensaje,
                "image" => $imagen ? asset('storage/' . $imagen) : null,
            ],

            "android" => [
                "priority" => "HIGH",
                "notification" => [
                    "channel_id" => "high_importance_channel",
                    "icon" => "ic_launcher",
                    "color" => "#FF0000",
                    "sound" => "default",
                    "click_action" => "FLUTTER_NOTIFICATION_CLICK"
                ]
            ]
        ]
    ];

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $accessToken,
        'Content-Type' => 'application/json',
    ])->post($url, $payload);

    \Log::info("📤 Notificación enviada a {$token} | Código: " . $response->status());
}
}