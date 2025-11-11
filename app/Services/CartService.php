<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;

class CartService
{
    private const COOKIE_KEY = 'cart_items';
    private const LIFETIME_MINUTES = 60 * 24 * 7; // cookies bewaren we 1 week

    public function items(): Collection
    {
        // Lees items uit de cookie en vul ze aan met actuele productinfo
        $rawItems = $this->decodeCookie();

        if (empty($rawItems)) {
            return collect();
        }

        // Haal alle producten op die in de cookie voorkomen
        $products = Product::whereIn('id', array_keys($rawItems))
            ->get()
            ->keyBy('id');

        $validItems = array_filter(
            $rawItems,
            fn ($quantity) => is_numeric($quantity) && (int) $quantity > 0
        );

        $normalizedItems = [];

        // Loop door alles en herbereken hoeveel er uiteindelijk mee mag
        $items = collect($validItems)->map(function (int $quantity, int $productId) use ($products, &$normalizedItems) {
            $product = $products->get($productId);

            if (! $product) {
                return null;
            }

            $sanitized = $this->sanitizeQuantity($product, $quantity);

            if ($sanitized === 0) {
                return null;
            }

            $normalizedItems[$productId] = $sanitized;

            $lineTotal = $product->price * $sanitized;

            return [
                'product' => $product,
                'quantity' => $sanitized,
                'line_total' => $lineTotal,
            ];
        })->filter()->values();

        if ($normalizedItems !== $validItems) {
            $this->store($normalizedItems); // schrijf opgeschoonde items terug
        }

        return $items;
    }

    public function summary(): array
    {
        // Bereken de tussenstand, btw en verzendkosten voor de checkout
        $items = $this->items();
        $subtotal = (float) $items->sum('line_total');
        $tax = round($subtotal * 0.21, 2);
        $shipping = $subtotal >= 75 ? 0.0 : 5.95;
        $total = $subtotal + $tax + $shipping; // totale prijs met btw + verzending

        return [
            'items' => $items,
            'count' => (int) $items->sum('quantity'),
            'subtotal' => round($subtotal, 2),
            'tax' => $tax,
            'shipping' => round($shipping, 2),
            'total' => round($total, 2),
        ];
    }

    public function add(Product $product, int $quantity = 1): void
    {
        // Voeg een product toe door op te tellen bij het huidige aantal
        $rawItems = $this->decodeCookie();
        $current = $rawItems[$product->id] ?? 0;
        $desired = $current + max(1, $quantity); // optellen bij bestaand aantal

        $sanitized = $this->sanitizeQuantity($product, $desired);

        if ($sanitized === 0) {
            unset($rawItems[$product->id]);
        } else {
            $rawItems[$product->id] = $sanitized;
        }

        $this->store($rawItems);
    }

    public function update(Product $product, int $quantity): void
    {
        // Werk een bestaand aantal bij of verwijder wanneer het nul wordt
        $rawItems = $this->decodeCookie();

        if ($quantity <= 0) {
            unset($rawItems[$product->id]);
        } else {
            $rawItems[$product->id] = $this->sanitizeQuantity($product, $quantity);

            if ($rawItems[$product->id] === 0) {
                unset($rawItems[$product->id]);
            }
        }

        $this->store($rawItems);
    }

    public function remove(Product $product): void
    {
        // Haal een product volledig uit de cookie
        $rawItems = $this->decodeCookie();
        unset($rawItems[$product->id]);

        $this->store($rawItems);
    }

    public function clear(): void
    {
        // Leeg de winkelmand door de cookie te vergeten
        Cookie::queue(Cookie::forget(self::COOKIE_KEY));
    }

    private function sanitizeQuantity(Product $product, int $quantity): int
    {
        // Knip bij tussen 1 en de maximaal toegestane voorraad
        $maxQuantity = $this->maxQuantity($product);

        if ($maxQuantity <= 0) {
            return 0;
        }

        return max(1, min($quantity, $maxQuantity));
    }

    private function maxQuantity(Product $product): int
    {
        // Bepaal een veilig maximum per product (voorraad + limiet 10)
        $stock = is_numeric($product->stock) ? (int) $product->stock : 10;

        if ($stock <= 0) {
            return 0;
        }

        return min(10, $stock);
    }

    private function decodeCookie(): array
    {
        // Lees de JSON cookie in en filter alles wat geen geldig aantal is
        $raw = request()->cookie(self::COOKIE_KEY);

        if (! $raw) {
            return [];
        }

        $decoded = json_decode($raw, true) ?? [];

        return array_filter($decoded, fn ($quantity) => is_numeric($quantity) && (int) $quantity > 0);
    }

    private function store(array $payload): void
    {
        // Schrijf enkel geldige items terug leeg anders de cookie
        $validItems = array_filter($payload, fn ($quantity) => is_numeric($quantity) && (int) $quantity > 0);

        if (empty($validItems)) {
            $this->clear();

            return;
        }

        Cookie::queue(
            self::COOKIE_KEY,
            json_encode($validItems),
            self::LIFETIME_MINUTES
        );
    }
}
