<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

    @foreach($products as $product)

        @include('store.partials.shop.product-card', [
            'product' => $product
        ])

    @endforeach

</div>