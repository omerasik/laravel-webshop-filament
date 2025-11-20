# Omerasik Webshop

Skin-care focused e-commerce showcase built with Laravel 12. Includes a filterable product catalog, favorites, cart and checkout flows, newsletter signups, and seeded demo data so you can explore immediately.

## Highlights
- Catalog: search, category/brand/tag filters, min-max price range, 12-per-page pagination, active-filter badges.
- Product detail: schema.org Product metadata, average rating and reviews, favorite toggle, cart with stock and 10-item cap.
- Favorites and cart: cookie-based storage; VAT 21% + EUR 5.95 shipping (free from EUR 75); automatic cleanup.
- Checkout: shipping form creates or finds user by email, sends customer and admin emails, optional Stripe Checkout session (when `services.stripe.secret` is set).
- Newsletter and SEO: subscriptions saved to `storage/app/newsletter/subscribers.json`, welcome email, dynamic `/sitemap.xml`.
- Seed data: 40 products, 12 brands, 11 categories, 15 tags, sample reviews; login: `admin@webshop.test` / `password`.

## Stack
- PHP 8.2, Laravel 12, Breeze
- SQLite by default (queue, session, cache on database)
- Vite, Tailwind, Alpine
- Mailpit for dev mail (MAIL_MAILER smtp, host mailpit)

## Requirements
- PHP 8.2+ with ext-json, ext-fileinfo, ext-xml
- Composer
- Node.js 18+ and npm
- SQLite (or another DB if configured)
- Optional: Mailpit or your SMTP, Stripe secret key

## Install from scratch
```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed   # seeds demo user and 40 products
npm install
npm run dev                  # or: composer run dev for server+queue+vite
# if you need a separate PHP server:
php artisan serve
```
> Using SQLite? Ensure `database/database.sqlite` exists (an empty file is fine).

## Testing
```bash
php artisan test
```

## Key routes
- `/` home + newsletter form
- `/products` catalog and filters
- `/products/{id}` product detail + review form
- `/favorieten` favorites
- `/cart` cart, `/checkout` payment, `/checkout/bedankt/{order}` thank-you page
- `/sitemap.xml` SEO sitemap
- `/dashboard` Breeze dashboard (login required)

Author: Omer Asik
