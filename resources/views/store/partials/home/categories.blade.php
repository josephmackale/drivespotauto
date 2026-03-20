<section class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-semibold text-center mb-12">
            Shop by Category
        </h2>

        @php
            $categories = \App\Models\Category::whereNull('parent_id')
                ->orderBy('name')
                ->get();

            $vehicleKey = session('shop_vehicle.key');
        @endphp

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @foreach($categories as $category)
                @php
                    $href = $vehicleKey
                        ? route('shop.vehicle.category', [$vehicleKey, $category->slug])
                        : route('shop.category', $category->slug);
                @endphp

                @include('store.partials.shared.category-card', [
                    'category' => $category,
                    'href' => $href,
                    'active' => false,
                ])
            @endforeach
        </div>
    </div>
</section>