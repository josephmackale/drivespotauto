@extends('layouts.store')

@section('content')
<section class="max-w-7xl mx-auto px-4 py-10">

    @php
        $specifications = $product->attributeValues
            ->filter(function ($item) {
                return $item->attribute && filled($item->value);
            })
            ->sortBy(function ($item) {
                return $item->attribute->name;
            });
    @endphp

    @include('store.partials.product.hero', [
        'product' => $product,
        'specifications' => $specifications,
    ])

    @include('store.partials.product.product-tabs', [
        'product' => $product,
        'specifications' => $specifications,
    ])

    @include('store.partials.product.related-products', [
        'relatedProducts' => $relatedProducts,
    ])

</section>
@endsection