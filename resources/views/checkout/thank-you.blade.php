{{-- Bedankpagina met orderdetails --}}
<x-layout title="Bedankt" meta-description="Je bestelling is geplaatst bij Webshop Omerasik. Bekijk het overzicht van je huidverzorgingsproducten.">
    <div class="thank-you">
        {{-- Samenvatting van de bestelling --}}
        <div class="thank-you__card">
            <h1 class="heading-xl">Bedankt voor je bestelling!</h1>
            <p>Ordernummer <strong>#{{ $order->id }}</strong></p>
            <p>We sturen een bevestiging naar <strong>{{ $order->user->email }}</strong>.</p>
            @if ($order->payment_status !== 'paid')
                <p class="summary-note">We wachten nog op je betaling. Rond de betaling af via de link in je mail of neem contact op met ons team.</p>
            @endif
        </div>

        {{-- Detailoverzicht van items --}}
        <section class="thank-you__details">
            <h2 class="section-subtitle">Bestelde items</h2>
            <ul class="thank-you__list">
                @foreach ($order->items as $item)
                    <li>
                        <span>{{ $item->product->name }}</span>
                        <span>{{ $item->quantity }} x &euro; {{ number_format($item->price, 2, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
            <p class="thank-you__total">Totaal: &euro; {{ number_format($order->price, 2, ',', '.') }}</p>
            <a class="btn-primary" href="{{ route('products.index') }}">Verder shoppen</a>
        </section>
    </div>
</x-layout>

