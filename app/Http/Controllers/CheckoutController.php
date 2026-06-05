<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        // Carrito desde sesión (no se borra al entrar a checkout)
        $cart  = $request->session()->get('cart', []);
        $items = Arr::get($cart, 'items', []);
        if (!is_array($items)) $items = [];
        if ($this->isAssoc($items)) $items = array_values($items);

        // Totales
        $subtotal = 0.0;
        foreach ($items as $it) {
            $qty   = (float) data_get($it, 'qty', 1);
            $price = (float) data_get($it, 'price', 0);
            $subtotal += $qty * $price;
        }
        $subtotal = round($subtotal, 2);

        $igvRate = 0.18;
        $igv     = round($subtotal * $igvRate, 2);
        $total   = round($subtotal + $igv, 2);

        return view('checkout.index', [
            'items'    => $items,
            'subtotal' => $subtotal,
            'igv'      => $igv,
            'total'    => $total,
            'igvRate'  => $igvRate,
        ]);
    }

    public function process(Request $request)
    {
        // OJO: en tu Blade el campo es "payment_method"
        $validated = $request->validate([
            'name'            => ['required','string','max:120'],
            'phone'           => ['nullable','string','max:30'],
            'dni'             => ['nullable','string','max:20'],
            'address'         => ['required','string','max:200'],
            'method' => ['required','in:mercadopago,efectivo,transferencia'],
        ]);

        // Recalcular totales desde el carrito de sesión
        $cart  = $request->session()->get('cart', []);
        $items = Arr::get($cart, 'items', []);
        if (!is_array($items)) $items = [];
        if ($this->isAssoc($items)) $items = array_values($items);

        $subtotal = 0.0;
        foreach ($items as $it) {
            $qty   = (float) data_get($it, 'qty', 1);
            $price = (float) data_get($it, 'price', 0);
            $subtotal += $qty * $price;
        }
        $subtotal = round($subtotal, 2);
        $igv      = round($subtotal * 0.18, 2);
        $total    = round($subtotal + $igv, 2);

        // La tabla orders (en tu BD) NO tiene columnas subtotal/tax: solo total_price y status.
        // Además, shipping_address_id es NOT NULL → creamos primero el registro en shipping_addresses.

        if (!Schema::hasTable('shipping_addresses')) {
            return back()->withErrors('No existe la tabla shipping_addresses. No se puede continuar.')->withInput();
        }
        if (!Schema::hasTable('orders')) {
            return back()->withErrors('No existe la tabla orders. No se puede continuar.')->withInput();
        }

        $orderId = null;

        DB::beginTransaction();
        try {
            // 1) Crear/guardar dirección de envío y obtener su ID
            // Usamos solo columnas seguras/estándar (user_id, address, phone, created_at, updated_at)
            $addressId = DB::table('shipping_addresses')->insertGetId([
            'user_id'    => auth()->id(),
            'address'    => $validated['address'],
            'created_at' => now(),
            'updated_at' => now(),
             ]);
            // 2) Crear orden PENDING con el addressId
            $orderId = DB::table('orders')->insertGetId([
                'user_id'             => auth()->id(),
                'shipping_address_id' => $addressId,     // ← ya no es NULL
                'status'              => 'pending',      // enum: pending|paid|shipped|cancelled
                'total_price'         => $total,         // tu tabla usa total_price
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);

            // 3) Guardar items (si existe la tabla)
            if (Schema::hasTable('order_items')) {
                foreach ($items as $it) {
                $qty   = (int)  data_get($it, 'qty', 1);
                $price = (float) data_get($it, 'price', 0);
                $lineSubtotal = round($qty * $price, 2);
            
                DB::table('order_items')->insert([
                    'order_id'   => $orderId,
                    'product_id' => data_get($it, 'id'),
                    'quantity'   => $qty,
                    'unit_price' => $price,
                    'subtotal'   => $lineSubtotal,   // ← NECESARIO
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('No se pudo registrar la orden: '.$e->getMessage())->withInput();
        }

        // No limpiamos carrito todavía: esperamos confirmación de la pasarela.
        // Redirección según método de pago.
        $method = $validated['method'];

        if ($method === 'mercadopago') {
            // Llevar al flujo de MP (ahí crearás la preferencia con el $orderId y $total)
            return redirect()
                ->route('mp.checkout')
                ->with('ok', 'Orden #'.$orderId.' creada. Continúa con el pago en Mercado Pago.');
        }

        // Métodos offline: solo marcamos pendiente y mostramos instrucciones
        return redirect()
            ->route('checkout')
            ->with('ok', 'Orden #'.$orderId.' creada. Método: '.$method.'. Te contactaremos para coordinar el pago.');
    }

    private function isAssoc(array $arr): bool
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
