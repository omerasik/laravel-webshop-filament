<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Productnaam')
                    ->helperText('De naam zoals getoond in de webshop.')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->label('Beschrijving')
                    ->helperText('Lange omschrijving voor klanten.')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('price')
                    ->label('Prijs')
                    ->helperText('Inclusief btw.')
                    ->money()
                    ->placeholder('-'),
                TextEntry::make('stock')
                    ->label('Voorraad')
                    ->helperText('Aantal beschikbare stuks.')
                    ->numeric()
                    ->placeholder('-'),
                ImageEntry::make('image')
                    ->disk('public')
                    ->visibility('public')
                    ->label('Afbeelding')
                    ->helperText('Publieke productfoto uit de opslag.')
                    ->placeholder('-')
                    ->height('200px'),
                TextEntry::make('category.name')
                    ->label('Categorie')
                    ->helperText('Hoofdgroep van het product.')
                    ->placeholder('-'),
                TextEntry::make('brand.name')
                    ->label('Merk')
                    ->helperText('Gekoppeld merk voor filters.')
                    ->placeholder('-'),
                TextEntry::make('tags.name')
                    ->label('Tags')
                    ->helperText('Labels voor marketing en filters.')
                    ->badge()
                    ->separator(', ')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->label('Aangemaakt op')
                    ->helperText('Datum waarop het product werd ingevoerd.')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Laatst bijgewerkt')
                    ->helperText('Laatste onderhoud van dit record.')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
