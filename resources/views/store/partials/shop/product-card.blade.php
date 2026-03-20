<div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm hover:shadow-lg hover:-translate-y-1 transition">

    <div class="aspect-square bg-gray-50 rounded-2xl mb-4 flex items-center justify-center overflow-hidden">

        @if($product->image)
            <img
                src="{{ asset('storage/'.$product->image) }}"
                alt="{{ $product->name }}"
                class="object-contain w-full h-full"
            >
        @else
            <span class="text-gray-400 text-sm">
                No image
            </span>
        @endif

    </div>

    <div class="space-y-2">

        <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 min-h-[3rem]">
            {{ $product->name }}
        </h3>

        @if($product->sku)
            <p class="text-sm text-gray-500">
                SKU: {{ $product->sku }}
            </p>
        @endif

        <div class="flex items-center justify-between pt-2">

            <div class="text-xl font-bold text-gray-900">
                @if($product->price)
                    KSh {{ number_format($product->price,2) }}
                @endif
            </div>

            <a
                href="{{ route('product.show', $product->slug ?? $product->id) }}"
                class="px-4 py-2 bg-gray-900 text-white rounded-xl text-sm hover:bg-black transition"
            >
                View
            </a>

        </div>

    </div>

</div>