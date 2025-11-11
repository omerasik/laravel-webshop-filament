<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use RuntimeException;
use Stripe\StripeClient;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly CheckoutService $checkoutService
    ) {
    }

    // toon checkout of verwijs terug als  mand leeg is
    public function index(): View|RedirectResponse
    {
        $summary = $this->cartService->summary();

        if ($summary['count'] === 0) {
            return redirect()->route('products.index')->with('error', 'Je winkelmand is leeg.');
        }

        return view('checkout.index', [
            'summary' => $summary,
            'supportsOnlinePayment' => filled(config('services.stripe.secret')),
        ]);
    }

    //checkout formulier en start de betaling
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string', 'max:40'],
            'street' => ['required', 'string', 'max:255'],
            'bus' => ['nullable', 'string', 'max:20'],
            'zip' => ['required', 'string', 'max:12'],
            'city' => ['required', 'string', 'max:120'],
            'payment_method' => ['required', 'in:card,bancontact,ideal'],
        ]);

        try {
            $result = $this->checkoutService->process($data);
        } catch (RuntimeException $exception) {
            return back()
                ->withInput()
                ->withErrors(['payment' => $exception->getMessage()]);
        } catch (\Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->withErrors(['payment' => 'Er ging iets mis bij het verwerken van je bestelling.']);
        }

        if (! empty($result['requires_payment']) && ! empty($result['redirect'])) {
            return redirect()->away($result['redirect']);
        }

        return redirect()->route('checkout.thank-you', ['order' => $result['order']->id]);
    }

    // laat de  bedankpagina zien 
    public function thankYou(Request $request, Order $order): View
    {
        $order->load(['items.product', 'user']);

        if ($request->filled('session_id') && $order->payment_status !== 'paid') {
            $stripeKey = config('services.stripe.secret');

            if ($stripeKey) {
                try {
                    $client = new StripeClient($stripeKey);
                    $session = $client->checkout->sessions->retrieve($request->string('session_id'));

                    if ($session && $session->payment_status === 'paid') {
                        $order->update([
                            'payment_status' => 'paid',
                            'order_status' => 'processing',
                        ]);
                    }
                } catch (\Throwable $exception) {
                    Log::warning('Stripe sessie kon niet worden gecontroleerd', ['error' => $exception->getMessage()]);
                }
            }
        }

        return view('checkout.thank-you', [
            'order' => $order,
        ]);
    }
}
