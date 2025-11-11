<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    // toon het winkelmandoverzicht voor de klant
    public function index(): View
    {
        return view('cart.index', [
            'summary' => $this->cartService->summary(),
        ]);
    }

    // voeg een nieuw product aan de mand toe via het formulier
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        $product = Product::findOrFail($data['product_id']);
        $this->cartService->add($product, $data['quantity'] ?? 1);

        return back()->with('success', sprintf('%s is toegevoegd aan je winkelmand.', $product->name));
    }

    // werk de hoeveelheid van een bestaand product bij
    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:10'],
        ]);

        $this->cartService->update($product, $data['quantity']);

        return back()->with('success', sprintf('Aantal voor %s is bijgewerkt.', $product->name));
    }

    // verwijder een product volledig uit de mand
    public function destroy(Product $product): RedirectResponse
    {
        $this->cartService->remove($product);

        return back()->with('success', sprintf('%s is verwijderd uit je winkelmand.', $product->name));
    }

    // Leeg de volledige mand inclusief cookie
    public function clear(): RedirectResponse
    {
        $this->cartService->clear();

        return back()->with('success', 'Winkelmand is geleegd.');
    }
}
