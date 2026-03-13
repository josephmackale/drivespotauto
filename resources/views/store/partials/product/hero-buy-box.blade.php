<div class="bg-white p-6 rounded-lg shadow-sm h-fit space-y-6">

    {{-- PRICE --}}
    <div>
        <p class="text-3xl font-bold text-blue-700">
            KES {{ number_format((float) ($product->price ?? 0), 2) }}
        </p>
    </div>

    {{-- STOCK STATUS --}}
    @if(($product->stock ?? 0) > 0)

        <p class="text-green-600 font-medium">In Stock</p>

        {{-- QUANTITY --}}
        <div class="flex items-center border rounded-lg overflow-hidden w-fit">
            <button class="px-4 py-2 bg-gray-100 hover:bg-gray-200">−</button>

            <input
                type="number"
                value="1"
                min="1"
                max="{{ $product->stock }}"
                class="w-16 text-center outline-none"
            >

            <button class="px-4 py-2 bg-gray-100 hover:bg-gray-200">+</button>
        </div>

        {{-- ADD TO CART --}}
        <button class="w-full py-3 bg-orange-500 text-white font-semibold rounded-lg hover:bg-orange-600 flex items-center justify-center gap-2">
            🛒 Add to Cart
        </button>

        {{-- BUY NOW --}}
        <button class="w-full py-3 bg-blue-700 text-white font-semibold rounded-lg hover:bg-blue-800 flex items-center justify-center gap-2">
            Buy Now →
        </button>

    @else

        <p class="text-red-600 font-medium">Out of Stock</p>

        <button
            disabled
            class="w-full py-3 bg-gray-300 text-gray-600 rounded-lg cursor-not-allowed"
        >
            Out of Stock
        </button>

    @endif

    {{-- WISHLIST / COMPARE --}}
    <div class="flex items-center gap-6 text-sm text-gray-600">

        <button class="flex items-center gap-2 hover:text-blue-600">
            ♡ Add to wishlist
        </button>

        <button class="flex items-center gap-2 hover:text-blue-600">
            ⇄ Add to compare
        </button>

    </div>

    {{-- CATEGORY --}}
    <div class="text-sm text-gray-600">
        <span class="font-medium text-gray-800">Categories:</span>

        @if($product->category)
            <span class="text-blue-600">
                {{ $product->category->name }}
            </span>
        @endif
    </div>

    {{-- SHARE --}}
    <div class="flex gap-2 pt-2">

        <a class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded">f</a>
        <a class="w-8 h-8 flex items-center justify-center bg-black text-white rounded">x</a>
        <a class="w-8 h-8 flex items-center justify-center bg-red-600 text-white rounded">p</a>
        <a class="w-8 h-8 flex items-center justify-center bg-blue-500 text-white rounded">in</a>
        <a class="w-8 h-8 flex items-center justify-center bg-green-600 text-white rounded">wa</a>
        <a class="w-8 h-8 flex items-center justify-center bg-purple-500 text-white rounded">tg</a>

    </div>

</div>