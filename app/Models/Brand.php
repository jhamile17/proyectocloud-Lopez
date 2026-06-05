<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'brands';

    // PK real en tu tabla
    protected $primaryKey = 'brand_id';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['name', 'description'];

    // Fuerza a que el route key sea la PK real (por si acaso)
    public function getRouteKeyName()
    {
        return $this->getKeyName(); // devuelve 'brand_id'
    }
}
