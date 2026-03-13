<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttributeFamilyItem extends Model
{
    protected $fillable = [
        'attribute_family_id',
        'product_attribute_id',
        'sort_order',
        'is_required_override',
        'group_name',
    ];

    protected $casts = [
        'is_required_override' => 'boolean',
    ];

    public function family(): BelongsTo
    {
        return $this->belongsTo(AttributeFamily::class, 'attribute_family_id');
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id');
    }
}