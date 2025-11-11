{{-- Winkelmand overzichtspagina --}}
<x-layout title="Winkelmand" meta-description="Bekijk je geselecteerde huidverzorgingsproducten en rond je order af.">
    <div class="cart-page">
        {{-- Lijst met alle producten in de mand --}}
        <section class="cart-items">
            <div class="cart-header">
                <h1 class="heading-xl">Je winkelmand</h1>
                @if ($summary['count'] > 0)
                    <form method="POST" action="{{ route('cart.clear') }}">
                        @csrf
                        @method('DELETE')
                        <button class="link-reset" type="submit">Winkelmand legen</button>
                    </form>
                @endif
            </div>

            @if ($summary['count'] === 0)
                <div class="cart-empty">
                    Je winkelmand is leeg. <a href="{{ route('products.index') }}">Start met shoppen</a>.
                </div>
            @else
                <ul class="cart-list">
                    @foreach ($summary['items'] as $item)
                        <li class="cart-item">
                            @if ($item['product']->image_url)
                                <img src="{{ $item['product']->image_url }}" alt="Foto van {{ $item['product']->name }}" class="cart-item__thumb" loading="lazy">
                            @endif
                            <div class="cart-item__info">
                                <h2>{{ $item['product']->name }}</h2>
                                <p>
                                    {{ $item['product']->brand?->name }}
                                    @if ($item['product']->brand)
                                        -
                                    @endif
                                    &euro; {{ number_format($item['product']->price, 2, ',', '.') }}
                                </p>
                            </div>
                            <div class="cart-item__actions">
                                <form method="POST" action="{{ route('cart.update', $item['product']) }}" class="cart-quantity">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" min="0" max="10" value="{{ $item['quantity'] }}">
                                    <button type="submit">Update</button>
                                </form>
                                <form method="POST" action="{{ route('cart.destroy', $item['product']) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="link-reset">Verwijder</button>
                                </form>
                                <div class="cart-item__total">&euro; {{ number_format($item['line_total'], 2, ',', '.') }}</div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        {{-- Samenvatting met totalen en CTA --}}
        <aside class="cart-summary">
            <h2 class="section-subtitle">Overzicht</h2>
            <dl class="summary-list">
                <div>
                    <dt>Producten</dt>
                    <dd>{{ $summary['count'] }}</dd>
                </div>
                <div>
                    <dt>Subtotaal</dt>
                    <dd>&euro; {{ number_format($summary['subtotal'], 2, ',', '.') }}</dd>
                </div>
                <div>
                    <dt>BTW (21%)</dt>
                    <dd>&euro; {{ number_format($summary['tax'], 2, ',', '.') }}</dd>
                </div>
                <div>
                    <dt>Verzending</dt>
                    <dd>
                        @if ($summary['shipping'] == 0)
                            <span class="badge">Gratis</span>
                        @else
                            &euro; {{ number_format($summary['shipping'], 2, ',', '.') }}
                        @endif
                    </dd>
                </div>
                <div class="summary-total">
                    <dt>Totaal</dt>
                    <dd>&euro; {{ number_format($summary['total'], 2, ',', '.') }}</dd>
                </div>
            </dl>
            <a class="btn-primary btn-block {{ $summary['count'] === 0 ? 'btn-disabled' : '' }}" href="{{ $summary['count'] === 0 ? '#' : route('checkout.index') }}">Ga naar afrekenen</a>
        </aside>
    </div>
</x-layout>

