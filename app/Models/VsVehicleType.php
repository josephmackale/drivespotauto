<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VsVehicleType extends Model
{
    protected $table = 'vs_vehicle_types';

    protected $fillable = [
        'name',
        'slug'
    ];
}