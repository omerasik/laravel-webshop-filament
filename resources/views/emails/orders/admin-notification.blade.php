<p>Nieuwe bestelling binnen:</p>

<p><strong>Ordernummer:</strong> {{ $order->id }}</p>
<p><strong>Klant:</strong> {{ $order->user->email }}</p>

<ul>
    @foreach ($order->items as $item)
        <li>{{ $item->product->name }} x {{ $item->quantity }}</li>
    @endforeach
</ul>

<p>Totaal bedrag: &euro; {{ number_format($order->price, 2, ',', '.') }}</p>
