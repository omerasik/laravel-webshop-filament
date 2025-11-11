<?php

namespace App\Services;

use App\Mail\OrderAdminNotificationMail;
use App\Mail\OrderCustomerConfirmationMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class CheckoutService
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    /**
     * @return array{order: Order, redirect: ?string, requires_payment: bool}
     */
    public function process(array $data): array
    {
        
        $summary = $this->cartService->summary();

        if ($summary['count'] === 0) {
            throw new RuntimeException('Je winkelmand is leeg.');
        }

        // Bouw bestelling + gebruiker binnen een transactie op
        $result = DB::transaction(function () use ($data, $summary): array {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => trim($data['first_name'] . ' ' . $data['last_name']),
                    'password' => bcrypt('password'),
                ]
            );

            $user->fill([
                'name' => trim($data['first_name'] . ' ' . $data['last_name']),
                'phone' => $data['phone'] !== '' ? $data['phone'] : null,
                'address' => $data['street'],
                'zipcode' => $data['zip'],
                'bus' => filled($data['bus'] ?? null) ? $data['bus'] : null,
            ]);

            if ($user->isDirty()) {
                $user->save();
            }

            $order = Order::create([
                'user_id' => $user->id,
                'price' => $summary['total'],
                'order_status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $data['payment_method'],
                'shipping_address' => $this->buildShippingAddress($data),
            ]);

            // Sla alle bestelregels op
            foreach ($summary['items'] as $item) {
                /** @var Product $product */
                $product = $item['product'];
                $quantity = $item['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);
            }

            $stripeKey = config('services.stripe.secret');
            $redirect = null;
            $requiresPayment = false;

            if ($stripeKey) {
                try {
                    // klant moet betalen via Stripe
                    $redirect = $this->createStripeSession($order, $summary, $stripeKey);

                    if (! $redirect) {
                        throw new RuntimeException('We konden geen betaalsessie opstarten.');
                    }

                    $requiresPayment = true;
                } catch (ApiErrorException $exception) {
                    report($exception);

                    throw new RuntimeException('Het lukt ons niet om Stripe te starten. Probeer later opnieuw.');
                }
            }

            if (! $requiresPayment) {
                $order->update([
                    'payment_status' => 'paid',
                    'order_status' => 'processing',
                ]);
            }

            return [
                'order' => $order->fresh(['items.product', 'user']),
                'redirect' => $redirect,
                'requires_payment' => $requiresPayment,
            ];
        });

        /** @var Order $order */
        $order = $result['order'];
        $requiresPayment = $result['requires_payment'];
        $redirect = $result['redirect'];

        Mail::to($order->user->email)->send(new OrderCustomerConfirmationMail($order)); // klant krijgt bevestiging

        if ($admin = config('mail.from.address')) {
            Mail::to($admin)->send(new OrderAdminNotificationMail($order));
        }

        // Winkelmand is afgehandeld en kan geleegd worden
        $this->cartService->clear();

        return [
            'order' => $order,
            'redirect' => $redirect,
            'requires_payment' => $requiresPayment,
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function buildShippingAddress(array $data): string
    {
        $streetLine = $data['street']; // straat en bus combineren

        if (filled($data['bus'] ?? null)) {
            $streetLine .= ' ' . trim((string) $data['bus']);
        }

        return trim($streetLine . ', ' . $data['zip'] . ' ' . $data['city']);
    }

    private function createStripeSession(Order $order, array $summary, string $secret): ?string
    {
        $client = new StripeClient($secret);

        $lineItems = $order->items()->with('product')->get()->map(function (OrderItem $item) {
            return [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item->product->name,
                    ],
                    'unit_amount' => (int) round($item->price * 100),
                ],
                'quantity' => $item->quantity,
            ];
        })->toArray();

        if (empty($lineItems)) {
            return null;
        }

        $successUrl = route('checkout.thank-you', ['order' => $order->id]) . '?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = config('services.stripe.cancel_url', route('checkout.index'));
        $session = $client->checkout->sessions->create([
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'line_items' => $lineItems,
            'customer_email' => $order->user->email,
            'payment_method_types' => ['card', 'bancontact', 'ideal'],
            'metadata' => [
                'order_id' => $order->id,
            ],
        ]);

        return $session->url ?? null;
    }
}
