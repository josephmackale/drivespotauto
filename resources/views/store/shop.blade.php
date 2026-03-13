@extends('layouts.store')

@section('content')
    <section class="max-w-7xl mx-auto px-4 py-10">
        <div class="mb-8">
            <h1 class="text-3xl font-bold">Shop</h1>
            <p class="text-gray-600 mt-2">Browse our auto parts catalog.</p>
        </div>

        @if($products->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm hover:shadow-lg hover:-translate-y-1 transition">
                        <div class="aspect-square bg-gray-50 rounded-2xl mb-4 flex items-center justify-center overflow-hidden">
                            @if($product->image)
                                <img
                                    src="{{ asset('storage/' . $product->image) }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-contain p-6 transition-transform duration-200 hover:scale-105"
                                >
                            @else
                                <span class="text-sm text-gray-400">No Image</span>
                            @endif
                        </div>

                        <h2 class="font-semibold text-lg mb-2 line-clamp-2 min-h-[3.5rem]">
                            <a href="{{ route('product.show', $product->slug) }}" class="hover:text-blue-600">
                                {{ $product->name ?? 'Unnamed Product' }}
                            </a>
                        </h2>

                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-3">
                            {{ $product->brand->name ?? 'No Brand' }}
                        </p>

                        <p class="text-2xl font-bold text-blue-700 mb-4">
                            KES {{ number_format((float) ($product->price ?? 0), 2) }}
                        </p>

                        <a
                            href="{{ route('product.show', $product->slug) }}"
                            class="block w-full text-center px-4 py-3 bg-gray-900 text-white rounded-xl hover:bg-black transition"
                        >
                            View Product
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <div class="bg-white border rounded-xl p-8 text-gray-600">
                No products found.
            </div>
        @endif
    </section>
@endsection