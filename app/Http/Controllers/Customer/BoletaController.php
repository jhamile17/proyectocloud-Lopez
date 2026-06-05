<?php

namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
 
class BoletaController extends Controller
{
    public function download(Request $request, $order)
    {
        $user = $request->user();

        $orderRow = DB::table('orders')
            ->where('id', $order)
            ->where('user_id', $user->id)
            ->whereIn('status', ['paid','shipped','completed','success']) // 🔥 SOLO PAGADOS
            ->first();

        if (!$orderRow) {
            abort(403, 'No tienes permiso o la orden no está pagada.');
        }

        $items = DB::table('order_items')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->select(
                'order_items.*',
                'products.name as product_name',
                'products.price as product_price'
            )
            ->where('order_items.order_id', $orderRow->id)
            ->get();

        $total = 0;
        foreach ($items as $it) {
            $qty   = $it->quantity ?? $it->qty ?? 1;
            $price = $it->price ?? $it->unit_price ?? $it->product_price ?? 0;
            $total += ($price * $qty);
        }

        $orderTotal = $orderRow->total ?? $orderRow->total_price ?? $orderRow->amount ?? null;
        if (is_numeric($orderTotal) && floatval($orderTotal) > 0) {
            $total = floatval($orderTotal);
        }

        $pdf = Pdf::loadView('customer.boleta', [
    'user' => $user,
    'order' => $orderRow,
    'items' => $items,
    'total' => $total,
])->setPaper('a4');


        return $pdf->download('BOLETA-PROCAFES-ORDER-'.$orderRow->id.'.pdf');
    }
}
