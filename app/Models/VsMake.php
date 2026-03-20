<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VsMake extends Model
{
    protected $table = 'vs_makes';

    protected $fillable = [
        'name',
        'slug',
        'country',
        'is_active'
    ];

    public function models()
    {
        return $this->hasMany(VsModel::class, 'make_id');
    }
}