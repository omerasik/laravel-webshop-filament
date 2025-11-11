<?php

namespace App\Filament\Resources\Reviews;

use App\Filament\Resources\Reviews\Pages\ManageReviews;
use App\Models\Review;
use BackedEnum;
use UnitEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static UnitEnum|string|null $navigationGroup = 'Catalogus';

    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Reviews';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->label('Product')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->required(),
                TextInput::make('name')
                    ->label('Naam')
                    ->required()
                    ->maxLength(120),
                TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->maxLength(255),
                Select::make('rating')
                    ->label('Score')
                    ->options([
                        5 => '5 - Uitstekend',
                        4 => '4 - Goed',
                        3 => '3 - Gemiddeld',
                        2 => '2 - Matig',
                        1 => '1 - Slecht',
                    ])
                    ->required(),
                Textarea::make('comment')
                    ->label('Opmerking')
                    ->rows(4)
                    ->maxLength(2000),
                Toggle::make('is_approved')
                    ->label('Goedgekeurd')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Reviewer')
                    ->searchable(),
                TextColumn::make('rating')
                    ->label('Score')
                    ->badge()
                    ->color(fn (int $state) => match (true) {
                        $state >= 4 => Color::Emerald,
                        $state === 3 => Color::Amber,
                        default => Color::Rose,
                    }),
                BadgeColumn::make('is_approved')
                    ->label('Status')
                    ->color(fn ($state) => $state ? 'success' : 'warning')
                    ->icon(fn ($state) => $state ? 'heroicon-m-check' : 'heroicon-m-clock')
                    ->formatStateUsing(fn ($state) => $state ? 'Goedgekeurd' : 'In wachtrij'),
                TextColumn::make('created_at')
                    ->label('Ingediend op')
                    ->dateTime()
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('is_approved')
                    ->label('Status')
                    ->options([
                        '1' => 'Goedgekeurd',
                        '0' => 'In wachtrij',
                    ])
                    ->query(function ($query, $state) {
                        if ($state === null || $state === '') {
                            return $query;
                        }

                        return $query->where('is_approved', (bool) $state);
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageReviews::route('/'),
        ];
    }
}
