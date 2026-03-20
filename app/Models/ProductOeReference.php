<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOeReference extends Model
{
    protected $table = 'product_oe_references';

    protected $fillable = [
        'product_id',
        'brand_name_raw',
        'brand_name_normalized',
        'reference_number_raw',
        'reference_number_normalized',
        'reference_type',
        'sort_order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}