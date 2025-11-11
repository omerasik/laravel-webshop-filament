<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $subscriberEmail)
    {
    }

    public function build(): self
    {
        return $this->subject('Bedankt voor je inschrijving op onze nieuwsbrief')
            ->view('emails.newsletter.welcome')
            ->with([
                'subscriberEmail' => $this->subscriberEmail,
            ]);
    }
}
