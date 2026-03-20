<div class="group bg-white rounded-lg border border-gray-200 p-4 transition hover:border-gray-300 hover:shadow-sm">

    <div class="aspect-square bg-gray-50 rounded-lg mb-3 flex items-center justify-center overflow-hidden">
        @if($product->image)
            <img
                src="{{ asset('storage/' . $product->image) }}"
                alt="{{ $product->name }}"
                class="h-full w-full object-contain transition group-hover:scale-[1.02]"
            >
        @else
            <span class="text-sm text-gray-400">
                No image
            </span>
        @endif
    </div>

    <div class="space-y-1.5">
        <h3 class="min-h-[2.75rem] text-sm font-semibold leading-5 text-gray-900 line-clamp-2">
            {{ $product->name }}
        </h3>

        @if($product->sku)
            <p class="text-xs text-gray-500">
                SKU: {{ $product->sku }}
            </p>
        @endif

        <div class="pt-2 flex items-center justify-between gap-3">
            <div class="text-base font-bold text-gray-900">
                @if($product->price)
                    KSh {{ number_format($product->price, 2) }}
                @endif
            </div>

            <a
                href="{{ route('product.show', $product->slug ?? $product->id) }}"
                class="inline-flex items-center rounded-md border border-gray-300 px-3 py-1.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
            >
                View
            </a>
        </div>
    </div>

</div>