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
        $oeGroups = $product->oeReferences
            ->sortBy([
                ['brand_name_raw', 'asc'],
                ['reference_number_raw', 'asc'],
            ])
            ->groupBy(function ($reference) {
                return $reference->brand_name_raw ?: 'Other';
            });

        $compatibilityItems = $product->engines
            ->groupBy(function ($engine) {
                return $engine->generation?->model?->make?->name ?? 'Other';
            })
            ->map(function ($enginesByMake) {
                return $enginesByMake
                    ->groupBy(function ($engine) {
                        $modelName = $engine->generation?->model?->name;
                        $generationCode = $engine->generation?->code;

                        if ($modelName && $generationCode) {
                            return "{$modelName} ({$generationCode})";
                        }

                        return $modelName ?: 'Other';
                    })
                    ->map(function ($enginesByModel) {
                        return $enginesByModel
                            ->map(function ($engine) {
                                $variant = $engine->variant_name;
                                $capacity = $engine->capacity_l;
                                $engineCode = $engine->engine_code;
                                $years = null;

                                if ($engine->year_from && $engine->year_to) {
                                    $years = "{$engine->year_from}-{$engine->year_to}";
                                } elseif ($engine->year_from) {
                                    $years = "{$engine->year_from}-";
                                } elseif ($engine->year_to) {
                                    $years = "-{$engine->year_to}";
                                }
                                $fuel = $engine->fuel_type ? "({$engine->fuel_type})" : null;

                                return collect([
                                    $variant,
                                    $capacity,
                                    $engineCode,
                                    $years,
                                    $fuel,
                                ])->filter()->implode(' ');
                            })
                            ->filter()
                            ->values();
                    })
                    ->filter();
            })
            ->filter();
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
            @if(!empty($compatibilityItems) && count($compatibilityItems))
                <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    @foreach($compatibilityItems as $make => $models)
                        <div
                            x-data="{ openMake: false }"
                            class="border-b border-gray-200 last:border-b-0"
                        >
                            <button
                                type="button"
                                @click="openMake = !openMake"
                                class="flex w-full items-center gap-3 px-5 py-4 bg-gray-50 border-b border-gray-200 text-left"
                            >
                                <span class="text-lg font-bold text-orange-600 w-5 text-center" x-text="openMake ? '−' : '+'"></span>

                                <h3 class="text-sm font-bold tracking-wide text-slate-900 uppercase">
                                    {{ $make }}
                                </h3>
                            </button>

                            <div x-show="openMake" x-collapse>
                                @foreach($models as $model => $vehicles)
                                    <div
                                        x-data="{ openModel: false }"
                                        class="border-b border-gray-100 last:border-b-0"
                                    >
                                        <button
                                            type="button"
                                            @click="openModel = !openModel"
                                            class="flex w-full items-center gap-3 px-5 py-4 text-left"
                                        >
                                            <span class="text-base font-bold text-orange-600 w-5 text-center" x-text="openModel ? '−' : '+'"></span>

                                            <h4 class="text-sm font-semibold text-slate-800">
                                                {{ $model }}
                                            </h4>
                                        </button>

                                        <div x-show="openModel" x-collapse class="px-5 pb-4">
                                            <div class="space-y-3">
                                                @foreach($vehicles as $item)
                                                    <div class="flex items-start gap-3 text-sm">
                                                        <span class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-green-100 text-green-600 text-xs font-bold">
                                                            ✓
                                                        </span>

                                                        <div>
                                                            <div class="font-medium text-slate-900">
                                                                {{ $item }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-lg border border-gray-200 bg-gray-50 px-5 py-4">
                    <p class="text-gray-500 text-sm">
                        No compatibility data available yet.
                    </p>
                </div>
            @endif
        </div>

        {{-- OE NUMBER --}}
        <div
            x-show="activeTab === 'oe'"
            x-transition.opacity.duration.150ms
            x-cloak
        >
            @if($oeGroups->isNotEmpty())
                <div
                    x-data="{ openBrand: null }"
                    class="mt-4"
                >
                    {{-- Brand row --}}
                    <div class="flex flex-wrap items-center gap-3 py-2">
                        @foreach($oeGroups as $brand => $references)
                            <button
                                type="button"
                                @click="openBrand === '{{ md5($brand) }}' ? openBrand = null : openBrand = '{{ md5($brand) }}'"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:text-orange-600"
                                :class="openBrand === '{{ md5($brand) }}'
                                ? 'text-orange-600 border-b-2 border-orange-500'
                                : 'border-b-2 border-transparent'"
                            >
                                <span x-text="openBrand === '{{ md5($brand) }}' ? '−' : '+'"></span>
                                <span>{{ $brand }}</span>
                            </button>
                        @endforeach
                    </div>

                    {{-- Expanded content --}}
                    @foreach($oeGroups as $brand => $references)
                        <div
                            x-show="openBrand === '{{ md5($brand) }}'"
                            x-transition.opacity.duration.150ms
                            x-cloak
                            class="pt-4"
                        >
                            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
                                <table class="min-w-full text-sm">


                                    <tbody class="min-w-full">
                                        @foreach($references as $reference)
                                            <tr
                                                x-data="{ copied: false }"
                                                class="hover:bg-gray-50 transition"
                                            >
                                                <td class="px-4 py-1.5 font-medium text-slate-900 tracking-wide">
                                                    {{ $reference->reference_number_raw }}
                                                </td>

                                                <td class="px-4 py-1.5 text-right">
                                                    <button
                                                        type="button"
                                                        @click="
                                                            navigator.clipboard.writeText('{{ $reference->reference_number_raw }}');
                                                            copied = true;
                                                            setTimeout(() => copied = false, 1200);
                                                        "
                                                        class="inline-flex items-center justify-center rounded-md p-2 text-slate-500 transition hover:bg-gray-100 hover:text-orange-600"
                                                        title="Copy OE number"
                                                        aria-label="Copy OE number {{ $reference->reference_number_raw }}"
                                                    >
                                                        <svg x-show="!copied" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2M10 20h8a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-8a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2Z" />
                                                        </svg>

                                                        <svg x-show="copied" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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