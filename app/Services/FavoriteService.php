<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;

class FavoriteService
{
    private const COOKIE_KEY = 'favorite_products';
    private const LIFETIME_MINUTES = 60 * 24 * 30; // favoriete producten 30 dagen onthouden

    public function ids(): array
    {
        // Haal de ruwe favorieten-cookie op en filter op ints
        $raw = request()->cookie(self::COOKIE_KEY);

        if (! $raw) {
            return [];
        }

        $decoded = json_decode($raw, true) ?? [];

        return array_values(
            array_unique(
                array_map('intval', array_filter($decoded, fn ($id) => is_numeric($id)))
            )
        );
    }

    public function items(): Collection
    {
        // Vraag alle producten op in dezelfde volgorde als de cookie
        $ids = $this->ids();

        if (empty($ids)) {
            return collect();
        }

        $ordered = implode(',', $ids);

        return Product::with(['brand', 'category', 'tags'])
            ->whereIn('id', $ids)
            ->orderByRaw('FIELD(id, ' . $ordered . ')')
            ->get();
    }

    public function add(Product $product): void
    {
        // Voeg een product toe als het nog niet voorkomt
        $ids = $this->ids();

        if (! in_array($product->id, $ids)) {
            $ids[] = $product->id;
        }

        $this->store($ids);
    }

    public function remove(Product $product): void
    {
        // Filter het product uit de lijst
        $ids = array_filter($this->ids(), fn ($id) => (int) $id !== $product->id);

        $this->store($ids);
    }

    public function toggle(Product $product): bool
    {
        // Wissel tussen toevoegen of verwijderen
        $ids = $this->ids();

        if (in_array($product->id, $ids)) {
            $this->remove($product);

            return false;
        }

        $this->add($product);

        return true;
    }

    public function contains(Product $product): bool
    {
        return in_array($product->id, $this->ids());
    }

    public function count(): int
    {
        return count($this->ids());
    }

    private function store(array $ids): void
    {
        // Sla de unieke set ids weer op in de cookie
        $unique = array_values(array_unique(array_map('intval', $ids)));

        if (empty($unique)) {
            Cookie::queue(Cookie::forget(self::COOKIE_KEY));

            return;
        }

        Cookie::queue(
            self::COOKIE_KEY,
            json_encode($unique),
            self::LIFETIME_MINUTES
        );
    }
}
