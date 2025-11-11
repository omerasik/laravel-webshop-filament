<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Klant')
                    ->searchable(),
                TextColumn::make('price')
                    ->label('Totaal')
                    ->money('eur')
                    ->sortable(),
                BadgeColumn::make('order_status')
                    ->label('Orderstatus')
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'processing',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
                BadgeColumn::make('payment_status')
                    ->label('Betaling')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                    ]),
                TextColumn::make('created_at')
                    ->label('Besteld op')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Status aanpassen')
                    ->icon(null),
            ]);
    }
}
