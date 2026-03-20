<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VsGeneration extends Model
{
    protected $table = 'vs_generations';

    protected $fillable = [
        'model_id',
        'name',
        'code',
        'body_type',
        'year_from',
        'year_to',
        'is_active'
    ];

    public function model()
    {
        return $this->belongsTo(VsModel::class, 'model_id');
    }

    public function engines()
    {
        return $this->hasMany(VsEngine::class, 'generation_id');
    }
}