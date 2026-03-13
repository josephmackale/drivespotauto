@extends('layouts.store')

@section('content')
    <section class="max-w-7xl mx-auto px-4 py-10">
        <h1 class="text-3xl font-bold mb-8">Your Cart</h1>

        @if(!empty($cart))
            <div class="bg-white border rounded-2xl overflow-hidden">
                <div class="divide-y">
                    @foreach($cart as $item)
                        <div class="p-5 flex items-center justify-between gap-4">
                            <div>
                                <h2 class="font-semibold text-lg">{{ $item['name'] ?? 'Product' }}</h2>
                                <p class="text-sm text-gray-500">Qty: {{ $item['quantity'] ?? 1 }}</p>
                            </div>

                            <div class="text-right font-semibold">
                                KES {{ number_format((float) ($item['price'] ?? 0), 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 bg-white border rounded-2xl p-6 max-w-md ml-auto">
                <div class="flex items-center justify-between text-lg font-semibold mb-4">
                    <span>Subtotal</span>
                    <span>KES {{ number_format($subtotal, 2) }}</span>
                </div>

                <a href="{{ route('checkout') }}"
                   class="block w-full text-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Proceed to Checkout
                </a>
            </div>
        @else
            <div class="bg-white border rounded-2xl p-8 text-gray-600">
                Your cart is empty.
            </div>
        @endif
    </section>
@endsection