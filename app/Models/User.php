<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'zipcode',
        'bus',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        $allowedEmails = collect(
            explode(',', (string) env('ADMIN_EMAILS', ''))
        )
            ->map(fn (string $email) => strtolower(trim($email)))
            ->filter()
            ->values()
            ->all();

        if (empty($allowedEmails)) {
            return true;
        }

        return in_array(strtolower($this->email), $allowedEmails, true);
    }
}
