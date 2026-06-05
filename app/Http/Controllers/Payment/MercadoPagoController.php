<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MercadoPagoController extends Controller
{
    /**
     * Página “Pagar con Mercado Pago”.
     * Usa SIEMPRE precio final (con IGV) guardado en el carrito.
     */
    public function checkout(Request $request)
    {
        $cart  = $request->session()->get('cart', []);
        $items = $cart['items'] ?? [];

        // Totales DESDE precio final (con IGV)
        $baseTotal = 0.0;   // base imponible (sin IGV)
        $igvTotal  = 0.0;   // IGV
        $grossTotal= 0.0;   // total con IGV

        foreach ($items as $it) {
            $qty       = (float)($it['qty']   ?? 1);
            $unitGross = (float)($it['price'] ?? 0);      // precio final con IGV
            $lineGross = round($unitGross * $qty, 2);

            $lineBase  = round($lineGross / 1.18, 2);     // base = final / 1.18
            $lineIgv   = round($lineGross - $lineBase, 2);

            $baseTotal  += $lineBase;
            $igvTotal   += $lineIgv;
            $grossTotal += $lineGross;
        }

        // La vista que ya tienes espera estas llaves:
        return view('payments.mercadopago', [
            'cart'     => $items,
            'subtotal' => round($baseTotal, 2), // base imponible
            'igv'      => round($igvTotal,  2),
            'total'    => round($grossTotal,2), // total final
        ]);
    }

    /**
     * Crea la preferencia y redirige al Checkout de Mercado Pago.
     * - Si hay carrito en sesión: arma ítems reales con unit_price = precio final (con IGV).
     * - Si no hay carrito: crea un ítem con el total recibido.
     */
    public function createPreference(Request $request)
    {
        $validated = $request->validate([
            'order_id' => ['nullable','integer'],
            'total'    => ['nullable','numeric'], // usado solo si no hay carrito
        ]);

        $accessToken = env('MP_ACCESS_TOKEN');
        if (empty($accessToken)) {
            return back()->withErrors('Falta MP_ACCESS_TOKEN en tu archivo .env');
        }

        // 1) Construir ítems desde carrito (si existe)
        $cart      = $request->session()->get('cart', []);
        $cartItems = $cart['items'] ?? [];
        $items     = [];

        if (is_array($cartItems) && count($cartItems) > 0) {
            foreach ($cartItems as $it) {
                $title = (string)($it['name'] ?? 'Producto');
                $qty   = (int)   ($it['qty']  ?? 1);

                // unit_price = PRECIO FINAL (con IGV). Sanitizamos por si viene S/ o comas.
                $rawPrice = (string)($it['price'] ?? '0');
                $numPrice = (float)str_replace(',', '.', preg_replace('/[^\d.,]/', '', $rawPrice));

                if ($qty < 1)        $qty = 1;
                if ($numPrice <= 0)  $numPrice = 0.01; // MP no acepta 0

                $items[] = [
                    'title'       => $title,
                    'quantity'    => $qty,
                    'unit_price'  => round($numPrice, 2), // NUMÉRICO (final con IGV)
                    'currency_id' => 'PEN',
                ];
            }
        }

        // 2) Si no hay carrito, crear un ítem con el total recibido
        if (empty($items)) {
            $total = (float)($validated['total'] ?? 0);
            if ($total <= 0) {
                return back()->withErrors('No hay items en el carrito ni total válido para crear la preferencia.');
            }

            $orderId = $validated['order_id'] ?? null;

            $items[] = [
                'title'       => 'Compra PROCAFES' . ($orderId ? (" #{$orderId}") : ''),
                'quantity'    => 1,
                'unit_price'  => round($total, 2), // TOTAL FINAL (con IGV)
                'currency_id' => 'PEN',
            ];
        }

        // Back URLs (usa valores por defecto si no están en .env)
        $success = env('MP_SUCCESS_URL', route('home'));
        $failure = env('MP_FAILURE_URL', route('home'));
        $pending = env('MP_PENDING_URL', route('home'));

        $payload = [
            'items'        => $items,
            'back_urls'    => [
                'success' => $success,
                'failure' => $failure,
                'pending' => $pending,
            ],
            'auto_return'  => 'approved',
            'notification_url' => url('/webhooks/mercadopago'),
        ];

        // Llamada a la API de preferencias
        $response = Http::withToken($accessToken)
            ->post('https://api.mercadopago.com/checkout/preferences', $payload);

        if ($response->failed()) {
            return back()->withErrors('Error API Mercado Pago: ' . $response->body());
        }

        $data = $response->json();
        $redirectUrl = $data['init_point'] ?? ($data['sandbox_init_point'] ?? null);

        if (!$redirectUrl) {
            return back()->withErrors('Respuesta inválida de Mercado Pago: ' . json_encode($data));
        }

        return redirect()->away($redirectUrl);
    }

    /** Éxito de pago */
    public function success(Request $request)
    {
        return view('payments.status', [
            'status' => 'success',
            'data'   => $request->all(),
        ]);
    }

    /** Pago pendiente */
    public function pending(Request $request)
    {
        return view('payments.status', [
            'status' => 'pending',
            'data'   => $request->all(),
        ]);
    }

    /** Pago fallido */
    public function failure(Request $request)
    {
        return view('payments.status', [
            'status' => 'failure',
            'data'   => $request->all(),
        ]);
    }
}
