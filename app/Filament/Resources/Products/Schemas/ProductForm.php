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
                    ->default(null),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->numeric()
                    ->default(null)
                    ->prefix('$'),
                TextInput::make('stock')
                    ->numeric()
                    ->default(null),
                FileUpload::make('image')
                    ->image(),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->default(null),
                Select::make('brand_id')
                    ->relationship('brand', 'name')
                    ->default(null),
            ]);
    }
}
