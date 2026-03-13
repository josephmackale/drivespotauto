@extends('layouts.store')

@section('content')
    <section class="max-w-7xl mx-auto px-4 py-10">
        <h1 class="text-3xl font-bold mb-8">Checkout</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white border rounded-2xl p-6">
                <h2 class="text-xl font-semibold mb-6">Customer Details</h2>

                <form class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Full Name</label>
                        <input type="text" class="w-full border rounded-lg px-4 py-3">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Phone Number</label>
                        <input type="text" class="w-full border rounded-lg px-4 py-3">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Email Address</label>
                        <input type="email" class="w-full border rounded-lg px-4 py-3">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Delivery Address</label>
                        <textarea class="w-full border rounded-lg px-4 py-3" rows="4"></textarea>
                    </div>

                    <button type="button" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Place Order
                    </button>
                </form>
            </div>

            <div class="bg-white border rounded-2xl p-6 h-fit">
                <h2 class="text-xl font-semibold mb-6">Order Summary</h2>

                <div class="space-y-3 mb-6">
                    @forelse($cart as $item)
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="font-medium">{{ $item['name'] ?? 'Product' }}</p>
                                <p class="text-sm text-gray-500">Qty: {{ $item['quantity'] ?? 1 }}</p>
                            </div>
                            <div class="font-semibold">
                                KES {{ number_format((float) ($item['price'] ?? 0), 2) }}
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">Your cart is empty.</p>
                    @endforelse
                </div>

                <div class="border-t pt-4 flex items-center justify-between text-lg font-semibold">
                    <span>Total</span>
                    <span>KES {{ number_format($subtotal, 2) }}</span>
                </div>
            </div>
        </div>
    </section>
@endsection