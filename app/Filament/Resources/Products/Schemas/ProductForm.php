<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Productnaam')
                    ->helperText('De naam zoals klanten hem in de shop zien.')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Beschrijving')
                    ->helperText('Vertel kort wat het product bijzonder maakt.')
                    ->rows(6)
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->label('Prijs')
                    ->helperText('Inclusief btw, gebruik maximaal twee decimalen.')
                    ->numeric()
                    ->required()
                    ->prefix('EUR ')
                    ->minValue(0)
                    ->rule('decimal:0,2'),
                TextInput::make('stock')
                    ->label('Voorraad')
                    ->helperText('Het aantal beschikbare stuks in het magazijn.')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->integer(),
                FileUpload::make('image')
                    ->label('Afbeelding')
                    ->helperText('Upload een scherpe foto van minimaal 1200px breed.')
                    ->directory('products')
                    ->disk('public')
                    ->image()
                    ->imageEditor()
                    ->imagePreviewHeight('200')
                    ->columnSpanFull(),
                Select::make('category_id')
                    ->label('Categorie')
                    ->helperText('Kies de hoofdgroep waarin dit product valt.')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('brand_id')
                    ->label('Merk')
                    ->helperText('Selecteer het merk zodat filters correct werken.')
                    ->relationship('brand', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('tags')
                    ->label('Tags')
                    ->helperText('Selecteer een of meerdere labels die bij het product passen.')
                    ->relationship('tags', 'name')
                    ->preload()
                    ->multiple()
                    ->searchable(),
            ]);
    }
}
