@php
    $hasVehicle = isset($vehicle) && $vehicle;
    $selectedCategorySafe = $selectedCategory ?? null;
    $selectedVehicleSafe = $selectedVehicle ?? null;

    $isSubcategory = $selectedCategorySafe && !empty($selectedCategorySafe->parent_id);

    if ($hasVehicle && $selectedCategorySafe && $isSubcategory) {
        $emptyTitle = 'No compatible ' . $selectedCategorySafe->name . ' found yet';
        $emptyMessage = 'We’re currently adding ' . $selectedCategorySafe->name . ' compatible with ' . $selectedVehicleSafe . '.';
    } elseif ($hasVehicle && $selectedCategorySafe) {
        $emptyTitle = 'No compatible ' . $selectedCategorySafe->name . ' found yet';
        $emptyMessage = 'We’re currently expanding our ' . $selectedCategorySafe->name . ' catalogue for ' . $selectedVehicleSafe . '.';
    } elseif ($hasVehicle) {
        $emptyTitle = 'No compatible parts found yet';
        $emptyMessage = 'We’re currently expanding our catalogue for ' . $selectedVehicleSafe . '.';
    } elseif ($selectedCategorySafe && $isSubcategory) {
        $emptyTitle = 'No ' . $selectedCategorySafe->name . ' available right now';
        $emptyMessage = 'We’re updating this subcategory. Select your vehicle for a more accurate parts match or contact us for help.';
    } elseif ($selectedCategorySafe) {
        $emptyTitle = 'No ' . $selectedCategorySafe->name . ' available right now';
        $emptyMessage = 'We’re updating this category. Select your vehicle for a more accurate parts match or contact us for help.';
    } else {
        $emptyTitle = 'No products available right now';
        $emptyMessage = 'We’re updating our catalogue. Check back soon or contact us and we’ll help you find the right part.';
    }
@endphp

<div>
    <div class="mx-auto max-w-2xl px-6 py-12 text-center md:px-10 md:py-14">

        <div class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-full border border-amber-100 bg-amber-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75h4.5v4.5h-4.5z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.75 12 3.75l8.25 6v9a1.5 1.5 0 0 1-1.5 1.5h-13.5a1.5 1.5 0 0 1-1.5-1.5v-9Z" />
            </svg>
        </div>

        <h3 class="text-2xl font-semibold tracking-tight text-gray-900">
            {{ $emptyTitle }}
        </h3>

        <p class="mx-auto mt-3 max-w-xl text-base leading-7 text-gray-600">
            {{ $emptyMessage }}
        </p>

        <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
            <a href="https://wa.me/254792163144"
               class="inline-flex min-w-[170px] items-center justify-center rounded-xl bg-green-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700">
                WhatsApp Us
            </a>

            @if($hasVehicle)
                <form method="POST" action="{{ route('shop.vehicle.clear') }}">
                    @csrf
                    <button
                        type="submit"
                        class="inline-flex min-w-[170px] items-center justify-center rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                        Change Vehicle
                    </button>
                </form>
            @else
                <a href="{{ route('shop') }}"
                   class="inline-flex min-w-[170px] items-center justify-center rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    Browse Shop
                </a>
            @endif
        </div>
    </div>
</div>