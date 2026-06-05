<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AlertController;
use App\Http\Controllers\Api\NotificacionController;
use App\Http\Controllers\Api\DeviceTokenApiController;

// ────────────── RUTAS PÚBLICAS ──────────────

// 🔐 Login
Route::post('/login', [AuthController::class, 'login']);

// 📦 PRODUCTOS
Route::prefix('products')->group(function () {

    // 🔥 ALERTAS (IMPORTANTE: VA PRIMERO)
    Route::get('/alertas', [ProductController::class, 'alertasActuales']);

    // 📋 Listado de productos
    Route::get('/', [ProductController::class, 'index']);

    // 🔍 Detalle de producto (SOLO NÚMEROS)
    Route::get('/{id}', [ProductController::class, 'show'])
        ->where('id', '[0-9]+');

    // 🔄 Actualizar stock
    Route::post('/update-stock', [ProductController::class, 'updateStock']);
});

// 🔔 Notificaciones manuales
Route::post('/notificaciones/enviar', [NotificacionController::class, 'enviar']);


// ────────────── RUTAS PROTEGIDAS (auth:sanctum) ──────────────

Route::middleware('auth:sanctum')->group(function () {

    // 📊 Historial de alertas
    Route::get('/alertas/historial', [AlertController::class, 'index']);

});


// ────────────── DEVICE TOKENS (FLUTTER) ──────────────

Route::prefix('device')->group(function () {

    // 📱 Registrar token
    Route::post('/register', [DeviceTokenApiController::class, 'register']);

    // 👤 Obtener tokens por usuario
    Route::get('/user/{userId}', [DeviceTokenApiController::class, 'getTokensByUser']);

    // ❌ Eliminar token
    Route::delete('/delete', [DeviceTokenApiController::class, 'deleteToken']);
});