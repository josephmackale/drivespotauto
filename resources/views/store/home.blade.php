@extends('layouts.store')

@section('content')
    <section class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-16">
            <h1 class="text-4xl font-bold mb-4">Auto Parts That Fit</h1>
            <p class="text-lg text-gray-600 max-w-2xl mb-6">
                Find quality parts for your vehicle quickly and easily.
            </p>

            <div class="flex gap-4">
                <a href="{{ route('shop') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Shop Now
                </a>

                <a href="{{ route('cart') }}" class="px-6 py-3 border rounded-lg hover:bg-gray-100">
                    View Cart
                </a>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 py-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-semibold">Featured Products</h2>
            <a href="{{ route('shop') }}" class="text-blue-600 hover:underline">View all</a>
        </div>

        @if($featuredProducts->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                    <div class="bg-white rounded-xl border p-4 shadow-sm">
                        <div class="aspect-square bg-gray-100 rounded-lg mb-4"></div>

                        <h3 class="font-semibold text-lg mb-2">
                            @if(!empty($product->slug))
                                <a href="{{ route('product.show', ['slug' => $product->slug]) }}" class="hover:text-blue-600">
                                    {{ $product->name ?? 'Unnamed Product' }}
                                </a>
                            @else
                                <span class="text-gray-400">
                                    {{ $product->name ?? 'Unnamed Product' }}
                                </span>
                            @endif
                        </h3>

                        <p class="text-sm text-gray-500 mb-3">
                            {{ $product->brand->name ?? 'No Brand' }}
                        </p>

                        <p class="text-xl font-bold mb-4">
                            KES {{ number_format((float) ($product->price ?? 0), 2) }}
                        </p>

                        @if(!empty($product->slug))
                            <a href="{{ route('product.show', ['slug' => $product->slug]) }}"
                            class="inline-block px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-black">
                                View Product
                            </a>
                        @else
                            <span class="inline-block px-4 py-2 bg-gray-300 text-gray-600 rounded-lg cursor-not-allowed">
                                No Slug
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white border rounded-xl p-8 text-gray-600">
                No featured products yet.
            </div>
        @endif
    </section>
@endsection