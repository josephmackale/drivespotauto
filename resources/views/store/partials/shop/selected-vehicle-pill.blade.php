@if(!empty($selectedVehicle))
    <div class="mb-6">
        <div class="flex flex-col gap-3 rounded-2xl border border-blue-200 bg-blue-50 px-4 py-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">
                    Selected vehicle
                </p>

                <p class="mt-1 text-sm font-medium text-blue-900">
                    {{ $selectedVehicle }}
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <form method="POST" action="{{ route('shop.vehicle.clear') }}">
                    @csrf

                    <button
                        type="submit"
                        class="inline-flex items-center rounded-xl border border-blue-300 bg-white px-4 py-2 text-sm font-medium text-blue-800 hover:bg-blue-100 transition"
                    >
                        Clear vehicle
                    </button>
                </form>
            </div>
        </div>
    </div>
@endif