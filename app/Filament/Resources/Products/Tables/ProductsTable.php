<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Product')
                    ->searchable(),
                TextColumn::make('price')
                    ->label('Prijs')
                    ->money()
                    ->sortable(),
                TextColumn::make('stock')
                    ->label('Voorraad')
                    ->numeric()
                    ->sortable(),
                ImageColumn::make('image')
                    ->label('Afbeelding')
                    ->disk('public')
                    ->visibility('public')
                    ->height(60)
                    ->circular(),
                TextColumn::make('category.name')
                    ->label('Categorie')
                    ->searchable(),
                TextColumn::make('brand.name')
                    ->label('Merk')
                    ->searchable(),
                TextColumn::make('tags.name')
                    ->label('Tags')
                    ->badge()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Aangemaakt op')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Laatst gewijzigd')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
