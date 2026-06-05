<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    protected $table = 'shipping_addresses';
    protected $primaryKey = 'shipping_addresses_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false; // tu tabla no lista timestamps

    protected $fillable = [
        'user_id','address','city','state','zip_code','country'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'User_id');
    }
}
