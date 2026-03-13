<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function home()
    {
        $featuredProducts = Product::query()
            ->latest()
            ->take(8)
            ->get();

        return view('store.home', [
            'featuredProducts' => $featuredProducts,
        ]);
    }

    public function shop(Request $request)
    {
        $products = Product::query()
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('store.shop', [
            'products' => $products,
        ]);
    }
}