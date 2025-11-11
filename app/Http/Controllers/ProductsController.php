<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductsController extends Controller
{
    // toon het productoverzicht met filters en paginering
    public function index(Request $request): View
    {
        $request->merge([
            'min_price' => $request->filled('min_price')
                ? str_replace(',', '.', $request->input('min_price'))
                : null,
            'max_price' => $request->filled('max_price')
                ? str_replace(',', '.', $request->input('max_price'))
                : null,
        ]);

        $filters = $request->validate(
            [
                'search' => ['nullable', 'string', 'max:120'],
                'category' => ['nullable', 'integer', 'exists:categories,id'],
                'tag' => ['nullable', 'integer', 'exists:tags,id'],
                'min_price' => ['nullable', 'numeric', 'min:0'],
                'max_price' => ['nullable', 'numeric', 'min:0'],
            ],
            [],
            [
                'search' => 'zoekterm',
                'category' => 'categorie',
                'tag' => 'tag',
                'min_price' => 'minimumprijs',
                'max_price' => 'maximumprijs',
            ],
        );

        $filters = array_merge([
            'search' => null,
            'category' => null,
            'tag' => null,
            'min_price' => null,
            'max_price' => null,
        ], $filters);

        $products = Product::with(['brand', 'category', 'tags'])
            ->when(
                $filters['search'],
                fn ($query, string $search) => $query->where(function ($nested) use ($search) {
                    $nested
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                }),
            )
            ->when(
                $filters['category'],
                fn ($query, int $categoryId) => $query->where('category_id', $categoryId),
            )
            ->when(
                $filters['tag'],
                fn ($query, int $tagId) => $query->whereHas(
                    'tags',
                    fn ($tagQuery) => $tagQuery->where('tags.id', $tagId),
                ),
            )
            ->when(
                $filters['min_price'],
                fn ($query, float $minPrice) => $query->where('price', '>=', $minPrice),
            )
            ->when(
                $filters['max_price'],
                fn ($query, float $maxPrice) => $query->where('price', '<=', $maxPrice),
            )
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('products.index', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
            'filters' => $filters,
        ]);
    }

    // laat een detailpagina met reviews en info zien
    public function show(Product $product): View
    {
        $product->load([
            'brand',
            'category',
            'tags',
            'reviews' => fn ($query) => $query
                ->where('is_approved', true)
                ->latest(),
        ]);

        return view('products.show', [
            'product' => $product,
            'reviews' => $product->reviews,
        ]);
    }
}
