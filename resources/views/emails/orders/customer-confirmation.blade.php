<p>Beste {{ $order->user->name }},</p>

<p>Bedankt voor je bestelling bij Webshop Omerasik. Hieronder vind je een overzicht van je order:</p>

<ul>
    @foreach ($order->items as $item)
        <li>{{ $item->product->name }} x {{ $item->quantity }} - &euro; {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</li>
    @endforeach
</ul>

<p>Totaal: &euro; {{ number_format($order->price, 2, ',', '.') }}</p>

<p>We houden je op de hoogte van de verzending.</p>

<p>Met vriendelijke groeten,<br>Webshop Omerasik</p>
