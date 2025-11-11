<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\FavoriteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FavoritesController extends Controller
{
    public function __construct(private readonly FavoriteService $favorites)
    {
    }

    // toon overzicht met favoriete producten
    public function index(): View
    {
        return view('favorites.index', [
            'products' => $this->favorites->items(),
        ]);
    }

    // wissel de favoriet status van een product via de knop
    public function store(Product $product): RedirectResponse
    {
        $added = $this->favorites->toggle($product);

        return back()->with(
            'success',
            $added
                ? sprintf('%s is opgeslagen als favoriet.', $product->name)
                : sprintf('%s is verwijderd uit je favorieten.', $product->name)
        );
    }
}
