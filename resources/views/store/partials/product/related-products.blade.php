<section>
    <h2 class="text-2xl font-semibold mb-6">Related Products</h2>

    @if($relatedProducts->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $related)
                <div class="bg-white rounded-xl border p-4 shadow-sm">

                    <div class="aspect-square bg-gray-100 rounded-lg mb-4 flex items-center justify-center overflow-hidden">
                        @if($related->image)
                            <img
                                src="{{ asset('storage/' . $related->image) }}"
                                alt="{{ $related->name }}"
                                class="object-contain w-full h-full p-4"
                            >
                        @else
                            <span class="text-sm text-gray-400">No Image</span>
                        @endif
                    </div>

                    <h3 class="font-semibold text-lg mb-2">
                        <a href="{{ route('product.show', $related->slug) }}" class="hover:text-blue-600">
                            {{ $related->name ?? 'Unnamed Product' }}
                        </a>
                    </h3>

                    <p class="text-blue-700 font-bold">
                        KES {{ number_format((float) ($related->price ?? 0), 2) }}
                    </p>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500">No related products found.</p>
    @endif
</section>