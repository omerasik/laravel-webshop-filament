<x-layout title="Welkom">
    <div class="home-hero">
        <h1 class="home-hero__title">Welkom bij Webshop Omerasik</h1>
        <p class="home-hero__intro">
            Dit is onze Laravel oefenwinkel. Je vindt hier een selectie van producten
            die we gebruiken om de bredere webshopfunctionaliteiten stap voor stap te bouwen.
        </p>
        <a href="{{ route('products.index') }}" class="btn-primary">
            Bekijk producten
        </a>
    </div>
</x-layout>
