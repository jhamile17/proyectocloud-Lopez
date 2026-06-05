<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\AlertasStock;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Services\FirebaseService;

class ProductController extends Controller
{
    // Obtener todos los productos
    public function index()
    {
        $products = Product::select(
            'id', 'name', 'stock', 'stock_minimo', 'price', 'image'
        )->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'stock' => $p->stock,
                'stock_minimo' => $p->stock_minimo,
                'price' => $p->price,
                'image_url' => $p->image ? asset('storage/' . $p->image) : null,
            ];
        });

        return response()->json($products);
    }

    // Productos con stock bajo o agotado
    public function alertasActuales()
    {
        $alertas = Product::whereColumn('stock', '<=', 'stock_minimo')
            ->orWhere('stock', '<=', 0)
            ->select('id', 'name', 'stock', 'stock_minimo', 'price', 'image')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'stock' => $p->stock,
                    'stock_minimo' => $p->stock_minimo,
                    'price' => $p->price,
                    'image_url' => $p->image ? asset('storage/' . $p->image) : null,
                ];
            });

        return response()->json($alertas);
    }
   // Obtener un producto por ID
        public function show($id)
        {
            $product = Product::find($id);
        
            if (!$product) {
                return response()->json([
                    'message' => 'Producto no encontrado'
                ], 404);
            }
        
            return response()->json([
                'id' => $product->id,
                'name' => $product->name,
                'stock' => $product->stock,
                'stock_minimo' => $product->stock_minimo,
                'price' => $product->price,
                'image_url' => $product->image ? asset('storage/' . $product->image) : null,
            ]);
        }

    // 🔥 UPDATE STOCK + NOTIFICACIÓN EN TIEMPO REAL
    public function updateStock(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:products,id',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
        ]);

        $product = Product::find($request->id);

        if (!$product) {
            return response()->json([
                'message' => 'Producto no encontrado'
            ], 404);
        }

        /// ✅ ACTUALIZAR PRODUCTO
        $product->update([
            'stock' => $request->stock,
            'stock_minimo' => $request->stock_minimo,
        ]);

        /// 🔥 VALIDAR ALERTA
        if ($product->stock <= $product->stock_minimo) {

            $tipo = $product->stock <= 0 ? 'agotado' : 'bajo';

            $mensaje = $tipo === 'agotado'
                ? "Producto AGOTADO: {$product->name}"
                : "Stock bajo ({$product->stock}): {$product->name}";

            /// 💾 GUARDAR ALERTA (sin regla de 30 min)
            AlertasStock::create([
                'product_id' => $product->id,
                'stock_detectado' => $product->stock,
                'mensaje' => $mensaje,
                'fecha_alerta' => Carbon::now(),
            ]);

            /// 🔥 FIREBASE (TOPIC)
            try {
                $firebase = new FirebaseService();
                $accessToken = $firebase->getAccessToken();

                $url = "https://fcm.googleapis.com/v1/projects/my-project-de-entrega/messages:send";

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ])->post($url, [
                    "message" => [
                        "topic" => "alertas_stock",

                        "notification" => [
                            "title" => "Alerta de Inventario",
                            "body" => $mensaje
                        ],

                        "data" => [
                            "screen" => "alertas",
                            "producto_id" => (string)$product->id,
                            "tipo" => $tipo
                        ],

                        "android" => [
                            "priority" => "HIGH"
                        ]
                    ]
                ]);

                \Log::info("📤 Notificación enviada", [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

            } catch (\Exception $e) {
                \Log::error("❌ Error Firebase: " . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Stock actualizado correctamente',
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'stock' => $product->stock,
                'stock_minimo' => $product->stock_minimo,
                'price' => $product->price,
                'image_url' => $product->image ? asset('storage/' . $product->image) : null,
            ]
        ]);
    }
}