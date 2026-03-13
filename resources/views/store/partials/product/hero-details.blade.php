<div>
    <p class="text-sm text-gray-500 mb-2">
        {{ $product->brand->name ?? 'No Brand' }}
    </p>

    <h1 class="text-3xl font-bold mb-3">
        {{ $product->name ?? 'Unnamed Product' }}
    </h1>

    <p class="text-sm text-gray-500 mb-6">
        SKU: {{ $product->sku ?? 'N/A' }}
    </p>

    @if(filled($product->description))
        <div class="prose max-w-none text-gray-700 mb-8">
            {!! $product->description !!}
        </div>
    @endif

    {{-- PRODUCT INFORMATION --}}
    @if($specifications->isNotEmpty())
        <div class="mt-6 rounded-lg overflow-hidden bg-[#f3f3f3] border border-gray-200">

            {{-- Header --}}
            <button
                type="button"
                class="w-full flex items-center justify-between px-5 py-4 bg-[#d9d9d9] text-left"
            >
                <h2 class="text-lg font-semibold text-gray-900">
                    Product information
                </h2>

                {{-- simple chevron icon --}}
                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 15l-7-7-7 7"/>
                </svg>
            </button>

            {{-- Rows --}}
            <div>
                @foreach($specifications as $attributeValue)
                    <div class="grid grid-cols-2 gap-4 px-5 py-3 text-sm odd:bg-[#efefef] even:bg-[#f7f7f7]">

                        {{-- Label --}}
                        <div class="text-gray-800 font-normal">
                            {{ $attributeValue->attribute->name }}
                        </div>

                        {{-- Value --}}
                        <div class="text-gray-800 text-left">
                            {{ $attributeValue->value }}
                            @if(filled($attributeValue->attribute->unit))
                                {{ $attributeValue->attribute->unit }}
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

        </div>
    @endif
</div>