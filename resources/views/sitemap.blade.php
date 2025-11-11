<?xml version="1.0" encoding="UTF-8"?>
{{-- Sitemap met vaste pagina's en recente producten --}}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($pages as $page)
    <url>
        <loc>{{ $page }}</loc>
    </url>
@endforeach
@foreach ($products as $product)
    <url>
        <loc>{{ route('products.show', $product->id) }}</loc>
        @if ($product->updated_at)
        <lastmod>{{ $product->updated_at->toDateString() }}</lastmod>
        @endif
    </url>
@endforeach
</urlset>

