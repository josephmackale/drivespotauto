<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VsVehicleVariant extends Model
{
    protected $table = 'vs_vehicle_variants';

    protected $fillable = [
        'generation_id',
        'engine_id',
        'variant_name',
        'type_code',
        'tecdoc_type_no',
        'engine_code',
        'fuel_type',
        'drivetrain',
        'body_type',
        'power_kw',
        'power_hp',
        'capacity_cc',
        'capacity_l',
        'year_from',
        'year_to',
        'vehicle_label',
        'key_canonical',
        'is_active',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $variant) {
            $variant->vehicle_label = $variant->buildVehicleLabel();
            $variant->key_canonical = $variant->buildCanonicalKey();
        });
    }

    public function generation()
    {
        return $this->belongsTo(VsGeneration::class, 'generation_id');
    }

    public function engine()
    {
        return $this->belongsTo(VsEngine::class, 'engine_id');
    }

    public function getComputedVehicleLabelAttribute(): string
    {
        return $this->buildVehicleLabel();
    }

    public function getComputedCanonicalKeyAttribute(): string
    {
        return $this->buildCanonicalKey();
    }

    public function buildVehicleLabel(): string
    {
        $make = $this->generation?->model?->make?->name;
        $model = $this->generation?->model?->name;
        $generationCode = $this->generation?->code;

        $modelPart = $model
            ? trim($model . ($generationCode ? " ({$generationCode})" : ''))
            : null;

        $capacity = $this->capacity_l ? rtrim(rtrim((string) $this->capacity_l, '0'), '.') : null;

        $years = null;
        if ($this->year_from || $this->year_to) {
            $from = $this->year_from ?: '';
            $to = $this->year_to ?: '';
            $years = trim($from . '-' . $to, '-');
        }

        return collect([
            $make,
            $modelPart,
            $this->variant_name,
            $this->drivetrain, // only shown if explicitly stored
            $capacity,
            $this->engine_code,
            $years,
            $this->fuel_type ? "({$this->fuel_type})" : null,
        ])->filter(fn ($value) => filled($value))->implode(' ');
    }

    public function buildCanonicalKey(): string
    {
        $make = $this->generation?->model?->make?->name;
        $model = $this->generation?->model?->name;
        $generationCode = $this->generation?->code;

        $parts = [
            $make,
            $model,
            $generationCode,
            $this->variant_name,
            $this->type_code,
            $this->engine_code,
            $this->year_from,
            $this->year_to,
        ];

        if (filled($this->drivetrain)) {
            $parts[] = $this->drivetrain;
        }

        $normalized = collect($parts)
            ->filter(fn ($value) => filled($value))
            ->map(function ($value) {
                $value = strtoupper((string) $value);
                $value = preg_replace('/[^A-Z0-9]+/', '-', $value);
                return trim($value, '-');
            })
            ->filter()
            ->implode('-');

        return Str::lower($normalized);
    }
}