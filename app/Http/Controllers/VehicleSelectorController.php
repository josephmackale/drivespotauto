<?php

namespace App\Http\Controllers;

use App\Models\VsGeneration;
use App\Models\VsMake;
use App\Models\VsModel;
use App\Models\VsVehicleVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleSelectorController extends Controller
{
    public function makes(): JsonResponse
    {
        $makes = VsMake::query()
            ->where('is_active', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($makes);
    }

    public function models(Request $request): JsonResponse
    {
        $makeId = (int) $request->query('make_id');

        if (! $makeId) {
            return response()->json([]);
        }

        $models = VsModel::query()
            ->where('make_id', $makeId)
            ->where('is_active', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($models);
    }

    public function generations(Request $request): JsonResponse
    {
        $modelId = (int) $request->query('model_id');

        if (! $modelId) {
            return response()->json([]);
        }

        $generations = VsGeneration::query()
            ->where('model_id', $modelId)
            ->where('is_active', 1)
            ->orderBy('year_from')
            ->get([
                'id',
                'name',
                'code',
                'year_from',
                'year_to',
            ])
            ->map(function ($row) {
                $yearTo = ((int) $row->year_to === 2099 || empty($row->year_to))
                    ? '...'
                    : $row->year_to;

                $years = $row->year_from
                    ? $row->year_from . ' - ' . $yearTo
                    : '';

                $label = $row->name ?: 'Unknown Generation';

                if (! empty($years)) {
                    $label .= ' (' . $years . ')';
                }

                return [
                    'id' => $row->id,
                    'label' => $label,
                ];
            })
            ->values();

        return response()->json($generations);
    }

    public function engines(Request $request): JsonResponse
    {
        $generationId = (int) $request->query('generation_id');

        if (! $generationId) {
            return response()->json([]);
        }

        $variants = VsVehicleVariant::query()
            ->where('generation_id', $generationId)
            ->where('is_active', 1)
            ->orderByRaw("COALESCE(fuel_type, 'Other')")
            ->orderBy('variant_name')
            ->orderBy('year_from')
            ->orderBy('engine_code')
            ->get([
                'id',
                'variant_name',
                'type_code',
                'engine_code',
                'fuel_type',
                'capacity_l',
                'power_hp',
                'year_from',
                'year_to',
                'drivetrain',
                'key_canonical',
            ]);

        $grouped = $variants
            ->groupBy(function ($variant) {
                return filled($variant->fuel_type) ? $variant->fuel_type : 'Other';
            })
            ->map(function ($items, $fuelType) {
                return [
                    'fuel_type' => $fuelType,
                    'options' => $items->map(function ($variant) {
                        $parts = [];

                        if (filled($variant->capacity_l)) {
                            $parts[] = $variant->capacity_l . 'L';
                        }

                        if (filled($variant->variant_name)) {
                            $parts[] = $variant->variant_name;
                        }

                        if (filled($variant->engine_code)) {
                            $parts[] = $variant->engine_code;
                        }

                        $yearTo = ((int) $variant->year_to === 2099 || empty($variant->year_to))
                            ? '...'
                            : $variant->year_to;

                        if (filled($variant->year_from)) {
                            $parts[] = '(' . $variant->year_from . ' - ' . $yearTo . ')';
                        }

                        if (filled($variant->drivetrain)) {
                            $parts[] = $variant->drivetrain;
                        }

                        return [
                            'id' => $variant->id,
                            'label' => implode(' ', $parts),
                            'vehicle_key' => $variant->key_canonical,
                        ];
                    })->values(),
                ];
            })
            ->values();

        return response()->json($grouped);
    }
}