@php
    $hasVehicle = isset($vehicle) && $vehicle;
    $selectedCategorySafe = $selectedCategory ?? null;
    $selectedParentCategorySafe = $selectedParentCategory ?? null;
    $selectedVehicleSafe = $selectedVehicle ?? null;

    $make = $hasVehicle ? $vehicle->generation?->model?->make : null;
    $model = $hasVehicle ? $vehicle->generation?->model : null;

    $yearToDisplay = $hasVehicle
        ? (($vehicle->year_to ?? null) == 9999 ? 'Present' : $vehicle->year_to)
        : null;

    $isSubcategory = $selectedCategorySafe && !empty($selectedCategorySafe->parent_id);

    $parentCategory = null;
    if ($selectedCategorySafe) {
        if ($selectedParentCategorySafe) {
            $parentCategory = $selectedParentCategorySafe;
        } elseif ($isSubcategory) {
            $parentCategory = $selectedCategorySafe->parent;
        } else {
            $parentCategory = $selectedCategorySafe;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Hero content
    |--------------------------------------------------------------------------
    */
    $heroTitle = 'Shop';
    $heroSubtitle = 'Browse our auto parts catalog and find the right replacement parts for your vehicle.';

    if ($hasVehicle && $selectedCategorySafe) {
        $heroTitle = $selectedCategorySafe->name;
        $heroSubtitle = $selectedVehicleSafe ?: trim(implode(' ', array_filter([
            $make?->name ?? null,
            $model?->name ?? null,
            $vehicle->engine_code ?? null,
        ])));
    } elseif ($hasVehicle) {
        $heroTitle = 'Compatible Parts';
        $heroSubtitle = $selectedVehicleSafe ?: trim(implode(' ', array_filter([
            $make?->name ?? null,
            $model?->name ?? null,
            $vehicle->engine_code ?? null,
            !empty($vehicle->year_from) ? '(' . $vehicle->year_from . ' - ' . $yearToDisplay . ')' : null,
        ])));
    }
@endphp

@if($hasVehicle)
    <section class="relative w-full bg-slate-950">
        <div class="absolute inset-0">
            <img
                src="{{ asset('images/shop/hero-shocks.jpg') }}"
                alt="{{ $heroTitle }}"
                class="h-full w-full object-cover"
            >
        </div>

        <div class="absolute inset-0 bg-slate-950/65"></div>

        <div class="relative z-10">
            <div class="max-w-7xl mx-auto px-4 py-14 md:py-20 lg:py-24">
                <div class="max-w-4xl mx-auto text-center">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight">
                        {{ $heroTitle }}
                    </h1>

                    @if($heroSubtitle)
                        <p class="mt-4 text-sm md:text-lg font-medium text-white/90">
                            {{ $heroSubtitle }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </section>
@else
    <section class="relative w-full bg-slate-950">
        <div class="absolute inset-0">
            <img
                src="{{ asset('images/shop/hero-shocks.jpg') }}"
                alt="Shop"
                class="h-full w-full object-cover"
            >
        </div>

        <div class="absolute inset-0 bg-slate-950/70"></div>

        <div class="relative z-10">
            <div class="max-w-7xl mx-auto px-4 pt-3 pb-4 md:pt-4 md:pb-5">
                <div class="max-w-4xl mx-auto text-center">
                    <p class="mt-4 text-sm md:text-lg font-medium text-white/90">
                        Select your vehicle to find compatible parts quickly.
                    </p>
                </div>

                <div class="mt-6 w-full relative z-20">
                    @include('store.partials.shop.vehicle-selector-horizontal')
                </div>
            </div>
        </div>
    </section>
@endif