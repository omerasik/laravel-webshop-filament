<?php

namespace App\Http\Controllers;

use App\Services\NewsletterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterSubscriptionController extends Controller
{
    public function __construct(private readonly NewsletterService $newsletter)
    {
    }

    // Verwerk het formulier voor nieuwsbriefinschrijvingen
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $added = $this->newsletter->subscribe($data['email']);

        if (! $added) {
            return back()->with('error', 'Dit e-mailadres staat al op onze lijst of is ongeldig.');
        }

        return back()->with('success', 'Bedankt! We sturen je af en toe een huidverzorgingstip.');
    }
}
