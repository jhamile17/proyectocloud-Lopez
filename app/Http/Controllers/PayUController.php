<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class PayUController extends Controller
{
    public function showForm()
    {
        return view('payments.checkout');
    }

    public function redirectToPayU(Request $request)
    {
        // Datos de prueba / demo
        $orderId        = 'ORDER_' . time();
        $description    = 'Compra PROCAFES Demo';
        $amount         = number_format((float)$request->input('amount', 10.00), 2, '.', '');
        $currency       = Config::get('services.payu.currency', 'PEN');

        $merchantId     = Config::get('services.payu.merchant_id');
        $accountId      = Config::get('services.payu.account_id');
        $apiKey         = Config::get('services.payu.api_key');
        $responseUrl    = Config::get('services.payu.response');
        $confirmationUrl= Config::get('services.payu.confirmation');

        // Referencia única de pedido
        $referenceCode  = $orderId;

        // Firmas para WebCheckout (regla oficial):
        // signature = md5( API_KEY~merchantId~referenceCode~amount~currency )
        $signatureStr   = implode('~', [$apiKey, $merchantId, $referenceCode, $amount, $currency]);
        $signature      = md5($signatureStr);

        // URL sandbox WebCheckout
        $action = 'https://sandbox.checkout.payulatam.com/ppp-web-gateway-payu/';

        // Pasamos todos los campos obligatorios al form auto-submit
        return view('payments.redirect', compact(
            'action',
            'merchantId',
            'accountId',
            'description',
            'referenceCode',
            'amount',
            'currency',
            'signature',
            'responseUrl',
            'confirmationUrl'
        ));
    }

    // Notificación servidor-a-servidor (PayU → tu backend)
    public function confirmation(Request $request)
    {
        // Aquí validas firma y actualizas el estado del pedido en BD
        // Campos típicos: merchant_id, reference_sale, value, currency, state_pol, sign
        // IMPORTANTE: recalcula firma y compara.

        // Para demo, solo registramos:
        \Log::info('PayU CONFIRMATION', $request->all());

        return response('OK', 200);
    }

    // Retorno del usuario (front)
    public function response(Request $request)
    {
        // PayU te devuelve query params con el resultado
        // Muestra un mensaje al cliente usando state_pol / response_code_pol, etc.
        return view('payments.response', ['data' => $request->all()]);
    }
}
