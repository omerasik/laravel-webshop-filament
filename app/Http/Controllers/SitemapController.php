<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    // maak de sitemap.xml aan voor zoekmachines
    public function __invoke(): Response
    {
        $pages = [
            route('home'),
            route('products.index'),
            route('cart.index'),
            route('checkout.index'),
        ];

        $products = Product::orderByDesc('updated_at')
            ->get(['id', 'updated_at']);

        $xml = view('sitemap', [
            'pages' => $pages,
            'products' => $products,
        ])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
