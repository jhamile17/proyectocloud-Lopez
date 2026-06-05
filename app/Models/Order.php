<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** Tabla y PK reales */
    protected $table      = 'orders';
    protected $primaryKey = 'id';
    public $incrementing  = true;
    protected $keyType    = 'int';

    /** Asignación masiva */
    protected $fillable = [
        'user_id',
        'shipping_address_id',
        'total_price',
        'status',
    ];

    /** Casts */
    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    /* ================= Relaciones ================= */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class, 'shipping_address_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'id');
    }

    /* ============== Helpers / Accessors ============== */

    /** Mapa de estados → etiqueta en español */
    public static function statusMap(): array
    {
        return [
            'paid'        => 'Pagado',
            'shipped'     => 'Enviado',
            'completed'   => 'Completado',
            'success'     => 'Completado',
            'processing'  => 'Procesando',
            'pending'     => 'Pendiente',
            'cancelled'   => 'Cancelado',
            'canceled'    => 'Cancelado',
            'failed'      => 'Fallido',
        ];
    }

    /** $order->status_label */
    public function getStatusLabelAttribute(): string
    {
        $map = self::statusMap();
        return $map[$this->status] ?? ucfirst($this->status ?? 'desconocido');
    }
}
