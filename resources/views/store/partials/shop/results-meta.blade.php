@php
    $hasVehicle = isset($vehicle) && $vehicle;
    $selectedCategorySafe = $selectedCategory ?? null;
    $selectedVehicleSafe = $selectedVehicle ?? null;
    $isSubcategory = $selectedCategorySafe && !empty($selectedCategorySafe->parent_id);

    if ($hasVehicle && $selectedCategorySafe && $isSubcategory) {
        $contextLabel = $selectedCategorySafe->name . ' for ' . $selectedVehicleSafe;
    } elseif ($hasVehicle && $selectedCategorySafe) {
        $contextLabel = $selectedCategorySafe->name . ' for ' . $selectedVehicleSafe;
    } elseif ($hasVehicle) {
        $contextLabel = 'Parts for ' . $selectedVehicleSafe;
    } elseif ($selectedCategorySafe && $isSubcategory) {
        $contextLabel = $selectedCategorySafe->name;
    } elseif ($selectedCategorySafe) {
        $contextLabel = $selectedCategorySafe->name;
    } else {
        $contextLabel = 'All parts';
    }

    $firstItem = $products->firstItem() ?? 0;
    $lastItem = $products->lastItem() ?? 0;
    $totalItems = method_exists($products, 'total') ? $products->total() : $products->count();
@endphp

<div class="mb-4 flex flex-col gap-2 border-b border-gray-200 pb-3 md:flex-row md:items-center md:justify-between">

    <div class="min-w-0">
        <div class="truncate text-sm font-medium text-gray-900">
            {{ $contextLabel }}
        </div>

        <div class="mt-0.5 text-xs text-gray-600">
            Showing {{ $firstItem }} to {{ $lastItem }} of {{ number_format($totalItems) }} results
        </div>
    </div>

    <form method="GET" action="{{ url()->current() }}" class="shrink-0">
        @foreach(request()->except('sort', 'page') as $key => $value)
            @if(is_array($value))
                @foreach($value as $nestedValue)
                    <input type="hidden" name="{{ $key }}[]" value="{{ $nestedValue }}">
                @endforeach
            @else
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
        @endforeach

        <select
            name="sort"
            onchange="this.form.submit()"
            class="h-9 rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-700"
        >
            <option value="">Latest</option>
            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>
                Price: Low → High
            </option>
            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>
                Price: High → Low
            </option>
            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>
                Name A → Z
            </option>
            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>
                Name Z → A
            </option>
        </select>
    </form>

</div>