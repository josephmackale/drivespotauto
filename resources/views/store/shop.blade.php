@extends('layouts.store')

@section('title')
    @php
        $hasVehicle = isset($vehicle) && $vehicle;
        $selectedCategorySafe = $selectedCategory ?? null;

        $makeName = $hasVehicle ? $vehicle->generation?->model?->make?->name : null;
        $modelName = $hasVehicle ? $vehicle->generation?->model?->name : null;
        $engineCode = $hasVehicle ? $vehicle->engine_code : null;

        $baseParts = array_filter([
            $makeName,
            $modelName,
            $engineCode,
        ]);
    @endphp

    @if($hasVehicle && $selectedCategorySafe)
        {{ implode(' ', $baseParts) }} {{ $selectedCategorySafe->name }} | DriveSpot Auto
    @elseif($hasVehicle)
        {{ implode(' ', $baseParts) }} Parts | DriveSpot Auto
    @elseif($selectedCategorySafe)
        {{ $selectedCategorySafe->name }} | DriveSpot Auto
    @else
        Shop Auto Parts Online | DriveSpot Auto
    @endif
@endsection

@section('content')

    @include('store.partials.shop.header')

    <div class="max-w-7xl mx-auto px-4 pt-6">
        @include('store.partials.shop.breadcrumbs')
    </div>

    <section class="max-w-7xl mx-auto px-4 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-[280px_minmax(0,1fr)] gap-10 items-start">

            <aside class="lg:sticky lg:top-6">
                @include('store.partials.shop.sidebar')
            </aside>

            <div class="min-w-0 space-y-6">

                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 space-y-5">
                    @include('store.partials.shop.active-filters')
                    @include('store.partials.shop.results-meta')
                </div>

                @if($products->count())
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
                        @include('store.partials.shop.product-grid', ['products' => $products])
                    </div>
                @else
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
                        @include('store.partials.shop.empty-state')
                    </div>
                @endif

            </div>

        </div>
    </section>
@endsection