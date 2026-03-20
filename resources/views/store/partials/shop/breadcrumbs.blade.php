@php
    $hasVehicle = isset($vehicle) && $vehicle;
    $selectedCategorySafe = $selectedCategory ?? null;
    $selectedParentCategorySafe = $selectedParentCategory ?? null;

    $make = $hasVehicle ? $vehicle->generation?->model?->make : null;
    $model = $hasVehicle ? $vehicle->generation?->model : null;
    $engine = $hasVehicle ? $vehicle->engine_code : null;

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
@endphp

<nav class="text-sm text-gray-600 flex flex-wrap items-center gap-1">
    <a href="{{ route('home') }}" class="hover:text-black">Home</a>
    <span>|</span>

    <a href="{{ route('shop') }}" class="hover:text-black">Shop</a>

    @if($hasVehicle)
        <span>|</span>
        <a href="{{ route('shop.vehicle', $vehicle->key_canonical) }}" class="hover:text-black">
            {{ $make?->name }} {{ $model?->name }} {{ $engine }}
        </a>
    @endif

    @if($parentCategory)
        <span>|</span>
        <a href="{{ route('shop.vehicle.category', [$vehicle->key_canonical, $parentCategory->slug]) }}"
           class="hover:text-black">
            {{ $parentCategory->name }}
        </a>
    @endif

    @if($selectedCategorySafe)
        <span>|</span>
        <span class="text-gray-900 font-semibold">
            {{ $selectedCategorySafe->name }}
        </span>
    @endif
</nav>