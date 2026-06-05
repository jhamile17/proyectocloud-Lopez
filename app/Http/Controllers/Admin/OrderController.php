<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /** Listado con búsqueda y filtro por estado */
    public function index(Request $request)
    {
        $q      = trim((string) $request->get('q', ''));
        $status = $request->get('status');

        $orders = DB::table('orders as o')
            ->join('users as u', 'u.id', '=', 'o.user_id')
            ->select(
                'o.id',
                'o.status',
                'o.total_price',
                'o.created_at',
                'u.name  as customer_name',
                'u.email as customer_email'
            )
            ->when($q !== '', function ($qry) use ($q) {
                $like = "%{$q}%";
                $qry->where(function ($w) use ($like) {
                    $w->where('u.name', 'like', $like)
                      ->orWhere('u.email', 'like', $like)
                      ->orWhere('o.id', 'like', $like);
                });
            })
            ->when($status, fn ($qry) => $qry->where('o.status', $status))
            ->orderByDesc('o.created_at')
            ->paginate(12)
            ->withQueryString();

        // Estados disponibles para el combo de filtro
        $statuses = DB::table('orders')->select('status')->distinct()->pluck('status')->filter()->values();

        // Mapa de traducción para mostrar en la vista
        $statusMap = Order::statusMap();

        return view('admin.orders.index', compact('orders', 'statuses', 'status', 'q', 'statusMap'));
    }

    /** Detalle de una orden + items con P. Unit y Subtotal */
    public function show($id)
    {
        $order = DB::table('orders as o')
            ->leftJoin('users as u', 'u.id', '=', 'o.user_id')
            ->select('o.*', 'u.name as customer_name', 'u.email as customer_email')
            ->where('o.id', $id)
            ->first();

        if (!$order) {
            return redirect()->route('admin.orders.index')
                ->with('warning', 'La orden no existe.');
        }

        $items = DB::table('order_items as oi')
            ->join('products as p', 'p.id', '=', 'oi.product_id')
            ->select([
                'oi.id',
                'p.name as product_name',
                'oi.quantity',
                DB::raw('oi.unit_price as price'), 
                'oi.subtotal'
            ])
            ->where('oi.order_id', $id)
            ->orderBy('oi.id')
            ->get();

        $totals = [
            'items_subtotal' => (float) $items->sum('subtotal'),
            'order_total'    => (float) ($order->total_price ?? 0),
        ];

        $statusMap   = Order::statusMap();
        $statusLabel = $statusMap[$order->status] ?? ucfirst($order->status ?? 'desconocido');

        return view('admin.orders.show', compact('order', 'items', 'totals', 'statusLabel'));
    }
    public function updateStatus(Request $request, \App\Models\Order $order)
    {
        // Estados permitidos en BD
        $allowed = ['pending', 'paid', 'cancelled'];
    
        $request->validate([
            'status' => 'required|in:'.implode(',', $allowed),
        ]);
    
        $new = $request->input('status');
    
        // Evita trabajo innecesario
        if ($order->status === $new) {
            return back()->with('status', 'El estado ya estaba en "'.$new.'".');
        }
    
        // Transiciones simples (ajústalas si usas más estados)
        $order->status = $new;
    
        // Si quieres guardar la fecha de pago:
        if ($new === 'paid' && !isset($order->paid_at) && \Schema::hasColumn('orders', 'paid_at')) {
            $order->paid_at = now();
        }
    
        $order->save();
    
        return back()->with('status', 'Estado de la orden #'.$order->id.' actualizado a "'.$new.'".');
    }

}
