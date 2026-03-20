@extends('layouts.store')

@section('title')
    @php
        $makeName = $vehicle->generation?->model?->make?->name;
        $modelName = $vehicle->generation?->model?->name;
        $engineCode = $vehicle->engine_code;
        $categoryName = $selectedCategory?->name;
    @endphp

    @if($selectedCategory)
        {{ $makeName }} {{ $modelName }} {{ $engineCode }} {{ $categoryName }} | DriveSpot Auto
    @else
        {{ $makeName }} {{ $modelName }} {{ $engineCode }} Parts | DriveSpot Auto
    @endif
@endsection

@section('content')

<div class="container mx-auto px-4 py-6">
    @php
        $make = $vehicle->generation?->model?->make;
        $model = $vehicle->generation?->model;
        $engine = $vehicle->engine_code;
        $yearToDisplay = $vehicle->year_to == 9999 ? 'Present' : $vehicle->year_to;
    @endphp

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-600 mb-4">
        <a href="{{ route('home') }}">Home</a> |
        <a href="{{ route('shop') }}">Shop</a> |
        <a href="{{ route('shop.vehicle', $vehicle->key_canonical) }}">
            {{ $make?->name }} {{ $model?->name }} {{ $engine }}
        </a>

        @if($selectedCategory)
            | <span class="font-semibold">{{ $selectedCategory->name }}</span>
        @endif
    </nav>

    {{-- Vehicle Title --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold">
            @if($selectedCategory)
                {{ $vehicle->generation->model->make->name }}
                {{ $vehicle->generation->model->name }}
                {{ $vehicle->engine_code }}
                {{ $selectedCategory->name }}
            @else
                {{ $vehicle->generation->model->make->name }}
                {{ $vehicle->generation->model->name }}
                {{ $vehicle->engine_code }} Parts
            @endif
        </h1>

        <p class="text-gray-600 mt-2">
            Browse compatible replacement parts for
            {{ $vehicle->generation->model->make->name }}
            {{ $vehicle->generation->model->name }}
            {{ $vehicle->engine_code }}
            ({{ $vehicle->year_from }} – {{ $yearToDisplay }}).
            @if($selectedCategory)
                Category: {{ $selectedCategory->name }}.
            @endif
        </p>
    </div>

    {{-- Vehicle Info Box --}}
    <div class="bg-gray-100 rounded p-4 mb-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">

            <div>
                <strong>Make</strong><br>
                {{ $vehicle->generation->model->make->name }}
            </div>

            <div>
                <strong>Model</strong><br>
                {{ $vehicle->generation->model->name }}
            </div>

            <div>
                <strong>Engine</strong><br>
                {{ $vehicle->engine_code }}
            </div>

            <div>
                <strong>Fuel</strong><br>
                {{ $vehicle->fuel_type }}
            </div>

            <div>
                <strong>Capacity</strong><br>
                {{ $vehicle->capacity_l }}L
            </div>

            <div>
                <strong>Years</strong><br>
                {{ $vehicle->year_from }} – {{ $yearToDisplay }}
            </div>

            @if($vehicle->drivetrain)
                <div>
                    <strong>Drivetrain</strong><br>
                    {{ $vehicle->drivetrain }}
                </div>
            @endif

        </div>
    </div>

    {{-- Category Navigation --}}
    @if(isset($categories) && $categories->count())
        @php
            $groupedCategories = $categories->groupBy('parent_id');
            $parentCategories = $groupedCategories->get(null, collect())->values();

            $expandedParentId = request('expand');

            if (!$expandedParentId && isset($selectedCategory) && $selectedCategory) {
                $expandedParentId = $selectedCategory->parent_id ?: $selectedCategory->id;
            }

            // Match lg:grid-cols-6
            $chunkedParents = $parentCategories->chunk(6);
        @endphp

        <section
            class="bg-gray-50 py-10 rounded-xl mb-10"
            x-data="{ openCategory: '{{ $expandedParentId ?? '' }}' }"
        >
            <div class="max-w-7xl mx-auto px-4">
                <h2 class="text-3xl font-semibold text-center mb-10">
                    Shop by Category
                </h2>

                <div class="mb-6 text-center">
                    <a href="{{ route('shop.vehicle', $vehicle->key_canonical) }}"
                    class="inline-flex items-center px-4 py-2 rounded-lg border text-sm transition {{ !isset($selectedCategory) || !$selectedCategory ? 'bg-black text-white border-black' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
                        All Parts
                    </a>
                </div>

                @foreach($chunkedParents as $row)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6 mb-6">
                        @foreach($row as $parent)
                            @php
                                $children = $groupedCategories->get($parent->id, collect());
                                $hasChildren = $children->count() > 0;

                                $isParentSelected = $selectedCategory
                                    && $selectedCategory->id === $parent->id;

                                $isParentBranchActive = $selectedCategory
                                    && ($selectedCategory->id === $parent->id || $selectedCategory->parent_id === $parent->id);
                            @endphp

                            <div>
                                @if($hasChildren)
                                    <button
                                        type="button"
                                        class="block group w-full text-left"
                                        @click="openCategory = openCategory == '{{ $parent->id }}' ? '' : '{{ $parent->id }}'"
                                    >
                                        <div class="relative h-full">
                                            @include('store.partials.shared.category-card-static', [
                                                'category' => $parent,
                                                'active' => $isParentBranchActive,
                                            ])

                                            <div class="absolute bottom-3 left-0 right-0 flex items-center justify-center gap-1 text-xs"
                                                :class="openCategory == '{{ $parent->id }}' ? 'text-black' : 'text-gray-500'">
                                                <span>{{ $children->count() }} subcategories</span>

                                                <svg class="w-4 h-4 transition-transform duration-200"
                                                    :class="openCategory == '{{ $parent->id }}' ? 'rotate-180' : ''"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </button>
                                @else
                                    @include('store.partials.shared.category-card', [
                                        'category' => $parent,
                                        'href' => route('shop.vehicle.category', [$vehicle->key_canonical, $parent->slug]),
                                        'active' => $isParentSelected,
                                    ])
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Expanded panel for the active parent in this row --}}
                    @foreach($row as $parent)
                        @php
                            $children = $groupedCategories->get($parent->id, collect());
                            $hasChildren = $children->count() > 0;

                            $isParentSelected = $selectedCategory
                                && $selectedCategory->id === $parent->id;
                        @endphp

                        @if($hasChildren)
                            <div
                                x-cloak
                                x-show="openCategory == '{{ $parent->id }}'"
                                x-transition
                                class="mb-6"
                            >
                                <div class="bg-white border border-gray-200 rounded-xl px-4 py-4 shadow-sm">
                                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-3">
                                        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                                            @foreach($children as $child)
                                                @php
                                                    $isChildActive = $selectedCategory
                                                        && $selectedCategory->id === $child->id;
                                                @endphp

                                                @include('store.partials.shared.subcategory-inline-card', [
                                                    'category' => $child,
                                                    'href' => route('shop.vehicle.subcategory', [
                                                        $vehicle->key_canonical,
                                                        $parent->slug,
                                                        $child->slug
                                                    ]),
                                                    'active' => $isChildActive,
                                                ])
                                            @endforeach
                                        </div>

                                        <div class="lg:shrink-0">
                                            <a href="{{ route('shop.vehicle.category', [$vehicle->key_canonical, $parent->slug]) }}"
                                            class="inline-flex items-center px-3 py-2 rounded-lg border text-xs transition {{ $isParentSelected ? 'bg-black text-white border-black' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
                                                View all {{ $parent->name }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </section>
    @endif

    {{-- Product Results --}}
    <div class="mb-4">
        <h2 class="text-lg font-semibold">
            @if($selectedCategory)
                Compatible {{ $selectedCategory->name }} ({{ $products->total() }})
            @else
                Compatible Parts ({{ $products->total() }})
            @endif
        </h2>
    </div>

    {{-- Products Grid --}}
    @if($products->count())

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="border rounded p-3 hover:shadow">
                    <a href="{{ route('product.show', $product->slug) }}">
                        <img src="{{ $product->image_url ?? '/images/placeholder.png' }}"
                             class="w-full h-40 object-cover mb-3">

                        <div class="text-sm font-semibold">
                            {{ $product->name }}
                        </div>

                        <div class="text-red-600 font-bold mt-1">
                            KSh {{ number_format($product->price, 2) }}
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $products->links() }}
        </div>

    @else

        {{-- Empty State --}}
        <div class="bg-yellow-50 border rounded p-6 text-center">
            <h3 class="font-semibold text-lg mb-2">
                @if($selectedCategory)
                    {{ $selectedCategory->name }} for this vehicle are coming soon
                @else
                    Parts for this vehicle are coming soon
                @endif
            </h3>

            <p class="text-gray-600 mb-4">
                @if($selectedCategory)
                    We are currently expanding our catalogue for {{ $selectedCategory->name }} for this vehicle.
                @else
                    We are currently expanding our catalogue for this vehicle.
                @endif
                Contact us if you need help finding parts.
            </p>

            <a href="https://wa.me/254792163144"
               class="bg-green-600 text-white px-5 py-2 rounded inline-block">
               WhatsApp Us
            </a>
        </div>

    @endif

</div>

@endsection