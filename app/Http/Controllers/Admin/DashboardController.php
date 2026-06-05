<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('es');

        // ---- Filtro por categoría (opcional)
        $categoryId = (int) $request->query('category_id', 0);

        // ---- Últimos 12 meses (inicio de mes)
        $now    = Carbon::now()->startOfMonth();
        $start  = (clone $now)->subMonths(11);
        $end    = (clone $now)->addMonth(); // exclusivo
        $months = collect(range(0, 11))->map(fn ($i) => (clone $start)->addMonths($i));

        $labels = $months
            ->map(fn ($m) => ucfirst($m->isoFormat('MMM')) . ' ' . $m->year)
            ->values()
            ->all();

        // ---- Campo total en orders
        $orderTotalField = Schema::hasColumn('orders', 'total_price')
            ? 'total_price'
            : (Schema::hasColumn('orders', 'total') ? 'total' : null);

        // ---- Estados válidos (pagados / completados)
        $paidStatuses = ['paid', 'shipped', 'completed', 'success'];

        // ========================
        // 1) INGRESOS POR MES
        // ========================
        if ($orderTotalField) {
            $sumQuery = DB::table('orders as o')
                ->selectRaw("DATE_FORMAT(o.created_at,'%Y-%m') as ym, SUM(o.{$orderTotalField}) as total")
                ->whereBetween('o.created_at', [$start, $end])
                ->whereIn('o.status', $paidStatuses);

            // Si hay filtro por categoría, unimos con items y products
            if ($categoryId > 0) {
                $sumQuery->join('order_items as oi', 'oi.order_id', '=', 'o.id')
                         ->join('products as p', 'p.id', '=', 'oi.product_id')
                         ->where('p.categories_id', $categoryId);
            }

            $rows = $sumQuery
                ->groupBy('ym')
                ->pluck('total', 'ym');

            // Asegurar 12 valores (0 si no hay ventas)
            $revenue = $months->map(function ($m) use ($rows) {
                $key = $m->format('Y-m');
                return round((float) ($rows[$key] ?? 0), 2);
            })->values()->all();
        } else {
            // Si no hay columna de total, llena con ceros
            $revenue = array_fill(0, 12, 0);
        }

        // ========================
        // 2) CARDS RESUMEN
        // ========================
        $stats = [
            'revenue'   => array_sum($revenue),
            'orders'    => (int) Order::count(),
            'products'  => (int) Product::count(),
            'customers' => (int) User::whereIn('role', ['customer', 'user'])->count(),
        ];

        // ========================
        // 3) CATEGORÍAS (chips) + lista para <select>
        // ========================
        $chips = Category::query()
            ->select('name')
            ->orderBy('name')
            ->limit(12)
            ->pluck('name')
            ->map(fn ($n) => ['i' => 'bi-dot', 't' => $n])
            ->values()
            ->all();

        // Detectar la PK real en categories (id, category_id, categories_id)
        $catPk = null;
        foreach (['id', 'category_id', 'categories_id'] as $cand) {
            if (Schema::hasColumn('categories', $cand)) { $catPk = $cand; break; }
        }

        // Lista de categorías para el select (id => name) sin romper si no hay PK estándar
        $categories = $catPk
            ? Category::select([$catPk.' as id', 'name'])->orderBy('name', 'asc')->get()
            : collect();

        // ========================
        // 4) PRODUCTOS MÁS VENDIDOS (Top 5)
        // ========================
        $hasSubtotal = Schema::hasColumn('order_items', 'subtotal');
        $hasPrice    = Schema::hasColumn('order_items', 'price');

        $best = DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->join('products as p', 'p.id', '=', 'oi.product_id')
            ->whereBetween('o.created_at', [$start, $end])
            ->whereIn('o.status', $paidStatuses)
            ->when($categoryId > 0, fn($q) => $q->where('p.categories_id', $categoryId))
            ->select([
                'p.id',
                'p.name',
                'p.image',
                DB::raw('SUM(oi.quantity) as qty_sold'),
                DB::raw(
                    $hasSubtotal
                        ? 'SUM(oi.subtotal) as amount'
                        : ($hasPrice ? 'SUM(oi.quantity * oi.price) as amount' : '0 as amount')
                ),
            ])
            ->groupBy('p.id', 'p.name', 'p.image')
            ->orderByDesc('qty_sold')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                // Normalizar imagen (URL absoluta o storage público)
                $img = $row->image;
                if ($img && !Str::startsWith($img, ['http://', 'https://', '//'])) {
                    $img = Storage::disk('public')->exists($img)
                        ? Storage::url($img)
                        : asset('images/no-image.png');
                }
                if (!$img) $img = 'https://via.placeholder.com/56';

                return [
                    'id'     => $row->id,
                    'name'   => $row->name,
                    'orders' => (int) $row->qty_sold,
                    'total'  => round((float) $row->amount, 2),
                    'img'    => $img,
                ];
            })
            ->toArray();

        // ========================
        // 5) Render
        // ========================
        return view('dashboard', [
            'labels'     => $labels,
            'revenue'    => $revenue,
            'stats'      => $stats,
            'chips'      => $chips,
            'best'       => $best,
            'categories' => $categories,
            'categoryId' => $categoryId,
        ]);
    }
}
