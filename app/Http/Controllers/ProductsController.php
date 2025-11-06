<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class ProductsController extends Controller
{
    public function index(): View
    {
        $products = Product::with(['brand', 'category'])
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('products.index', [
            'products' => $products,
        ]);
    }

    public function show(Product $product): View
    {
        $product->load(['brand', 'category', 'tags']);

        return view('products.show', [
            'product' => $product,
        ]);
    }
}
