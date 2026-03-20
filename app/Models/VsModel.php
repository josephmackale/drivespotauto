<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VsModel extends Model
{
    protected $table = 'vs_models';

    protected $fillable = [
        'make_id',
        'name',
        'slug',
        'is_active'
    ];

    public function make()
    {
        return $this->belongsTo(VsMake::class, 'make_id');
    }

    public function generations()
    {
        return $this->hasMany(VsGeneration::class, 'model_id');
    }
}