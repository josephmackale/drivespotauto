@php
    $hasVehicle = isset($vehicle) && $vehicle;

    $selectedCategoryId = $selectedCategory->id ?? null;
    $selectedParentCategoryId = $selectedParentCategory->id ?? null;

    $rootCategories = $categories ?? collect();

    $buildCategoryUrl = function ($category, $parent = null) use ($hasVehicle, $vehicle) {
        if ($hasVehicle) {
            if ($parent) {
                return route('shop.vehicle.subcategory', [
                    'vehicle_key'      => $vehicle->key_canonical,
                    'category_slug'    => $parent->slug,
                    'subcategory_slug' => $category->slug,
                ]);
            }

            return route('shop.vehicle.category', [
                'vehicle_key'   => $vehicle->key_canonical,
                'category_slug' => $category->slug,
            ]);
        }

        if ($parent && Route::has('shop.subcategory')) {
            return route('shop.subcategory', [
                'category_slug'    => $parent->slug,
                'subcategory_slug' => $category->slug,
            ]);
        }

        return route('shop.category', [
            'slug' => $category->slug,
        ]);
    };
@endphp

<div class="bg-white rounded-xl border border-gray-100">

    <div class="px-4 py-4 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900">
            Categories
        </h3>
    </div>

    <div class="px-4 py-4">
        <input
            type="text"
            placeholder="Search for a category"
            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:border-gray-400"
        >
    </div>

    <nav class="px-3 pb-4 space-y-2">
        @forelse($rootCategories as $category)
            @php
                $children = $category->children ?? collect();

                $isParentActive =
                    $selectedCategoryId === $category->id ||
                    $selectedParentCategoryId === $category->id;

                $shouldOpen = $isParentActive;
            @endphp

            <div x-data="{ open: {{ $shouldOpen ? 'true' : 'false' }} }" class="rounded-lg">
                <div class="flex items-center gap-2">
                    <a
                        href="{{ $buildCategoryUrl($category) }}"
                        class="flex-1 flex items-center justify-between rounded-lg px-3 py-3 text-sm transition
                            {{ $isParentActive
                                ? 'bg-gray-900 text-white font-semibold'
                                : 'text-gray-800 hover:bg-gray-50' }}"
                    >
                        <span>{{ $category->name }}</span>

                        @if($children->count())
                            <span class="text-xs {{ $isParentActive ? 'text-white/80' : 'text-gray-400' }}">
                                {{ $children->count() }}
                            </span>
                        @endif
                    </a>

                    @if($children->count())
                        <button
                            type="button"
                            @click="open = !open"
                            class="shrink-0 rounded-lg px-3 py-3 text-sm text-gray-500 hover:bg-gray-50 hover:text-gray-800 transition"
                            :aria-expanded="open ? 'true' : 'false'"
                        >
                            <span x-text="open ? '−' : '+'"></span>
                        </button>
                    @endif
                </div>

                @if($children->count())
                    <div
                        x-show="open"
                        x-cloak
                        class="mt-2 ml-3 border-l border-gray-200 pl-3 space-y-1"
                    >
                        @foreach($children as $child)
                            @php
                                $isChildActive = $selectedCategoryId === $child->id;
                            @endphp

                            <a
                                href="{{ $buildCategoryUrl($child, $category) }}"
                                class="block rounded-lg px-3 py-2.5 text-sm transition
                                    {{ $isChildActive
                                        ? 'bg-gray-100 text-gray-900 font-semibold'
                                        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
                            >
                                {{ $child->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="px-1 py-2 text-sm text-gray-500">
                No categories available.
            </div>
        @endforelse
    </nav>

</div>