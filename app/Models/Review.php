<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'reviews_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false; // no definiste timestamps

    protected $fillable = ['user_id','products_id','rating','comment'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'User_id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'products_id', 'products_id');
    }
}
