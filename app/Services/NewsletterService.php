<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class NewsletterService
{
    private const STORAGE_PATH = 'newsletter/subscribers.json';

    public function all(): Collection
    {
        // geef alle ingeschreven adressen terug als collectie
        return collect($this->read());
    }

    public function subscribe(string $email): bool
    {
        // normaliseer email zodat dubbels voorkomen worden
        $email = strtolower(trim($email));

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // lees bestaande records om dubbele inschrijvingen te vermijden
        $records = $this->read();

        if (collect($records)->contains(fn ($item) => $item['email'] === $email)) {
            return false;
        }

        $records[] = [
            'email' => $email,
            'subscribed_at' => now()->toIso8601String(),
        ];

        $this->store($records);

        return true;
    }

    private function read(): array
    {
        // haaljson  uit storage
        if (! Storage::disk('local')->exists(self::STORAGE_PATH)) {
            return [];
        }

        return json_decode(Storage::disk('local')->get(self::STORAGE_PATH), true) ?? [];
    }

    private function store(array $records): void
    {
    
        Storage::disk('local')->put(
            self::STORAGE_PATH,
            json_encode($records, JSON_PRETTY_PRINT)
        );
    }
}
