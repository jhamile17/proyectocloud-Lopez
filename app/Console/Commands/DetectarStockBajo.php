<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Services\AlertasStockService;

class DetectarStockBajo extends Command
{
    protected $signature = 'stock:check';
    protected $description = 'Detecta productos con stock bajo o agotado y envía alertas';

    public function handle()
    {
        $this->info('🚀 Iniciando verificación de stock...');
        \Log::info("🟢 Cron ejecutado correctamente");

        $service = new AlertasStockService();

        // Revisar todos los productos
        $service->revisarTodos();

        $this->info('✅ Revisión completada.');
    }
}