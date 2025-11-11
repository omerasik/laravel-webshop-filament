<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('price')
                    ->label('Totaalbedrag')
                    ->helperText('Inclusief btw en verzendkosten (alleen-lezen).')
                    ->numeric()
                    ->prefix('EUR ')
                    ->disabled(),
                TextInput::make('payment_method')
                    ->label('Betaalmethode')
                    ->helperText('Kanaal dat de klant koos tijdens checkout.')
                    ->disabled(),
                Select::make('order_status')
                    ->label('Orderstatus')
                    ->helperText('Gebruik deze status om interne opvolging te sturen.')
                    ->options([
                        'pending' => 'In behandeling',
                        'processing' => 'In verwerking',
                        'completed' => 'Voltooid',
                        'cancelled' => 'Geannuleerd',
                    ])
                    ->required(),
                Select::make('payment_status')
                    ->label('Betaalstatus')
                    ->helperText('Geeft aan of de betaling geslaagd is.')
                    ->options([
                        'pending' => 'In afwachting',
                        'paid' => 'Betaald',
                        'failed' => 'Mislukt',
                    ])
                    ->required(),
                Textarea::make('shipping_address')
                    ->label('Leveradres')
                    ->helperText('Volledig adres zoals bevestigd door de klant.')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
