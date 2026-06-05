<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlertasStock extends Model
{
    protected $table = 'alertas_stock';
    protected $primaryKey = 'id_alertas';

    // ❌ No usamos created_at ni updated_at
    public $timestamps = false;

    // 🔓 Campos permitidos
    protected $fillable = [
        'product_id',
        'stock_detectado',
        'mensaje',
        'fecha_alerta',
    ];

    // 🔥 IMPORTANTE: manejo correcto de fechas
    protected $casts = [
        'fecha_alerta' => 'datetime',
    ];

    /**
     * 🔗 Relación con producto
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}