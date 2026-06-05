<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'Payments_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'order_id','payment_method','amount','transaction_id','Transaction_json','status'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function order(){
        return $this->belongsTo(Order::class, 'order_id', 'Orders_id');
    }
}
