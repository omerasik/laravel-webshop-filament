@php
    use Illuminate\Support\Str;

    $metaDescription = Str::limit(strip_tags($product->description), 155);
    $schema = [
        '@context' => 'https://schema.org/',
        '@type' => 'Product',
        'name' => $product->name,
        'description' => strip_tags($product->description),
        'image' => $product->image_url,
        'brand' => [
            '@type' => 'Brand',
            'name' => $product->brand?->name ?? 'Webshop Omerasik',
        ],
        'aggregateRating' => $product->average_rating ? [
            '@type' => 'AggregateRating',
            'ratingValue' => $product->average_rating,
            'reviewCount' => $product->reviews_count,
        ] : null,
        'offers' => [
            '@type' => 'Offer',
            'priceCurrency' => 'EUR',
            'price' => number_format($product->price, 2, '.', ''),
            'availability' => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'url' => route('products.show', $product),
        ],
    ];
    $schema = array_filter($schema);

    $favoriteService = app(\App\Services\FavoriteService::class);
    $isFavorite = $favoriteService->contains($product);
@endphp

{{-- Product detailpagina met schema en reviews --}}
<x-layout
    :title="$product->name"
    :meta-description="$metaDescription"
    :meta-image="$product->image_url"
    :schema="$schema"
>
    <div class="product-detail">
        <section>
            {{-- Mediasectie en favorieten knop --}}
            <div class="product-detail__actions">
                @if ($product->image_url)
                    <div class="product-detail__image">
                        <img
                            src="{{ $product->image_url }}"
                            alt="Foto van {{ $product->name }}"
                            loading="lazy"
                        />
                    </div>
                @endif
                <form method="POST" action="{{ route('favorites.store', $product) }}" class="favorite-toggle">
                    @csrf
                    <button type="submit" class="btn-secondary">
                        {{ $isFavorite ? 'Verwijder uit favorieten' : 'Bewaar als favoriet' }}
                    </button>
                </form>
            </div>
            <h1 class="product-detail__title">
                {{ $product->name }}
            </h1>
            <p class="product-detail__meta">
                {{ $product->brand?->name }}
                <span aria-hidden="true">&middot;</span>
                {{ $product->category?->name }}
            </p>
            @if ($product->average_rating)
                <p class="product-detail__rating">
                    * {{ number_format($product->average_rating, 1) }}
                    <span class="rating-count">({{ $product->reviews_count }} reviews)</span>
                </p>
            @endif
            <p class="product-detail__price">&euro; {{ number_format($product->price, 2, ',', '.') }}</p>
            <div class="product-detail__description">
                {!! nl2br(e($product->description)) !!}
            </div>

            <div class="add-to-cart">
                @if ($product->stock > 0)
                    <form method="POST" action="{{ route('cart.store') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="add-to-cart__controls">
                            <label class="add-to-cart__quantity">
                                <span class="form-label">Aantal</span>
                                <input class="form-input" type="number" name="quantity" min="1" max="10" value="1">
                            </label>
                            <button class="btn-primary" type="submit">Voeg toe aan mand</button>
                        </div>
                    </form>
                @else
                    <p class="summary-note">Dit product is tijdelijk uitverkocht.</p>
                @endif
            </div>
        </section>
        <aside>
            @if ($product->tags->isNotEmpty())
                <h2 class="section-subtitle">Tags</h2>
                <ul class="tag-pills">
                    @foreach ($product->tags as $tag)
                        <li class="tag-pill">#{{ $tag->name }}</li>
                    @endforeach
                </ul>
            @endif

            {{-- Reviews en formulier --}}
            <section class="detail-box">
                <h2 class="section-subtitle">Reviews</h2>
                @if ($reviews->isEmpty())
                    <p>Wees de eerste om een review te schrijven.</p>
                @else
                    <ul class="review-list">
                        @foreach ($reviews as $review)
                            <li class="review-item">
                                <div class="review-item__header">
                                    <strong>{{ $review->name }}</strong>
                                    <span class="review-rating">{{ str_repeat('*', $review->rating) }}</span>
                                </div>
                                @if ($review->comment)
                                    <p class="review-item__comment">{{ $review->comment }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
                {{-- Formulier om nieuwe review achter te laten --}}
                <form method="POST" action="{{ route('products.reviews.store', $product) }}" class="review-form">
                    @csrf
                    <h3 class="section-subtitle">Schrijf een review</h3>
                    <label class="form-field">
                        <span class="form-label">Naam</span>
                        <input type="text" name="name" class="form-input" required value="{{ old('name') }}">
                    </label>
                    <label class="form-field">
                        <span class="form-label">E-mail (optioneel)</span>
                        <input type="email" name="email" class="form-input" value="{{ old('email') }}">
                    </label>
                    <label class="form-field">
                        <span class="form-label">Rating</span>
                        <select name="rating" class="form-input" required>
                            <option value="">Kies een score</option>
                            @for ($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" @selected(old('rating') == $i)>{{ $i }} - {{ str_repeat('*', $i) }}</option>
                            @endfor
                        </select>
                    </label>
                    <label class="form-field">
                        <span class="form-label">Opmerking</span>
                        <textarea name="comment" rows="3" class="form-input">{{ old('comment') }}</textarea>
                    </label>
                    <button type="submit" class="btn-secondary">Review verzenden</button>
                </form>
            </section>
        </aside>
    </div>
</x-layout>
