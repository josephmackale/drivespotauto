@extends('layouts.store')

@section('title')
    @php
        $makeName = $vehicle->generation?->model?->make?->name;
        $modelName = $vehicle->generation?->model?->name;
        $engineCode = $vehicle->engine_code;
        $categoryName = $selectedCategory?->name;
        $parentCategoryName = $selectedCategory?->parent?->name;
        $isSubcategory = $selectedCategory && $selectedCategory->parent_id;
        $parentCategory = $selectedParentCategory ?? ($isSubcategory ? $selectedCategory->parent : $selectedCategory);
    @endphp

    @if($selectedCategory)
        @if($isSubcategory)
            {{ $makeName }} {{ $modelName }} {{ $engineCode }} {{ $categoryName }} | DriveSpot Auto
        @else
            {{ $makeName }} {{ $modelName }} {{ $engineCode }} {{ $categoryName }} | DriveSpot Auto
        @endif
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

        $isSubcategory = $selectedCategory && $selectedCategory->parent_id;
        $parentCategory = $isSubcategory ? $selectedCategory->parent : $selectedCategory;
    @endphp

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-600 mb-4">
        <a href="{{ route('home') }}" class="hover:text-black">Home</a>
        <span class="mx-1">|</span>

        <a href="{{ route('shop') }}" class="hover:text-black">Shop</a>
        <span class="mx-1">|</span>

        <a href="{{ route('shop.vehicle', $vehicle->key_canonical) }}" class="hover:text-black">
            {{ $make?->name }} {{ $model?->name }} {{ $engine }}
        </a>

        @if($parentCategory)
            <span class="mx-1">|</span>
            <a href="{{ route('shop.vehicle.category', [$vehicle->key_canonical, $parentCategory->slug]) }}"
               class="hover:text-black">
                {{ $parentCategory->name }}
            </a>
        @endif

        @if($isSubcategory)
            <span class="mx-1">|</span>
            <span class="font-semibold text-gray-900">{{ $selectedCategory->name }}</span>
        @elseif($selectedCategory)
            <span class="mx-1">|</span>
            <span class="font-semibold text-gray-900">{{ $selectedCategory->name }}</span>
        @endif
    </nav>

    {{-- Vehicle Title --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold leading-tight">
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
                You are viewing
                <strong>{{ $selectedCategory->name }}</strong>
                @if($isSubcategory && $parentCategory)
                    under <strong>{{ $parentCategory->name }}</strong>
                @endif
                for this vehicle.
            @endif
        </p>
    </div>

    {{-- Vehicle Info Box --}}
    <div class="bg-gray-100 rounded-xl p-4 mb-8">
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

    {{-- Compact Browsing Context --}}
    @if($selectedCategory)
        <section class="bg-white border border-gray-200 rounded-xl p-4 mb-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Browsing</p>

                    @if($isSubcategory && $parentCategory)
                        <h2 class="text-lg font-semibold text-gray-900">
                            {{ $parentCategory->name }} / {{ $selectedCategory->name }}
                        </h2>
                    @else
                        <h2 class="text-lg font-semibold text-gray-900">
                            {{ $selectedCategory->name }}
                        </h2>
                    @endif

                    <p class="text-sm text-gray-600 mt-1">
                        {{ $products->total() }} compatible product{{ $products->total() == 1 ? '' : 's' }} found
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('shop.vehicle', $vehicle->key_canonical) }}"
                       class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 hover:bg-gray-50 transition">
                        Back to categories
                    </a>

                    @if($isSubcategory && $parentCategory)
                        <a href="{{ route('shop.vehicle.category', [$vehicle->key_canonical, $parentCategory->slug]) }}"
                           class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 hover:bg-gray-50 transition">
                            View all {{ $parentCategory->name }}
                        </a>
                    @endif
                </div>
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
                <div class="border rounded-xl p-3 hover:shadow transition bg-white">
                    <a href="{{ route('product.show', $product->slug) }}">
                        <img
                            src="{{ $product->image_url ?? '/images/placeholder.png' }}"
                            alt="{{ $product->name }}"
                            class="w-full h-40 object-cover mb-3 rounded"
                        >

                        <div class="text-sm font-semibold text-gray-900 line-clamp-2">
                            {{ $product->name }}
                        </div>

                        <div class="text-red-600 font-bold mt-2">
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
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center">
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
                Contact us if you need help finding the right part.
            </p>

            <a href="https://wa.me/254792163144"
               class="bg-green-600 text-white px-5 py-2 rounded-lg inline-block hover:bg-green-700 transition">
                WhatsApp Us
            </a>
        </div>
    @endif
</div>
@endsection