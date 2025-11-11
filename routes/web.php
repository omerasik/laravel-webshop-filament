<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterSubscriptionController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// Homepage
Route::get('/', HomeController::class)->name('home');

// Productcatalogus
Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductsController::class, 'show'])->name('products.show');
Route::post('/products/{product}/reviews', [ProductReviewController::class, 'store'])->name('products.reviews.store');

// Winkelmand
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

// Checkout en bedankpagina
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/bedankt/{order}', [CheckoutController::class, 'thankYou'])->name('checkout.thank-you');

// Nieuwsbriefinschrijving
Route::post('/newsletter', [NewsletterSubscriptionController::class, 'store'])->name('newsletter.subscribe');

// Sitemap voor SEO
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

// Favorieten
Route::get('/favorieten', [FavoritesController::class, 'index'])->name('favorites.index');
Route::post('/favorieten/{product}', [FavoritesController::class, 'store'])->name('favorites.store');
