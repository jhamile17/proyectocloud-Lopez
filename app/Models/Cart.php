<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';
    protected $primaryKey = 'Cart_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['user_id','products_id','quantity','price','sub_total'];

    protected $casts = [
        'price' => 'decimal:2',
        'sub_total' => 'decimal:2',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'User_id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'products_id', 'products_id');
    }
}
