<div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mb-16">

    @include('store.partials.product.hero-gallery', [
        'product' => $product,
    ])

    @include('store.partials.product.hero-details', [
        'product' => $product,
        'specifications' => $specifications,
    ])

    @include('store.partials.product.hero-buy-box', [
        'product' => $product,
    ])

</div>