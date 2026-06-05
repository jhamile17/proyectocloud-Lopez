<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'Order_items_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'orders_id','products_id','quantity','price','subtotal'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function order(){
        return $this->belongsTo(Order::class, 'orders_id', 'Orders_id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'products_id', 'products_id');
    }
}
