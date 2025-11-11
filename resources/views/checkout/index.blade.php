{{-- Checkout formulier en orderoverzicht --}}
<x-layout title="Afrekenen" meta-description="Vul je gegevens in en rond de bestelling veilig af bij Webshop Omerasik.">
    <div class="checkout-page">
        {{-- Formulier voor klantgegevens --}}
        <section class="checkout-form">
            <h1 class="heading-xl">Afrekenen</h1>
            <form method="POST" action="{{ route('checkout.store') }}" class="checkout-form__grid">
                @csrf
                <div class="form-field">
                    <label class="form-label" for="first_name">Voornaam</label>
                    <input class="form-input" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                </div>
                <div class="form-field">
                    <label class="form-label" for="last_name">Achternaam</label>
                    <input class="form-input" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                </div>
                <div class="form-field">
                    <label class="form-label" for="email">E-mail</label>
                    <input class="form-input" id="email" name="email" type="email" value="{{ old('email') }}" required>
                </div>
                <div class="form-field">
                    <label class="form-label" for="phone">Telefoon</label>
                    <input class="form-input" id="phone" name="phone" value="{{ old('phone') }}">
                </div>
                <div class="form-field form-field--wide">
                    <label class="form-label" for="street">Straat en huisnummer</label>
                    <input class="form-input" id="street" name="street" value="{{ old('street') }}" required>
                </div>
                <div class="form-field">
                    <label class="form-label" for="bus">Bus</label>
                    <input class="form-input" id="bus" name="bus" value="{{ old('bus') }}">
                </div>
                <div class="form-field">
                    <label class="form-label" for="zip">Postcode</label>
                    <input class="form-input" id="zip" name="zip" value="{{ old('zip') }}" required>
                </div>
                <div class="form-field">
                    <label class="form-label" for="city">Stad</label>
                    <input class="form-input" id="city" name="city" value="{{ old('city') }}" required>
                </div>
                <div class="form-field form-field--wide">
                    <label class="form-label" for="payment_method">Betaalmethode</label>
                    <select class="form-input" id="payment_method" name="payment_method" required>
                        <option value="card" @selected(old('payment_method') === 'card')>Kredietkaart</option>
                        <option value="bancontact" @selected(old('payment_method') === 'bancontact')>Bancontact</option>
                        <option value="ideal" @selected(old('payment_method') === 'ideal')>iDEAL</option>
                    </select>
                    @if ($supportsOnlinePayment)
                        <p class="form-help">Je betaalt veilig via Stripe met Bancontact, iDEAL of kredietkaart.</p>
                    @else
                        <p class="form-help">Online betaling staat uit in deze omgeving. We verwerken je bestelling handmatig.</p>
                    @endif
                </div>
                <button class="btn-primary form-submit" type="submit">Bestelling plaatsen</button>
            </form>
        </section>

        {{-- Orderoverzicht met totalen --}}
        <aside class="checkout-summary">
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
            <p class="summary-note">Na het plaatsen van de bestelling ontvang je een bevestigingsmail.</p>
            @if ($supportsOnlinePayment)
                <p class="summary-note">Je wordt na het verzenden automatisch doorgestuurd naar de betaalpagina.</p>
            @endif
        </aside>
    </div>
</x-layout>

