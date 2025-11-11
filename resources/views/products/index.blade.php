@php
    use Illuminate\Support\Str;
    $favoriteIds = array_flip(app(\App\Services\FavoriteService::class)->ids());
@endphp
{{-- Product overzichtspagina met filters en kaarten --}}
<x-layout
    title="Producten"
    meta-description="Blader door al onze huidverzorgingsproducten, filtreer op categorie, tags of prijs en vind snel jouw nieuwe routine."
>
    <div class="catalog-header">
        <div>
            <p class="eyebrow">Overzicht</p>
            <h1 class="heading-xl">Producten</h1>
        </div>
        <p class="catalog-header__count">
            {{ $products->total() }} {{ $products->total() === 1 ? 'product' : 'producten' }} gevonden
        </p>
    </div>

    @if ($errors->any())
        <div class="alert alert--error">
            <p class="alert__title">Er ging iets mis met de filters:</p>
            <ul class="alert__list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="catalog-layout">
        {{-- Filterkolom met zoek- en prijsvelden --}}
        <aside class="product-filters">
            <form method="GET" class="product-filters__form">
                <div class="form-field">
                    <label for="search" class="form-label">Zoekterm</label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        class="form-input"
                        placeholder="Zoek op naam of beschrijving"
                        value="{{ old('search', $filters['search']) }}"
                    />
                </div>

                <div class="form-field">
                    <label for="category" class="form-label">Categorie</label>
                    <select id="category" name="category" class="form-select">
                        <option value="">Alle categorie&euml;n</option>
                        @foreach ($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                @selected((string) $category->id === (string) ($filters['category'] ?? ''))
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field">
                    <label for="brand" class="form-label">Merk</label>
                    <select id="brand" name="brand" class="form-select">
                        <option value="">Alle merken</option>
                        @foreach ($brands as $brand)
                            <option
                                value="{{ $brand->id }}"
                                @selected((string) $brand->id === (string) ($filters['brand'] ?? ''))
                            >
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field">
                    <label for="tag" class="form-label">Tag</label>
                    <select id="tag" name="tag" class="form-select">
                        <option value="">Alle tags</option>
                        @foreach ($tags as $tag)
                            <option
                                value="{{ $tag->id }}"
                                @selected((string) $tag->id === (string) ($filters['tag'] ?? ''))
                            >
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field">
                    <label class="form-label">Prijs</label>
                    <div class="price-range">
                        <input
                            type="number"
                            inputmode="decimal"
                            step="0.01"
                            min="0"
                            placeholder="van"
                            name="min_price"
                            class="form-input"
                            value="{{ old('min_price', $filters['min_price']) }}"
                        />
                        <span class="price-range__divider">-</span>
                        <input
                            type="number"
                            inputmode="decimal"
                            step="0.01"
                            min="0"
                            placeholder="tot"
                            name="max_price"
                            class="form-input"
                            value="{{ old('max_price', $filters['max_price']) }}"
                        />
                    </div>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-primary btn-block">
                        Toon resultaten
                    </button>
                    <a href="{{ route('products.index') }}" class="btn-secondary btn-block">
                        Reset filters
                    </a>
                </div>
            </form>
        </aside>

        {{-- Resultatenraster met kaarten en actieve filters --}}
        <section class="product-results">
            @php
                $activeFilters = [];
                if ($filters['search']) {
                    $activeFilters[] = 'Zoekterm: "' . e($filters['search']) . '"';
                }
                if ($filters['category']) {
                    $categoryName = optional($categories->firstWhere('id', $filters['category']))?->name;
                    if ($categoryName) {
                        $activeFilters[] = "Categorie: {$categoryName}";
                    }
                }
                if ($filters['brand']) {
                    $brandName = optional($brands->firstWhere('id', $filters['brand']))?->name;
                    if ($brandName) {
                        $activeFilters[] = "Merk: {$brandName}";
                    }
                }
                if ($filters['tag']) {
                    $tagName = optional($tags->firstWhere('id', $filters['tag']))?->name;
                    if ($tagName) {
                        $activeFilters[] = "Tag: {$tagName}";
                    }
                }
                if ($filters['min_price']) {
                    $activeFilters[] = 'Vanaf &euro; ' . number_format((float) $filters['min_price'], 2, ',', '.');
                }
                if ($filters['max_price']) {
                    $activeFilters[] = 'Tot &euro; ' . number_format((float) $filters['max_price'], 2, ',', '.');
                }
            @endphp

            @if ($activeFilters)
                <div class="active-filters">
                    <p class="active-filters__label">Actieve filters:</p>
                    <ul class="active-filters__list">
                        @foreach ($activeFilters as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Lijst met productkaarten --}}
            <div class="products-grid">
                @forelse ($products as $product)
                    <article class="product-card">
                        <div class="product-card__favorite">
                            <form method="POST" action="{{ route('favorites.store', $product) }}">
                                @csrf
                                <button type="submit" class="favorite-button {{ isset($favoriteIds[$product->id]) ? 'is-active' : '' }}">
                                    {{ isset($favoriteIds[$product->id]) ? 'Opgeslagen' : 'Bewaar' }}
                                </button>
                            </form>
                        </div>
                        @if ($product->image_url)
                            <div class="product-card__image-wrapper">
                                <img
                                    src="{{ $product->image_url }}"
                                    alt="Foto van {{ $product->name }}"
                                    class="product-card__image"
                                    loading="lazy"
                                />
                            </div>
                        @endif
                        <p class="product-card__brand">
                            {{ $product->brand?->name ?? 'Onbekend merk' }}
                        </p>
                        <a href="{{ route('products.show', $product) }}" class="product-card__link">
                            {{ $product->name }}
                        </a>
                        <p class="product-card__description">
                            {{ Str::limit($product->description, 90) }}
                        </p>
                        @if ($product->tags->isNotEmpty())
                            <ul class="tag-pills">
                                @foreach ($product->tags->take(3) as $tag)
                                    <li class="tag-pill tag-pill--small">#{{ $tag->name }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <div class="product-card__footer">
                            <span class="product-card__price">&euro; {{ number_format($product->price, 2, ',', '.') }}</span>
                            <form method="POST" action="{{ route('cart.store') }}" class="product-card__form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button class="btn-secondary" type="submit">Voeg toe</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <p>Geen producten gevonden met de huidige filters.</p>
                @endforelse
            </div>

            <div class="pagination">
                {{ $products->links('vendor.pagination.custom') }}
            </div>
        </section>
    </div>
</x-layout>



