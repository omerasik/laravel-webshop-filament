<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    // sla een productreview
    public function store(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:255'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        Review::create([
            'product_id' => $product->id,
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
            'is_approved' => true,
        ]);

        return back()->with('success', 'Bedankt voor je review!');
    }
}
