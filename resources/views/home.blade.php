{{-- Homepage met hero en nieuwsbriefkaart --}}
<x-layout
    title="Welkom"
    meta-description="Webshop Omerasik brengt je een zorgvuldig samengestelde collectie huidverzorgingsproducten, cadeausets en wellness tips."
>
    <div class="home-hero">
        <h1 class="home-hero__title">Verzorg je huid met vertrouwen</h1>
        <p class="home-hero__intro">
            Kies voor Belgische slow beauty. Onze selectie bestaat uit zachte reinigers, serums en bodyproducten
            die je dagelijks ritueel eenvoudig en effectief maken.
        </p>
        <a href="{{ route('products.index') }}" class="btn-primary">
            Bekijk producten
        </a>
    </div>

    <div class="home-grid">
        {{-- Waarom-sectie met voordelen --}}
        <section class="detail-box">
            <h2 class="section-subtitle">Waarom klanten voor ons kiezen</h2>
            <ul class="feature-list">
                <li><strong>Natuurlijke formules:</strong> duizenden tevreden klanten met een gevoelige huid.</li>
                <li><strong>Snel geleverd:</strong> gratis verzending vanaf EUR 75 en binnen 48u verzonden.</li>
                <li><strong>Advies op maat:</strong> ontvang maandelijkse tips van ons schoonheidsteam.</li>
            </ul>
        </section>

        {{-- Nieuwsbriefkaart voor leads --}}
        <section class="detail-box newsletter-card">
            <h2 class="section-subtitle">Ontvang de wekelijkse nieuwsbrief</h2>
            <p class="newsletter-card__intro">
                Elke vrijdag sturen we een korte mail met verzorgingstips, nieuwe producten en exclusieve acties.
            </p>
            <form method="POST" action="{{ route('newsletter.subscribe') }}" class="newsletter-form">
                @csrf
                <label class="newsletter-input">
                    <span class="sr-only">E-mailadres</span>
                    <input type="email" name="email" placeholder="jij@example.com" required>
                    <button type="submit">Inschrijven</button>
                </label>
            </form>
        </section>
    </div>
</x-layout>
