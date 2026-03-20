<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OeReference extends Model
{
    protected $table = 'oe_references';

    protected $fillable = [
        'brand_name_raw',
        'brand_name_normalized',
        'reference_number_raw',
        'reference_number_normalized',
        'reference_type',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $oeReference) {
            $oeReference->brand_name_raw = trim((string) $oeReference->brand_name_raw);
            $oeReference->reference_number_raw = trim((string) $oeReference->reference_number_raw);

            $oeReference->brand_name_normalized = static::normalizeBrand($oeReference->brand_name_raw);
            $oeReference->reference_number_normalized = static::normalizeReference($oeReference->reference_number_raw);

            if (blank($oeReference->reference_type)) {
                $oeReference->reference_type = 'OE';
            }
        });
    }

    public static function normalizeBrand(?string $value): string
    {
        return Str::of((string) $value)
            ->upper()
            ->ascii()
            ->replaceMatches('/[^A-Z0-9]+/', ' ')
            ->trim()
            ->value();
    }

    public static function normalizeReference(?string $value): string
    {
        return Str::of((string) $value)
            ->upper()
            ->ascii()
            ->replaceMatches('/[^A-Z0-9]+/', '')
            ->trim()
            ->value();
    }

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'product_oe_reference',
            'oe_reference_id',
            'product_id'
        );
    }
}