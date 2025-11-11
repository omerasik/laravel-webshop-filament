{{-- Favorietenoverzicht voor bezoekers --}}
@php
    use Illuminate\Support\Str;
@endphp

<x-layout title="Favorieten" meta-description="Overzicht van je opgeslagen producten bij Webshop Omerasik.">
    <div class="catalog-header">
        <div>
            <p class="eyebrow">Overzicht</p>
            <h1 class="heading-xl">Favorieten</h1>
        </div>
        <p class="catalog-header__count">{{ $products->count() }} opgeslagen producten</p>
    </div>

    @if ($products->isEmpty())
        <p>Je hebt nog geen producten opgeslagen. Bekijk <a href="{{ route('products.index') }}">onze catalogus</a> en klik op het hartje om items bij te houden.</p>
    @else
        <div class="products-grid">
            @foreach ($products as $product)
                <article class="product-card">
                    @if ($product->image_url)
                        <div class="product-card__image-wrapper">
                            <img src="{{ $product->image_url }}" alt="Foto van {{ $product->name }}" class="product-card__image" loading="lazy">
                        </div>
                    @endif
                    <p class="product-card__brand">{{ $product->brand?->name ?? 'Onbekend merk' }}</p>
                    <a href="{{ route('products.show', $product) }}" class="product-card__link">{{ $product->name }}</a>
                    <p class="product-card__description">{{ Str::limit($product->description, 90) }}</p>
                    <div class="product-card__footer">
                        <span class="product-card__price">&euro; {{ number_format($product->price, 2, ',', '.') }}</span>
                        <form method="POST" action="{{ route('favorites.store', $product) }}">
                            @csrf
                            <button class="btn-secondary" type="submit">Verwijder</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</x-layout>

