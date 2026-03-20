@php
    $hasVehicle = isset($vehicle) && $vehicle;
    $selectedCategorySafe = $selectedCategory ?? null;
    $selectedVehicleSafe = $selectedVehicle ?? null;
@endphp

<div>
    <div class="mx-auto max-w-2xl px-6 py-12 text-center md:px-10 md:py-14">

        <div class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-full bg-amber-50 border border-amber-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75h4.5v4.5h-4.5z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.75 12 3.75l8.25 6v9a1.5 1.5 0 0 1-1.5 1.5h-13.5a1.5 1.5 0 0 1-1.5-1.5v-9Z" />
            </svg>
        </div>

        <h3 class="text-2xl font-semibold tracking-tight text-gray-900">
            @if($hasVehicle && $selectedCategorySafe)
                No {{ $selectedCategorySafe->name }} found yet
            @elseif($hasVehicle)
                No compatible parts found yet
            @else
                No products available right now
            @endif
        </h3>

        <p class="mx-auto mt-3 max-w-xl text-base leading-7 text-gray-600">
            @if($hasVehicle && $selectedCategorySafe)
                We’re currently adding <span class="font-semibold text-gray-900">{{ $selectedCategorySafe->name }}</span> for
                <span class="font-semibold text-gray-900">{{ $selectedVehicleSafe }}</span>.
            @elseif($hasVehicle)
                We’re currently expanding our catalogue for
                <span class="font-semibold text-gray-900">{{ $selectedVehicleSafe }}</span>.
            @else
                We’re updating our catalogue. Check back soon or contact us and we’ll help you find the right part.
            @endif
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
            @endif
        </div>
    </div>
</div>