<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MercadoPagoWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // MP enviará POST con { action, data.id, type... }
        Log::info('[MP Webhook] payload', $request->all());

        // Si quieres consultar el pago/merchant_order:
        $type = $request->input('type');
        $id   = data_get($request->all(), 'data.id');

        if ($type === 'payment' && $id) {
            $token = env('MP_ACCESS_TOKEN');
            $resp  = Http::withToken($token)->get("https://api.mercadopago.com/v1/payments/{$id}");
            Log::info('[MP Webhook] payment detail', $resp->json());
            // Aquí puedes actualizar tu orden según el status del pago
        }

        return response()->json(['ok' => true]);
    }
}
