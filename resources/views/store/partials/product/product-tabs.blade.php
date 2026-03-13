<div class="mt-16 mb-16" x-data="{ activeTab: 'description' }">

    <style>
        [x-cloak] { display: none !important; }
    </style>

    {{-- TAB HEADERS --}}
    <div class="border-b border-gray-300">
        <nav class="flex flex-wrap gap-8">
            <button
                type="button"
                @click="activeTab = 'description'"
                class="relative pb-4 text-sm md:text-base font-medium transition"
                :class="activeTab === 'description'
                    ? 'text-orange-600'
                    : 'text-slate-800 hover:text-orange-600'"
            >
                Description
                <span
                    x-show="activeTab === 'description'"
                    x-cloak
                    class="absolute left-0 bottom-[-1px] h-[3px] w-full bg-orange-500"
                ></span>
            </button>

            <button
                type="button"
                @click="activeTab = 'compatibility'"
                class="relative pb-4 text-sm md:text-base font-medium transition"
                :class="activeTab === 'compatibility'
                    ? 'text-orange-600'
                    : 'text-slate-800 hover:text-orange-600'"
            >
                Compatibility
                <span
                    x-show="activeTab === 'compatibility'"
                    x-cloak
                    class="absolute left-0 bottom-[-1px] h-[3px] w-full bg-orange-500"
                ></span>
            </button>

            <button
                type="button"
                @click="activeTab = 'oe'"
                class="relative pb-4 text-sm md:text-base font-medium transition"
                :class="activeTab === 'oe'
                    ? 'text-orange-600'
                    : 'text-slate-800 hover:text-orange-600'"
            >
                OE Number
                <span
                    x-show="activeTab === 'oe'"
                    x-cloak
                    class="absolute left-0 bottom-[-1px] h-[3px] w-full bg-orange-500"
                ></span>
            </button>
        </nav>
    </div>

    @php
        $oemValues = $product->attributeValues->filter(function ($item) {
            if (! $item->attribute || ! filled($item->value)) {
                return false;
            }

            $name = strtolower($item->attribute->name);

            return str_contains($name, 'oe')
                || str_contains($name, 'oem')
                || str_contains($name, 'part number')
                || str_contains($name, 'reference');
        });

        // placeholder collection for now
        $compatibilityItems = collect();
    @endphp

    {{-- TAB CONTENT --}}
    <div class="pt-8">

        {{-- DESCRIPTION --}}
        <div
            x-show="activeTab === 'description'"
            x-transition.opacity.duration.150ms
        >
            @if(filled($product->description))
                <div class="prose max-w-none text-gray-700">
                    {!! $product->description !!}
                </div>
            @else
                <p class="text-gray-500 text-sm">
                    No product description available.
                </p>
            @endif
        </div>

        {{-- COMPATIBILITY --}}
        <div
            x-show="activeTab === 'compatibility'"
            x-transition.opacity.duration.150ms
            x-cloak
        >
            @if($compatibilityItems->isNotEmpty())
                <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    @foreach($compatibilityItems as $item)
                        <div class="px-5 py-3 text-sm border-b last:border-b-0 border-gray-200">
                            {{ $item }}
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm">
                    No compatibility data available yet.
                </p>
            @endif
        </div>

        {{-- OE NUMBER --}}
        <div
            x-show="activeTab === 'oe'"
            x-transition.opacity.duration.150ms
            x-cloak
        >
            @if($oemValues->isNotEmpty())
                <div class="overflow-hidden rounded-lg border border-gray-200 bg-[#f5f5f5]">
                    @foreach($oemValues as $attributeValue)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 px-5 py-3 text-sm odd:bg-[#efefef] even:bg-[#f8f8f8]">
                            <div class="text-slate-800">
                                {{ $attributeValue->attribute->name }}
                            </div>

                            <div class="text-slate-900 font-medium">
                                {{ $attributeValue->value }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm">
                    No OE numbers available.
                </p>
            @endif
        </div>

    </div>
</div>