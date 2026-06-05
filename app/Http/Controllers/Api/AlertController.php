<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AlertasStock;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AlertController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            // Traer todas las alertas con su producto relacionado, ordenadas por fecha más reciente
            $alertas = AlertasStock::with('product')
                ->orderByDesc('fecha_alerta')
                ->get();

            $result = [];
            foreach ($alertas as $a) {
                try {
                    $result[] = [
                        'id' => $a->id_alertas,
                        'product_id' => $a->product_id,
                        'name' => $a->product?->name ?? 'PRODUCTO NO EXISTE',
                        'stock_detectado' => $a->stock_detectado ?? 0,
                        // Fecha formateada con zona horaria correcta
                        'fecha' => $a->fecha_alerta
                            ? Carbon::parse($a->fecha_alerta)
                                ->setTimezone('America/Lima')
                                ->format('Y-m-d H:i:s')
                            : '',
                        'mensaje' => $a->mensaje ?? '',
                        'image_url' => $a->product?->image 
                            ? asset('storage/' . $a->product->image) 
                            : null,
                    ];
                } catch (\Exception $e) {
                    Log::error("Error procesando alerta ID {$a->id_alertas}: " . $e->getMessage());
                }
            }

            return response()->json($result, 200);

        } catch (\Exception $e) {
            Log::error("Error obteniendo alertas: " . $e->getMessage());
            return response()->json([
                'error' => 'Error al obtener historial',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}