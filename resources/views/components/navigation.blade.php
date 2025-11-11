@php
    $cartSummary = app(\App\Services\CartService::class)->summary();
    $favoritesCount = app(\App\Services\FavoriteService::class)->count();
@endphp

{{-- Navigatie met badges voor favorieten en winkelmand --}}
<nav class="navigation">
    <a href="{{ route('home') }}" class="navigation__brand">
        Webshop Omerasik
    </a>
    <div class="navigation__links">
        <a href="{{ route('products.index') }}" class="navigation__link">Producten</a>
        <a href="{{ route('favorites.index') }}" class="navigation__link navigation__cart">
            Favorieten
            @if ($favoritesCount > 0)
                <span class="navigation__badge">{{ $favoritesCount }}</span>
            @endif
        </a>
        <a href="{{ route('cart.index') }}" class="navigation__link navigation__cart">
            Winkelmand
            @if ($cartSummary['count'] > 0)
                <span class="navigation__badge">{{ $cartSummary['count'] }}</span>
            @endif
        </a>
    </div>
</nav>
