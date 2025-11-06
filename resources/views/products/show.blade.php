<x-layout :title="$product->name">
    <div class="product-detail">
        <section>
            <h1 class="product-detail__title">
                {{ $product->name }}
            </h1>
            <p class="product-detail__meta">{{ $product->brand?->name }} · {{ $product->category?->name }}</p>
            <p class="product-detail__price">&euro; {{ number_format($product->price, 2, ',', '.') }}</p>
            <div class="product-detail__description">
                {!! nl2br(e($product->description)) !!}
            </div>
        </section>
        @if ($product->tags->isNotEmpty())
            <aside>
                <h2 class="section-subtitle">Tags</h2>
                <ul class="tag-pills">
                    @foreach ($product->tags as $tag)
                        <li class="tag-pill">#{{ $tag->name }}</li>
                    @endforeach
                </ul>
            </aside>
        @endif
    </div>
</x-layout>
