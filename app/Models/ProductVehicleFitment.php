<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVehicleFitment extends Model
{
    protected $table = 'product_vehicle_fitments';

    protected $fillable = [
        'product_id',
        'engine_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function engine()
    {
        return $this->belongsTo(VsEngine::class, 'engine_id');
    }
}