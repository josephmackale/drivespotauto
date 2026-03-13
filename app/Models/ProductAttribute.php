<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductAttribute extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'unit',
        'options',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function families(): BelongsToMany
    {
        return $this->belongsToMany(
            AttributeFamily::class,
            'attribute_family_items',
            'product_attribute_id',
            'attribute_family_id'
        )->withPivot([
            'sort_order',
            'is_required_override',
            'group_name',
        ])->withTimestamps();
    }
}