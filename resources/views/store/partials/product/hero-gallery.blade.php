<div>
    <div class="aspect-square bg-white flex items-center justify-center">
        @if($product->image)
            <img
                src="{{ asset('storage/' . $product->image) }}"
                alt="{{ $product->name }}"
                class="object-contain w-full h-full p-6"
            >
        @else
            <span class="text-gray-400">No Image</span>
        @endif
    </div>
</div>