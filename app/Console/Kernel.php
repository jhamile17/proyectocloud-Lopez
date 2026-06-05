<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * 🔹 Comandos Artisan personalizados
     */
    protected $commands = [
        \App\Console\Commands\DetectarStockBajo::class,
    ];

    /**
     * 🔹 Definición del scheduler
     */
    protected function schedule(Schedule $schedule)
    {
        // Ejecutar cada minuto el comando stock:check
        $schedule->command('stock:check')
                 ->everyMinute()                // Se ejecuta cada minuto
                 ->withoutOverlapping()         // Evita que se ejecuten múltiples instancias simultáneamente
                 ->appendOutputTo(storage_path('logs/stock_check.log')); // Guarda logs y no sobrescribe archivo
    }

    /**
     * 🔹 Registrar rutas de comandos si se requiere
     */
    protected function commands()
    {
        // Si necesitas definir comandos a través de routes/console.php
        // require base_path('routes/console.php');
    }
}