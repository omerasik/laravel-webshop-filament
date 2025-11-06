<x-layout title="Producten">
    <h1 class="heading-xl">Producten</h1>

    <div class="products-grid">
        @forelse ($products as $product)
            <article class="product-card">
                <p class="product-card__brand">
                    {{ $product->brand?->name ?? 'Onbekend merk' }}
                </p>
                <a href="{{ route('products.show', $product) }}" class="product-card__link">
                    {{ $product->name }}
                </a>
                <p class="product-card__description">
                    {{ Str::limit($product->description, 90) }}
                </p>
                <span class="product-card__price">&euro; {{ number_format($product->price, 2, ',', '.') }}</span>
            </article>
        @empty
            <p>Geen producten gevonden.</p>
        @endforelse
    </div>

    <div class="pagination">
        {{ $products->links() }}
    </div>
</x-layout>
