<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AttributeFamily extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'code',
        'notes',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(AttributeFamilyItem::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductAttribute::class,
            'attribute_family_items',
            'attribute_family_id',
            'product_attribute_id'
        )->withPivot([
            'sort_order',
            'is_required_override',
            'group_name',
        ])->withTimestamps();
    }
}