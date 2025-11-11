<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterWelcomeMail;
use App\Services\NewsletterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterSubscriptionController extends Controller
{
    public function __construct(private readonly NewsletterService $newsletter)
    {
    }

    // verwerk het formulier voor nieuwsbriefinschrijvingen
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $added = $this->newsletter->subscribe($data['email']);

        if (! $added) {
            return back()->with('error', 'Dit e-mailadres staat al op onze lijst of is ongeldig.');
        }

        Mail::to($data['email'])->send(new NewsletterWelcomeMail($data['email']));

        return back()->with('success', 'Bedankt! We sturen je af en toe een huidverzorgingstip.');
    }
}
