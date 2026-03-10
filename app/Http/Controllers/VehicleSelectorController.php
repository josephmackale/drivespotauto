<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleSelectorController extends Controller
{
    public function makes()
    {
        $makes = DB::table('vs_makes')
            ->select('id', 'name')
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();

        return response()->json($makes);
    }

    public function generations(Request $request)
    {
        $makeId = (int) $request->query('make_id');

        if ($makeId <= 0) {
            return response()->json([]);
        }

        $generations = DB::table('vs_generations as g')
            ->join('vs_models as m', 'm.id', '=', 'g.model_id')
            ->select(
                'g.id',
                'm.name as model_name',
                'g.generation_code',
                'g.year_from',
                'g.year_to'
            )
            ->where('g.make_id', $makeId)
            ->where('g.is_active', 1)
            ->orderBy('m.name')
            ->orderBy('g.year_from')
            ->get()
            ->map(function ($row) {
                $generationCode = $row->generation_code ?: '-';
                $yearTo = $row->year_to ?: 'Present';

                return [
                    'id'    => $row->id,
                    'label' => "{$row->model_name} ({$generationCode}) {$row->year_from} - {$yearTo}",
                ];
            })
            ->values();

        return response()->json($generations);
    }

    public function engines(Request $request)
    {
        $generationId = (int) $request->query('generation_id');

        if ($generationId <= 0) {
            return response()->json([]);
        }

        $engines = DB::table('vs_vehicle_engines')
            ->select(
                'id',
                'fuel_type',
                'variant_name',
                'variant_code',
                'capacity_l',
                'power_kw',
                'power_hp',
                'engine_code_raw',
                'year_from',
                'year_to',
                'drivetrain'
            )
            ->where('generation_id', $generationId)
            ->where('is_active', 1)
            ->orderBy('fuel_type')
            ->orderBy('variant_name')
            ->orderBy('year_from')
            ->get()
            ->map(function ($row) {
                $variantCode = $row->variant_code ?: '-';
                $capacity = $row->capacity_l ?: '?';
                $powerHp = $row->power_hp ?: '?';
                $yearTo = $row->year_to ?: 'Present';

                return [
                    'id'        => $row->id,
                    'fuel_type' => $row->fuel_type ?: 'Unknown',
                    'label'     => "{$row->variant_name} ({$variantCode}) — {$capacity}L — {$powerHp} hp",
                    'years'     => "{$row->year_from} - {$yearTo}",
                ];
            })
            ->values();

        return response()->json($engines);
    }
}