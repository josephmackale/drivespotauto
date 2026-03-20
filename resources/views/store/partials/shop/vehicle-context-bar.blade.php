@php
    $hasVehicle = isset($vehicle) && $vehicle;

    $make = $hasVehicle ? $vehicle->generation?->model?->make?->name : null;
    $model = $hasVehicle ? $vehicle->generation?->model?->name : null;
    $generation = $hasVehicle ? $vehicle->generation?->name : null;
    $engine = $hasVehicle ? $vehicle->engine_code : null;

    $yearFrom = $hasVehicle ? $vehicle->year_from : null;
    $yearTo = $hasVehicle
        ? (($vehicle->year_to ?? null) == 9999 ? 'Present' : $vehicle->year_to)
        : null;

    $label = $selectedVehicle ?? trim(implode(' ', array_filter([
        $make,
        $model,
        $generation ? "({$generation})" : null,
        $engine,
        $yearFrom ? "({$yearFrom} - {$yearTo})" : null,
    ])));
@endphp

@if($hasVehicle)
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-4 md:p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        {{-- LEFT: Vehicle Info --}}
        <div class="flex items-start md:items-center gap-3">
            <div class="h-10 w-10 flex items-center justify-center rounded-full bg-blue-100 text-blue-600">
                🚗
            </div>

            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">
                    Selected Vehicle
                </p>

                <p class="text-sm md:text-base font-semibold text-gray-900 leading-tight">
                    {{ $label }}
                </p>
            </div>
        </div>

        {{-- RIGHT: Actions --}}
        <div class="flex items-center gap-3">

            {{-- Change Vehicle --}}
            <button
                type="button"
                onclick="window.dispatchEvent(new CustomEvent('open-vehicle-selector'))"
                class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition"
            >
                Change Vehicle
            </button>

            {{-- Clear Vehicle --}}
            <form action="{{ route('shop.vehicle.clear') }}" method="POST" class="inline">
                @csrf
                <button
                    type="submit"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-red-600 text-white hover:bg-red-700 transition"
                >
                    Clear
                </button>
            </form>

        </div>
    </div>
@endif