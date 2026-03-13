<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::with([
                'brand',
                'images',
                'attributeValues.attribute',
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedProducts = Product::with('brand')
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get();

        return view('store.product', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}