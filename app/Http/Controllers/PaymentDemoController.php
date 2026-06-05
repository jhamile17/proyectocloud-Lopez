<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentDemoController extends Controller
{
    public function redirect(Request $request)
    {
        // Muestra pantalla “Saliendo a PayU…”
        $payment = session('payment_demo');
        abort_if(!$payment, 404);

        // Para la demo simulamos que PayU devuelve OK
        // En un caso real, aquí harías POST a PayU y luego redirigirían a tu response.
        return view('payments.redirect', [
            'payment' => $payment,
            'sandbox_url' => 'https://sandbox.api.payulatam.com/' // solo informativo
        ]);
    }

    public function response(Request $request)
    {
        $payment = session('payment_demo');
        abort_if(!$payment, 404);

        $status = strtoupper($request->query('status', 'APPROVED')); // APPROVED, PENDING, DECLINED

        $cash = session('payment_demo.cash');

        return view('payments.response', [
            'payment' => $payment,
            'status' => $status,
            'cash'   => $cash, // contiene coupon y expires_at si es PagoEfectivo
        ]);
    }
}
