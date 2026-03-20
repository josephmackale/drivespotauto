<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VsEngine extends Model
{
    protected $table = 'vs_engines';

    protected $fillable = [
        'generation_id',
        'variant_name',
        'engine_code',
        'engine_family',
        'capacity_cc',
        'capacity_l',
        'fuel_type',
        'power_hp',
        'drivetrain',
        'year_from',
        'year_to',
        'canonical_key'
    ];

    public function generation()
    {
        return $this->belongsTo(VsGeneration::class, 'generation_id');
    }

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'product_vehicle_fitments',
            'engine_id',
            'product_id'
        )->withTimestamps();
    }
}