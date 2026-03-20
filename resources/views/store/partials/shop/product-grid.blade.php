@php
    $hasProducts = $products->count() > 0;
    $hasPagination = method_exists($products, 'hasPages') && $products->hasPages();
@endphp

@if($hasProducts)

    {{-- Product Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($products as $product)
            @include('store.partials.shop.product-card', [
                'product' => $product
            ])
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($hasPagination)
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @endif

@else

    {{-- Empty State --}}
    @include('store.partials.shop.empty-state')

@endif