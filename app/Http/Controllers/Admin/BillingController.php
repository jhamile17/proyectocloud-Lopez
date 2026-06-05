<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;

class BillingController extends Controller
{
    public function index(Request $request)
{
    // Arrancar en blanco
    $lookup = [
        'type' => '', 'document' => '', 'name' => '', 'address' => '', 'raw' => null,
    ];
    if (session()->has('lookup')) {
        $lookup = session('lookup');
    }

    $paidStatuses = ['paid','shipped','completed','success'];

    // Detectar columnas
    $ordersTotalCol = Schema::hasColumn('orders', 'total_price')
        ? 'o.total_price'
        : (Schema::hasColumn('orders', 'total') ? 'o.total' : null);

    $oiUnitCol = Schema::hasColumn('order_items', 'unit_price')
        ? 'oi.unit_price'
        : (Schema::hasColumn('order_items', 'price') ? 'oi.price' : null);

    $productFk = Schema::hasColumn('order_items', 'product_id')
        ? 'product_id'
        : (Schema::hasColumn('order_items', 'products_id') ? 'products_id' : 'product_id');

    $prodPk = Schema::hasColumn('products', 'product_id') ? 'product_id' : 'id';

    // Agregaciones para ONLY_FULL_GROUP_BY
    $ordersTotalExprAgg = $ordersTotalCol ? "MAX($ordersTotalCol)" : "NULL";
    $oiUnitExpr         = $oiUnitCol ? $oiUnitCol : '0';

    $totalExpr = "
        COALESCE(
          NULLIF($ordersTotalExprAgg, 0),
          SUM( oi.quantity * COALESCE( NULLIF($oiUnitExpr, 0), p.price, 0 ) )
        ) AS total
    ";

    // 👇 Unimos con usuarios para traer el nombre
    $orders = DB::table('orders as o')
        ->leftJoin('order_items as oi', 'oi.order_id', '=', 'o.id')
        ->leftJoin('products as p', "p.$prodPk", '=', "oi.$productFk")
        ->leftJoin('users as u', 'u.id', '=', 'o.user_id')
        ->whereIn('o.status', $paidStatuses)
        ->groupBy('o.id', 'o.user_id', 'o.status', 'o.created_at', 'u.name')
        ->select([
            'o.id', 'o.user_id', 'o.status', 'o.created_at',
            DB::raw($totalExpr),
            DB::raw('COALESCE(u.name, "Cliente") as customer_name')
        ])
        ->orderByDesc('o.id')
        ->limit(300)
        ->get();

    return view('admin.billing.index', compact('lookup','orders'));
}

    
    public function lookup(Request $request)
    {
        // Normaliza nombres de campos (acepta ambos)
        $docType   = $request->input('doc_type', $request->input('type'));
        $docNumber = $request->input('doc_number', $request->input('document'));

        // Validación base
        $request->merge(['doc_type' => $docType, 'doc_number' => $docNumber]);
        $request->validate([
            'doc_type'   => 'required|in:dni,ruc,DNI,RUC',
            'doc_number' => 'required|numeric',
        ]);

        $type = strtoupper($docType);
        $doc  = preg_replace('/\D/', '', $docNumber);

        // Estructura de retorno/flash
        $lookup = [
            'type'     => $type,   // 'DNI' | 'RUC'
            'document' => $doc,
            'name'     => '',
            'address'  => '',
            'raw'      => null,
        ];

        // Reglas de longitud
        if ($type === 'DNI' && strlen($doc) !== 8) {
            return $this->lookupReturn($request, false, 'El DNI debe tener 8 dígitos.', $lookup, 422);
        }
        if ($type === 'RUC' && strlen($doc) !== 11) {
            return $this->lookupReturn($request, false, 'El RUC debe tener 11 dígitos.', $lookup, 422);
        }

        // Config de API
        $base  = rtrim(env('DOCAPI_BASE', ''), '/');
        $token = env('DOCAPI_TOKEN', '');

        if (!$base || !$token) {
            return $this->lookupReturn(
                $request,
                false,
                'Falta configurar DOCAPI_BASE o DOCAPI_TOKEN en el archivo .env',
                $lookup,
                500
            );
        }

        try {
            if ($type === 'DNI') {
                $resp = Http::timeout(10)->get("{$base}/dni/{$doc}", ['token' => $token]);
                if (!$resp->ok()) {
                    return $this->lookupReturn($request, false, 'Error API DNI: '.$resp->status(), $lookup, $resp->status());
                }

                $data = $resp->json();
                $lookup['raw'] = $data;

                // Ajusta las claves según tu proveedor real
                $ok = (bool)($data['success'] ?? $data['successfully'] ?? $data['ok'] ?? false);
                if ($ok) {
                    $nombres = trim($data['nombres'] ?? '');
                    $apPat   = trim($data['apellidoPaterno'] ?? $data['apellido_paterno'] ?? '');
                    $apMat   = trim($data['apellidoMaterno'] ?? $data['apellido_materno'] ?? '');
                    $lookup['name'] = trim(implode(' ', array_filter([$nombres, $apPat, $apMat])));

                    return $this->lookupReturn($request, true, 'Consulta DNI OK.', $lookup);
                }

                return $this->lookupReturn($request, false, 'La API no devolvió datos para este DNI.', $lookup, 404);
            }

            // RUC
            $resp = Http::timeout(10)->get("{$base}/ruc/{$doc}", ['token' => $token]);
            if (!$resp->ok()) {
                return $this->lookupReturn($request, false, 'Error API RUC: '.$resp->status(), $lookup, $resp->status());
            }

            $data = $resp->json();
            $lookup['raw'] = $data;

            $ok = (bool)($data['success'] ?? $data['successfully'] ?? $data['ok'] ?? false);
            if ($ok) {
                $lookup['name']    = trim($data['razonSocial'] ?? $data['razon_social'] ?? '');
                $lookup['address'] = trim($data['direccion'] ?? $data['domicilio_fiscal'] ?? '');
                return $this->lookupReturn($request, true, 'Consulta RUC OK.', $lookup);
            }

            return $this->lookupReturn($request, false, 'La API no devolvió datos para este RUC.', $lookup, 404);

        } catch (\Throwable $e) {
            return $this->lookupReturn($request, false, 'Error consultando API: '.$e->getMessage(), $lookup, 500);
        }
    }

    /** Helper: decide si devolver JSON (AJAX) o redireccionar con sesión */
    private function lookupReturn(Request $request, bool $ok, string $message, array $lookup, int $status = 200)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok'       => $ok,
                'message'  => $message,
                'type'     => $lookup['type'],
                'document' => $lookup['document'],
                'name'     => $lookup['name'],
                'address'  => $lookup['address'],
            ], $ok ? 200 : $status);
        }

        // Post-back normal a la vista
        $flashKey = $ok ? 'status' : 'warning';
        return back()->with($flashKey, $message)->with('lookup', $lookup);
    }

    /** Genera y muestra el PDF (si llega order_id, arma desde la orden pagada) */
    public function pdf(Request $request)
    {
        $request->validate([
            'doc_type'  => 'required|in:BOLETA,FACTURA',
            'order_id'  => 'nullable|integer',
            // (modo manual – por si en el futuro lo usas)
            'items'               => 'nullable|array',
            'items.*.description' => 'required_with:items|string|max:255',
            'items.*.qty'         => 'required_with:items|numeric|min:0.01',
            'items.*.unit_price'  => 'required_with:items|numeric|min:0',
            'customer_name'       => 'nullable|string|max:255',
            'customer_document'   => 'nullable|string|max:15',
            'customer_address'    => 'nullable|string|max:255',
        ]);

        $docType = $request->doc_type;
        $series  = $docType === 'FACTURA' ? 'F001' : 'B001';

        $calcItems = [];
        $op = $igv = $total = 0;

        if ($request->filled('order_id')) {
            // Cargar la orden (debe estar pagada)
            $paid = ['paid','shipped','completed','success'];
            $order = Order::where('id', $request->order_id)->whereIn('status', $paid)->first();

            if (!$order) {
                return back()->with('warning', 'La orden no existe o no está pagada.');
            }

            // Detectar columnas de items
            $unitPriceCol = Schema::hasColumn('order_items','unit_price') ? 'unit_price'
                          : (Schema::hasColumn('order_items','price') ? 'price' : null);
            $productFk    = Schema::hasColumn('order_items','product_id') ? 'product_id'
                          : (Schema::hasColumn('order_items','products_id') ? 'products_id' : 'product_id');
            $prodPk       = Schema::hasColumn('products','product_id') ? 'product_id' : 'id';

            $items = DB::table('order_items as oi')
                ->join('products as p', "p.$prodPk", '=', "oi.$productFk")
                ->where('oi.order_id', $order->id)
                ->select([
                    'p.name as product_name',
                    'p.price as product_price', // <- fallback
                    'oi.quantity',
                    $unitPriceCol ? DB::raw("oi.$unitPriceCol as unit_price") : DB::raw('0 as unit_price'),
                ])
                ->orderBy('oi.id')
                ->get();

            foreach ($items as $i => $it) {
                $q = (float) $it->quantity;
                // Si unit_price es 0 o null, usar p.price
                $p = (isset($it->unit_price) && $it->unit_price > 0) ? (float)$it->unit_price : (float)$it->product_price;

                $opg  = $q * $p;
                $tax  = round($opg * 0.18, 2);
                $line = round($opg + $tax, 2);
                $op  += $opg;
                $igv += $tax;
                $total += $line;

                $calcItems[] = [
                    'n'          => $i + 1,
                    'description'=> $it->product_name,
                    'qty'        => $q,
                    'unit_price' => $p,
                    'line_opg'   => round($opg,2),
                    'line_igv'   => $tax,
                    'line_total' => $line,
                ];
            }

            $customerName = $order->user->name ?? 'Cliente';
            $customerDoc  = '';
            $customerAddr = '';

        } else {
            // (Modo manual – opcional)
            $items = $request->input('items', []);
            foreach ($items as $i => $it) {
                $q = (float) $it['qty'];
                $p = (float) $it['unit_price'];
                $opg  = $q * $p;
                $tax  = round($opg * 0.18, 2);
                $line = round($opg + $tax, 2);
                $op  += $opg;
                $igv += $tax;
                $total += $line;

                $calcItems[] = [
                    'n'          => $i + 1,
                    'description'=> $it['description'],
                    'qty'        => $q,
                    'unit_price' => $p,
                    'line_opg'   => round($opg,2),
                    'line_igv'   => $tax,
                    'line_total' => $line,
                ];
            }

            $customerName = $request->customer_name ?? 'Cliente';
            $customerDoc  = $request->customer_document ?? '';
            $customerAddr = $request->customer_address  ?? '';
        }

        $data = [
            'doc_type'   => $docType,
            'series'     => $series,
            'number'     => 'PREVIEW',
            'issue_date' => now()->format('d/m/Y'),
            'customer'   => [
                'document' => $customerDoc,
                'name'     => $customerName,
                'address'  => $customerAddr,
            ],
            'items'      => $calcItems,
            'totals'     => [
                'op_gravadas' => round($op,2),
                'igv'         => round($igv,2),
                'total'       => round($total,2),
                'currency'    => 'PEN',
            ],
        ];

        try {
            $pdf = Pdf::loadView('admin.billing.pdf', $data)->setPaper('a4');
            while (ob_get_level() > 0) { ob_end_clean(); }
            return $pdf->stream(strtolower($data['doc_type']).'_'.now()->format('Ymd_His').'.pdf');
        } catch (\Throwable $e) {
            return response(
                "Error generando PDF: ".$e->getMessage(),
                500,
                ['Content-Type'=>'text/plain']
            );
        }
    }
}
